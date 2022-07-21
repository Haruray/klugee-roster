<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

use App\Students;
use App\Attendance;
use App\Attendee;
use App\Progress;
use App\Program;
use App\StudentProgram;
use App\TuitionFee;
use App\Teachers;
use App\TeachPosition;
use App\TeachMethod;
use App\TeachSchedule;
use App\TeachPresence;
use App\TeachProgram;
use App\Schedule;
use App\StudentSchedule;
use App\Fee;
use App\Salary;
use App\Incentive;
use App\StudentPresence;
use App\FeeList;
use App\IncentiveList;
use App\User;
use App\Accounting;
use App\Referral;

use PDF;

use Session;

class HeadTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('secondlevel');
    }

    public function AttendanceAdmin(){
        $view = view('admin-attendance');
        return $view;
    }

    public function UserProfiles(){
        $view = view('admin-profiles');

        return $view;
    }

    public function UserList(){
        $view = view('teacher-list');
        $users = Teachers::select('teachers.id', 'teachers.name as teachername', 'users.user_type', 'teachers.photo')
        ->join('users','teachers.id','=','users.id_teacher')
        ->orderByRaw('FIELD(users.user_type,"head of institution","super admin","admin","head teacher","teacher")')
        ->get();
        return $view->with('users',$users);
    }

    public function ScheduleAdmin(){
        $view = view('admin-schedule');
        return $view;
    }

    public function UserAttendances(){
        //TODO
        //Untuk teacher manager dan admin buat approvalnya perlu rework
        $view = view('admin-attendance-list');
        $attendances = TeachPresence::select('teach_presences.id as id_presence', 'teach_presences.id_teacher','teach_presences.date','teachers.name','progress.filled','attendances.id as id_attendance')
        ->join('teachers','teachers.id','=','teach_presences.id_teacher')
        ->join('attendances', 'attendances.id','=','teach_presences.id_attendance')
        ->leftjoin('progress',function($join){
            $join->on('progress.id_attendance','=','attendances.id')->on('progress.id_attendance','=','teach_presences.id_attendance')->on('progress.id_attendance','=','teach_presences.id_attendance');
        })
        ->where('teach_presences.approved',false)
        ->orderBy('date','DESC')->distinct()->get();

        return $view->with('attendance',$attendances);
    }

    public function UserAttendanceApproval($id){
        $attendance_presence = TeachPresence::where('id',$id)->update(['approved' => true]);
        //get attendance_id
        $attendance_presence = TeachPresence::where('id',$id)->first();
        $attendance_id = $attendance_presence->id_attendance;
        $teacher_id = $attendance_presence->id_teacher;
        $teacher_name = Teachers::where('id',$teacher_id)->first()->name;
        //get the new progress report data
        $new_pr = Progress::where('id_attendance',$attendance_id)->get();
        if (count($new_pr) == 0){
            //Kalau gaada progress reportnya, langsung balik aja
            return redirect('/user-attendances');
        }
        if ($new_pr[0]->filled){
            //process the payment thing if only the progress report is filled
            //PAYMENT TIME
            $attendance = Attendance::where('id',$attendance_id)->first();
            $cunt = $new_pr[0]->level;
            $payment = new Fee;
            $payment->id_attendance = $attendance->id;
            $payment->fee_nominal = FeeList::where('program', $attendance->program)->where('level', $new_pr[0]->level)->first()['nominal_'.strtolower($attendance->class_type)];

            //Incentives
            //lunch : syarat adalah mengajar dua kali sesi
            $sessions = Attendance::select('attendances.id')->join('teach_presences','teach_presences.id_attendance','attendances.id')
            ->where('attendances.date', $attendance->date)->distinct()->where('attendances.id_teacher', $teacher_id);
            if ($sessions->count() >=2){
                //check apakah buat lunch sudah dibayar untuk hari ini. Karena dibayarnya cuma sekali
                $check_lunch = Fee::join('attendances','fees.id_attendance','=','attendances.id')->where('attendances.date', $attendance->date)
                ->whereIn('fees.id_attendance',$sessions->pluck('attendances.id')->toArray())->where('lunch_nominal','>',0)->count();
                if ($check_lunch == 0){
                    //artinya belum dibayar untuk hari ini
                    $payment->lunch_nominal = IncentiveList::where('name','Lunch')->first()->nominal;
                }
                else{
                    $payment->lunch_nominal = 0;
                }
            }
            else{
                $payment->lunch_nominal = 0;
            }

            //transport
            $payment_incentive_check = IncentiveList::where('name','Transport ('.$attendance->location.')')->first();
            $payment->transport_nominal = is_null($payment_incentive_check) ? 0 : $payment_incentive_check->nominal;
            $payment->approved = false;
            $payment->save();
        }
        return redirect('/user-attendances');
    }

    public function UserAttendanceDelete($id){
        //get attendance_id
        $attendance_presence = TeachPresence::where('id',$id)->first();
        $id_attendance = $attendance_presence->id_attendance;
        $program = Attendance::where('id',$id_attendance)->first()->program;
        $attendance = Attendance::where('id', $id_attendance)->delete();
        //get the new progress report data
        $new_pr = Progress::where('id_attendance',$id_attendance)->delete();
        $student_attendance = StudentPresence::where('id_attendance',$id_attendance)->get();
        //Update SPP Info : delete spp paid on student presence and increase quota
        foreach($student_attendance as $sa){
            if ($sa->spp_paid){
                $stud_a = StudentPresence::where('id',$sa->id)->first();
                $tuition = TuitionFee::where('id_student',$stud_a->id_student)
                ->where('program', $program)
                ->first();
                $tuition->quota++;
                $tuition->save();
                $stud_a->delete();
            }
        }
        $attendance_presence->delete();

        return redirect('/user-attendances');
    }

    private function CountCurrentUserFee($user_id){
        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();
        $fee1 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('fee_nominal');
        $fee2 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('lunch_nominal');
        $fee3 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('transport_nominal');
        $incentive = Incentive::where('id_teacher',$user_id)->whereMonth('date',date('m'))->sum('nominal');
        $fees = $fee1+$fee2+$fee3 + $incentive;
        return $fees;
    }

    public function UserSelectProfile($user_id){
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $program = TeachProgram::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',$user_id)->distinct()->get();

        //get this month's fee
        $fees = self::CountCurrentUserFee($user_id);
        $view = view('teacher')->with('profile',$profile)->with('program',$program)
        ->with('position',$position)->with('method',$method)->with('schedule',$schedules)->with('fees',$fees)->with('user_id',$user_id);
        return $view;
    }

    public function UserSelectStudents($user_id){
            //Basic data
            $profile = Teachers::where('id',$user_id)->first();
            $position = TeachPosition::where('id_teacher', $user_id)->get();
            $method = TeachMethod::where('id_teacher', $user_id)->get();

            //Get teacher's schedule student count
            $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',$user_id)->distinct()->get();
            //Method 2 : join
            $schedules_detail = Schedule::join('teach_schedules','teach_schedules.id_schedule','=','schedules.id')
            ->join('student_schedules', 'schedules.id','=','student_schedules.id_schedule')
            ->join('students','student_schedules.id_student','=','students.id')->orderBy('students.name','ASC')
            ->where('id_teacher',$user_id)->get();
            //return $schedules_detail;

            //fees
            //get this month's fee
            $fees = self::CountCurrentUserFee($user_id);

            $view = view('teacher-students-list')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule_details',$schedules_detail)->with('schedule',$schedules)->with('fees',$fees);
            return $view->with('user_id',$user_id);

    }
    public function UserSelectAttendance($user_id){
        // //Basic data
        // $profile = Teachers::where('id',$user_id)->first();
        // $position = TeachPosition::where('id_teacher', $user_id)->get();
        // $method = TeachMethod::where('id_teacher', $user_id)->get();

        // //Get attendance detail
        // $teach_presence = Attendance::select('attendances.id','attendances.date', 'students.name', 'attendances.location', 'attendances.class_type','fees.approved as fee_approval','teach_presences.approved as presence_approval')->join('attendees','attendances.id','=','attendees.id_attendance')->join('students','attendees.id_student','=','students.id')->join('progress', function($join){
        //     $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        // })->join('fees','attendances.id','=','fees.id_attendance')->join('teach_presences','teach_presences.date','=','attendances.date')->where('attendances.id_teacher',auth()->user()->id_teacher)->get();
        //    $teach_presence_approval=TeachPresence::where('id_teacher',$user_id)->get();
        // //fees
        // //get this month's fee
        // $fees = self::CountCurrentUserFee($user_id);
        // $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence)->with('approval',$teach_presence_approval);
        // return $view->with('user_id',$user_id);
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        //Get attendance detail
        $teach_presence = Attendance::select('attendances.id','attendances.date', 'students.name', 'attendances.location', 'attendances.class_type','fees.approved as fee_approval','teach_presences.approved as presence_approval','progress.filled')
        ->join('attendees','attendances.id','=','attendees.id_attendance')
        ->join('students','attendees.id_student','=','students.id')->leftjoin('progress', function($join){
            $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        })->leftjoin('fees','attendances.id','=','fees.id_attendance')
        ->join('teach_presences','teach_presences.id_attendance','=','attendances.id')
        ->where('attendances.id_teacher',$user_id)->orderBy('attendances.date','DESC')
        ->get();
        $teach_presence_approval=TeachPresence::where('id_teacher',$user_id)->get();
        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee($user_id);
        $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence)->with('approval',$teach_presence_approval);
        //return $teach_presence;
        return $view->with('user_id',$user_id);

    }
    public function UserSelectSchedule($user_id){
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();
        $fees = self::CountCurrentUserFee($user_id);

        $schedule = Schedule::select('schedules.id','schedules.day','schedules.begin','schedules.classroom_type','schedules.classroom_students','schedules.program','schedules.subject','students.name')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->where('id_teacher',$user_id)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();

        $view = view('schedule')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedule)->with('fees',$fees);

        return $view->with('user_id',$user_id);

    }
    public function UserSelectProfilePictureChange(Request $request){
        $user_id = $request["user_id"];
        //PROSES UPLOAD KE SERVER
        $data =  $request["image"];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $destinationPath = 'uploads/profile-pictures';
        $name = User::where('id_teacher',$user_id)->first()->name;
        $image_name = $user_id.'_'.$name.date('m-d-Y h:i:s').'.png';



        file_put_contents($destinationPath.'/'.$image_name,$data);


        //PROSES PERUBAHAN DI DATABASE
        $teacher = Teachers::where('id',$user_id)->update(['photo' => $image_name]);
        $teacher = User::where('id_teacher',$user_id)->update(['photo' => $image_name]);

        return response()->json([
            'success' => true,
            'data' => '/'.$destinationPath.'/'.$image_name
        ],200);
    }


    public function ScheduleAdminManage(){
        $view = view('admin-schedule-manage');
        $teachers = Teachers::select('teachers.name', 'teachers.id', 'teachers.is_teacher')->join('users','users.id_teacher','=','teachers.id')->where('teachers.is_teacher',true)->get();
        $programs = Program::get();
        $students = Students::get();
        return $view->with('teachers',$teachers)->with('program',$programs)->with('student',$students);
    }

    public function ScheduleAdd(Request $request){
        $new_sched = new Schedule;
        $new_sched->day = $request->input('day');
        $new_sched->begin = $request->input('time');
        $new_sched->classroom_type = $request->input('location');
        $new_sched->classroom_students = $request->input('class-type');
        $new_sched->program = $request->input('program');
        $new_sched->subject = $request->input('subject');
        $new_sched->save();


        foreach ($request->input('students') as $s){
            $sched_studs = new StudentSchedule;
            $sched_studs->id_student = $s;
            $sched_studs->id_schedule = $new_sched->id;
            $sched_studs->save();
        }
        $teach_sched = new TeachSchedule;
        $teach_sched->id_teacher = $request->input('teacher-id');
        $teach_sched->id_schedule = $new_sched->id;
        $teach_sched->save();

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function ScheduleDelete($schedule_id){
        $sched_studs = StudentSchedule::where('id_schedule',$schedule_id)->delete();
        $teach_sched = TeachSchedule::where('id_schedule',$schedule_id)->delete();
        $sched = Schedule::where('id',$schedule_id)->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function ScheduleEdit(Request $request){
        $sched = Schedule::where('id',$request->input('schedule-id'))->update([
            'day' => $request->input('day'),
            'begin' => $request->input('time'),
            'classroom_type' => $request->input('location'),
            'classroom_students' => $request->input('class-type'),
            'program' => $request->input('program'),
            'subject' => $request->input('subject')
        ]);

        $sched_studs = StudentSchedule::where('id_schedule',$request->input('schedule-id'))->delete();
        foreach ($request->input('students') as $s){
            $sched_studs = new StudentSchedule;
            $sched_studs->id_student = $s;
            $sched_studs->id_schedule = $request->input('schedule-id');
            $sched_studs->save();
        }
        return response()->json([
            'success' => true,
        ], 200);
    }

    private function CreateTimeJson($day, $teacher_json){
        $schedule = Schedule::select('schedules.day','schedules.begin')
        ->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')
        ->orderBy('schedules.begin','ASC')
        ->where('day',$day)
        ->get();

        $schedule_json = '{';
        foreach($schedule as $s){
            $schedule_json = $schedule_json.'"'.$s->begin.'": '.$teacher_json.',';
        }
        $schedule_json = substr($schedule_json,0,-1);
        $schedule_json= $schedule_json.'}';
        return $schedule_json;

    }

    public function ScheduleAll($teacher){
        if ($teacher!="all"){
            $teachers = Schedule::select('teachers.name','teachers.id')->join('teach_schedules','teach_schedules.id_schedule', '=', 'schedules.id')->join('teachers','teach_schedules.id_teacher','=','teachers.id')->distinct()->get();
            $schedule = Schedule::select('schedules.id','schedules.day','schedules.begin','students.name as student_name', 'teachers.name as teach_name','teachers.id')->selectRaw('DATE_ADD(schedules.begin, INTERVAL 55 MINUTE) as end')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->join('teachers','teachers.id','=','id_teacher')->with('teachers.id',$teacher)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
            //return $schedule;
            $view = view('admin-schedule-all');

            return $view->with([
                'teachers' => $teachers,
                'schedule' => $schedule,
                'selector' => $teacher
            ]);;
        }
        $teachers = Schedule::select('teachers.name','teachers.id')->join('teach_schedules','teach_schedules.id_schedule', '=', 'schedules.id')->join('teachers','teach_schedules.id_teacher','=','teachers.id')->distinct()->get();
        // $schedule = Schedule::select('schedules.day','schedules.begin','students.name as student_name', 'teachers.name as teach_name','teachers.id')->selectRaw('DATE_ADD(schedules.begin, INTERVAL 55 MINUTE) as end')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->join('teachers','teachers.id','=','id_teacher')->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        $schedule = Schedule::select('schedules.day','schedules.begin')
        ->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')
        ->orderBy('schedules.begin','ASC')->get();

        //This is fucking horrible dont laugh.
        $teacher_json = '{';
        $day_json = '{';
        foreach($teachers as $t){
            $teacher_json = $teacher_json.'"'.$t->name.'": null,';
        }
        $teacher_json = substr($teacher_json,0,-1);
        $teacher_json= $teacher_json.'}';

        foreach($schedule as $s){
            $day_json = $day_json.'"'.$s->day.'": '.self::CreateTimeJson($s->day,$teacher_json).',';
        }

        $day_json = substr($day_json,0,-1);
        $day_json= $day_json.'}';

        $schedule_json = json_decode($day_json, true);
        $teacher_json =  json_decode($teacher_json, true);
        //return $schedule_json;
        //filling the actual schedule
        foreach(array_keys($schedule_json) as $day){
            foreach(array_keys($schedule_json[$day]) as $time){
                foreach(array_keys($schedule_json[$day][$time]) as $teach){
                    $schedule = Schedule::select('schedules.day','schedules.begin','students.name as student_name', 'teachers.name as teach_name','teachers.id')
                    ->selectRaw('DATE_ADD(schedules.begin, INTERVAL 55 MINUTE) as end')
                    ->join('student_schedules','schedules.id','=','student_schedules.id_schedule')
                    ->join('students','student_schedules.id_student','=','students.id')
                    ->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')
                    ->join('teachers','teachers.id','=','id_teacher')
                    ->where([
                        ['day',$day],
                        ['begin',$time],
                        ['teachers.name',$teach]
                    ])
                    ->get();
                    $res='';
                    for ($i = 0 ; $i < count($schedule); $i++){
                        if ($i != count($schedule)-1){
                            $res = $res.$schedule[$i]->student_name.', ';
                        }
                        else{
                            $res = $res.$schedule[$i]->student_name;
                        }
                    }
                    $schedule_json[$day][$time][$teach] = $res;
                }
            }
        }
        $schedule = Schedule::select('schedules.day','schedules.begin','students.name as student_name', 'teachers.name as teach_name','teachers.id')->selectRaw('DATE_ADD(schedules.begin, INTERVAL 55 MINUTE) as end')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->join('teachers','teachers.id','=','id_teacher')->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        //return $schedule_json;
        $view = view('admin-schedule-all');

        return $view->with([
            'teachers' => $teachers,
            'teacher_json' => $teacher_json,
            'schedule_json' => $schedule_json,
            'schedule' => $schedule,
            'selector' => $teacher
        ]);
    }

    public function ReportGenerate($student_id, $program){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get attendance id first based on program
        $attendee_data = Attendee::where('id_student',$student_id)->get();
        $attendance_ids = Attendance::whereIn('id', $attendee_data->pluck('id_attendance')->toArray())->where('program',$program)->get();
        $head_teacher = Teachers::join('users','users.id_teacher','=','teachers.id')
        ->where('user_type','head teacher')->first();
        $progress_reports = Progress::select('progress.*','attendances.date')->where('id_student',$student_id)->whereIn('id_attendance', $attendance_ids->pluck('id')->toArray())->join('attendances','attendances.id','progress.id_attendance')->where('generated',false)->where('filled',true)->orderBy('level','ASC')->orderBy('date','ASC')->get();
        $view = view('report-book-generation')->with('progress_report',$progress_reports)
        ->with('attendance',$attendance_ids)
        ->with('student', $studentbio)
        ->with('program',$program)
        ->with('head_teacher',$head_teacher);

        return $view;
    }

    public function ReportGenerateProcess(Request $request){
        //TODO :
        //- RELOAD PAGE AFTER DOWNLOADING THE PDF (USE JAVASCRIPT)
        $progress_reports = Progress::whereIn('id',$request->input('progress'))->update(['generated'=>true]);
        $progress_reports = Progress::whereIn('id',$request->input('progress'))->get();
        $levels = Progress::select('level')->whereIn('id',$request->input('progress'))->distinct()->get();
        $latest_teacher_1 =  Progress::whereIn('progress.id',$request->input('progress'))->join('teachers','teachers.id','=','progress.id_teacher')->get()->reverse()->first()['name'];
        $student = Students::where('id',$progress_reports[0]->id_student)->first();
        $head_teacher = Teachers::select('teachers.name')->join('users','users.id_teacher','=','teachers.id')
        ->where('user_type','head teacher')->first();
        view()->share([
            'data' => $progress_reports,
            'level' => $levels,
            'program' => $request->input('program'),
            'teacher' => $latest_teacher_1,
            'student' => $student,
            'desc_eng' => $request->input('description-english'),
            'desc_ind' => $request->input('description-indo'),
            'score' => $request->input('overall-score'),
            'farewell' => $request->input('farewell'),
            'head_teacher' => $head_teacher,
        ]);
        $pdf = PDF::loadView('reportbook')->setOption('page-width','210')->setOption('page-height','297')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download($student->nickname.'\'s '.$request->input('program').' Report Book.pdf');

    }

    public function TeacherAttendanceInput(){
        $students = Students::get();
        $programs = Program::get();
        $teachers = Teachers::get();

        $view = view('attendance-input');
        return $view->with([
            'students'=> $students,
            'programs' => $programs,
            'another_teacher' => true,
            'teachers' => $teachers,
            ]);
    }

    public function UserSelectEarnings($user_id, $month, $year){
        $years = Attendance::selectRaw('YEAR(date) as year')->distinct()->get();
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        $teachPresences = Attendance::whereMonth('date', $month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();
        $fee = Fee::join('attendances','fees.id_attendance','=','attendances.id')
        ->whereIn('id_attendance', $teachPresences->pluck('id')->toArray())
        ->whereMonth('attendances.date',$month)->whereYear('attendances.date',$year)
        ->orderBy('attendances.date','DESC')
        ->get();
        $salary = Salary::whereMonth('date',$month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();
        $total_fee = self::CountCurrentUserFee($user_id);
        $incentives = Incentive::whereMonth('date', $month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();


        $view=view('teacher-earnings')->with('fee',$fee)->with('salary',$salary)->with('incentive',$incentives)->with('fees',$total_fee)->with('profile',$profile)->with('position',$position)->with('method',$method);
        return $view->with('user_id',$user_id)->with('month',$month)->with('year',$year)->with('years',$years);

    }


}
