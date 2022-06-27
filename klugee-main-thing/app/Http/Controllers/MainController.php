<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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
use App\Schedule;
use App\StudentSchedule;
use App\Fee;
use App\Salary;
use App\Incentive;
use App\StudentPresence;
use App\FeeList;
use App\IncentiveList;
use App\Accounting;
use App\SalaryList;
use App\User;

use Session;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firstlevel');
    }

    public function index()
    {
        $view = view('index');
        return $view;
    }

    public function AttendanceMenu(){
        $view = view('attendance');
        return view('attendance');
    }

    public function AttendanceInput(){
        $view = view('attendance-input');
        $students = Students::get();
        $programs = Program::get();
        return $view->with('students',$students)->with('programs',$programs);
    }

    public function AttendanceInputProcess(Request $request){
        $max_stud = 10; //max students for input
        $should_pay_teach_fee = false;
        //Save attendance data
        $new_attendance = new Attendance;
        $new_attendance->id_teacher = auth()->user()->id_teacher;
        $new_attendance->date = $request->input('date');
        $new_attendance->time = $request->input('time');
        $new_attendance->program = $request->input('program');
        $new_attendance->location = $request->input('location');
        $new_attendance->class_type = $request->input('class-type');

        for ($i = 1 ; $i < $max_stud ; $i++){
            //Search for every student input form
            $string_search = "student".$i;
            $string_search_2 = "student-attend-".$i;

            if ($request->input($string_search) != NULL){
                //check if attendee a duplicate or not
                $attendee_check = Attendee::where([
                    ['id_attendance',$new_attendance->id],
                    ['id_student',Students::where('name',$request->input($string_search))->first()['id']]
                ])->first();
                if ($attendee_check!=NULL){
                    continue;
                }
                //Check if attendee is registered to input-ed program
                $student_program_check = Students::join('student_programs','student_programs.id_student','=','students.id')->where([
                    ['name',$request->input($string_search)],
                    ['program',$request->input('program')]
                    ])->get();
                if (count($student_program_check) <= 0){
                    Session::flash('unregistered-program', $request->input($string_search).' belum terdaftar di program yang dimasukkan.');
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to save data. Student is not registered to a program.'
                    ], 401);
                }
                if (!$new_attendance->save()){
                    //If it doesn't success, then return error message
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to save data. Please reload and re-enter the data.'
                    ], 401);
                }
                //Save attendee data
                $new_attendee = new Attendee;
                $new_attendee->id_attendance = $new_attendance->id;
                $new_attendee->id_student = Students::where('name',$request->input($string_search))->first()['id'];
                if ($request->input($string_search_2)=='no'){
                    $new_attendee->present = false;
                }
                else{
                    $new_attendee->present = true;
                }
                if (!$new_attendee->save()){
                    //If it doesn't success, then return error message
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to save data. Please reload and re-enter the data.'
                    ], 401);
                }
                if ($new_attendee->present){
                    $should_pay_teach_fee = true;
                    //Create progress report
                    $new_progress = new Progress;
                    $new_progress->id_teacher = $new_attendance->id_teacher;
                    $new_progress->id_student = $new_attendee->id_student;
                    $new_progress->id_attendance = $new_attendance->id;
                    $new_progress->filled = false;
                    $new_progress->save();
                    if (!$new_progress->save()){
                        //If it doesn't success, then return error message
                        return response()->json([
                            'success' => false,
                            'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                        ], 401);
                    }
                    //Student presences
                    $student_fee = TuitionFee::where('id_student',$new_attendee->id_student)->where('program',$new_attendance->program)->first();

                    $new_student_presence = new StudentPresence;
                    $new_student_presence->id_student = $new_attendee->id_student;
                    $new_student_presence->id_attendance = $new_attendance->id;
                    $new_student_presence->spp_paid = $student_fee->quota > 0 ? true:false;
                    $new_student_presence->save();

                    //update spp
                    $initial_quota = $student_fee->quota;
                    if ($initial_quota>0){
                        $student_fee = TuitionFee::where('id_student',$new_attendee->id_student)->where('program',$new_attendance->program)->update([
                            'quota' => $initial_quota-1
                        ]);
                    }
                }
            }
        }
        //Save teacher's attendance
        $new_presence = new TeachPresence;
        $new_presence->id_teacher = auth()->user()->id_teacher;
        $new_presence->date = $request->input('date');
        $new_presence->id_attendance = $new_attendance->id;
        $new_presence->save();

        return response()->json([
            'success' => true,
            'attendance_id' => $new_attendance->id,
        ], 200);
    }

    private function CheckAttendeeDuplicate($attendance_id, $name){
        $attendee_data = GetAttendee($attendance_id);

        foreach ($attendee_data as $a){
            if ($a->name == $name){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function AttendanceEdit(Request $request){
        //UNTESTED
        $edited_attendance = Attendance::where('id',$request->input('attendance_id'))->update([
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'program'=> $request->input('program'),
            'location' => $request->input('location'),
            'class_type' => $request->input('class-type')
        ]);
        for ($i = 1 ; $i < $max_stud ; $i++){
            //Search for every student input form
            $string_search = "student".$i;
            $string_search_2 = "student-attend-".$i;
            if ($request->input($string_search) != NULL){
                //check sudah ada di database atau tidak
                //Kalau iya, edit aja
                if (self::CheckAttendeeDuplicate($request->input('attendance_id'), $request->input($string_search))){
                    $student_present_new = Attendee::where('name', $request->input($string_search))->first()->present;
                    $need_new_progress_report = !$student_present_new;
                    if ($request->input($string_search_2)=='no'){
                        $student_present_new = false;
                    }
                    else{
                        $student_present_new = true;
                    }
                    $updated_attendee = Attendee::where('name', $request->input($string_search))->update([
                        'present' => $student_present_new
                    ]);

                    if ($need_new_progress_report && $student_present_new){
                        $new_progress = new Progress;
                        $new_progress->id_teacher = $edited_attendance->id_teacher;
                        $new_progress->id_student = Attendee::where('name', $request->input($string_search))->first()->id;
                        $new_progress->id_attendance = $edited_attendance->id;
                        $new_progress->filled = false;
                        $new_progress->save();
                        if (!$new_progress->save()){
                            //If it doesn't success, then return error message
                            return response()->json([
                                'success' => false,
                                'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                            ], 401);
                        }
                    }

                }
                else{
                    //Save attendee data
                    $new_attendee = new Attendee;
                    $new_attendee->id_attendance = $edited_attendance->id;
                    $new_attendee->id_student = Students::where('name',$request->input($string_search))->first()['id'];
                    if ($request->input($string_search_2)=='no'){
                        $new_attendee->present = false;
                    }
                    else{
                        $new_attendee->present = true;
                    }
                    if (!$new_attendee->save()){
                        //If it doesn't success, then return error message
                        return response()->json([
                            'success' => false,
                            'message' => 'Fail to save data. Please reload and re-enter the data.'
                        ], 401);
                    }
                    //Create progress report
                    $new_progress = new Progress;
                    $new_progress->id_teacher = $edited_attendance->id_teacher;
                    $new_progress->id_student = $new_attendee->id_student;
                    $new_progress->id_attendance = $edited_attendance->id;
                    $new_progress->filled = false;
                    $new_progress->save();
                    if (!$new_progress->save()){
                        //If it doesn't success, then return error message
                        return response()->json([
                            'success' => false,
                            'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                        ], 401);
                    }
                }


            }
        }

        return response()->json([
            'success' => true,
            'attendance_id' => $new_attendance->id,
        ], 200);
    }

    private function GetAttendee($attendance_id){
        $students = array();
        $attendee = Attendee::where('id_attendance',$attendance_id)->get();
        foreach ($attendee as $a){
            array_push($students, Students::where('id',$a->id_student)->get()->first());
        }
        return $students;
    }

    private function GetPresentAttendee($attendance_id){
        $students = array();
        $attendee = Attendee::where('id_attendance',$attendance_id)
        ->join('attendances','attendances.id','=','attendees.id_attendance')->get();
        foreach ($attendee as $a){
            if ($a->present)
                array_push($students, Students::where('id',$a->id_student)->get()->first());
        }
        return $students;
    }

    public function AttendanceProgressReport($attendance_id){
        //Get every student on current attendance
        $students = self::GetPresentAttendee($attendance_id);
        $flag; //for checking whether all the students present or not
        $filled = Progress::where('id_attendance',$attendance_id)->first()->filled;
        if (!$filled){
            $spp_warning = array(); //for SPP warning
            $program = Attendance::where('id',$attendance_id)->first()->program;
            $attendee = Attendee::where('id_attendance',$attendance_id)->join('tuition_fees','tuition_fees.id_student','=','attendees.id_student')
            ->join('students','students.id','=','attendees.id_student')->where('program',$program)->get();
            $flag = $attendee[0]->present;
            foreach ($attendee as $a){
                $flag = $flag || $a->present;
                if ($a->quota <= 0){
                    $warning_string = $a->name.' belum membayar SPP '.$a->program.' untuk bulan ini.';
                    array_push($spp_warning,$warning_string);
                }

            }
            //SPP warning stuff
            Session::flash('spp-warning', $spp_warning);
            $levels = FeeList::select('level')->where('program', $program)->get();
            $view = view('progress-report-input');
            return $view->with('students', $students)->with('attendance_id',$attendance_id)->with('flag',$flag)->with('levels',$levels)->with('program', $program);
        }
        else{
            return redirect('/attendance/progress-report/'.$attendance_id.'/filled');
        }

 }
    public function ProgressView($attendance_id){
        $progress = Attendance::join('progress','progress.id_attendance','=','attendances.id')->where('attendances.id',$attendance_id)->get();
        if ($progress[0]->filled){
            $view = view('progress-report-confirm');
            return $view->with('progress',$progress);
        }
        else{
            return redirect('/attendance/progress-report/'.$attendance_id);
        }
    }

    public function AttendanceView($attendance_id){
        //Get every student on current attendance
        $students = self::GetAttendee($attendance_id);
        $attendance = Attendance::where('id', $attendance_id)->get()->first();
        $view = view('attendance-input-confirm');
        return $view->with('students', $students)->with('attendance',$attendance);
    }

    public function ProgressReportInputProcess(Request $request){
        //get all progress report with $attendance_id
        // TODO : Level input validation
        if ($request->input('level')==null || $request->input('unit')==null || $request->input('last_exercise')==null){
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ],401);
        }
        //Move the file to uploads\progress-reports
        //if there's a documentation image
        if (!is_null($request->file('documentation'))){
            $file = $request->file('documentation');
            $destinationPath = 'uploads/progress-reports';
            $documentation_file_name = auth()->user()->name."_Progress-report_".$request->input('attendance_id').'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath,$documentation_file_name);
        }
        else{
            $documentation_file_name = null;

        }


        /*$progress_reports = Progress::where('id_attendance',$request->input('attendance_id'))->update([
            'level' => $request->input('level'),
                'unit' => $request->input('unit'),
                'last_exercise' => $request->input('last_exercise'),
                'score' => $request->input('score'),
                'note' => $request->input('note'),
                'documentation' =>$documentation_file_name,
        ]);*/

        //Update data individually per student
        $students = self::GetAttendee($request->input('attendance_id'));
        foreach ($students as $student){
            $score_id = 'score-'.$student->id;
            $progress_report_update = Progress::where([
                ['id_attendance', $request->input('attendance_id')],
                ['id_student', $student->id]
            ])->update([
                'level' => $request->input('level'),
                'unit' => $request->input('unit'),
                'last_exercise' => $request->input('last_exercise'),
                'score' => $request->input($score_id),
                'note' => $request->input('note'),
                'documentation' =>$documentation_file_name,
                'filled' => true
            ]);
        }

        //get the new progress report data
        $new_pr = Progress::where('id_attendance',$request->input('attendance_id'))->get();

        // //PAYMENT TIME
        // $attendance = Attendance::where('id',$request->input('attendance_id'))->first();
        // $cunt = $new_pr[0]->level;
        // $payment = new Fee;
        // $payment->id_attendance = $attendance->id;
        // $payment->fee_nominal = FeeList::where('program', $attendance->program)->where('level', $new_pr[0]->level)->first()['nominal_'.strtolower($attendance->class_type)];

        // //Incentives
        // //lunch : syarat adalah mengajar dua kali sesi
        // $sessions = Attendance::whereDay('date',date('d', strtotime($attendance->date)))->where('id_teacher', auth()->user()->id_teacher)->count();
        // if ($sessions>=2){
        //     //check apakah buat lunch sudah dibayar untuk hari ini. Karena dibayarnya cuma sekali
        //     $check_lunch = Fee::join('attendances','fees.id_attendance','=','attendances.id')->where('date',date('d', strtotime($attendance->date)))->sum('lunch_nominal');
        //     if ($check_lunch == 0){
        //         //artinya belum dibayar untuk hari ini
        //         $payment->lunch_nominal = IncentiveList::where('name','Lunch')->first()->nominal;
        //     }
        //     else{
        //         $payment->lunch_nominal = 0;
        //     }
        // }
        // else{
        //     $payment->lunch_nominal = 0;
        // }

        // //transport
        // $payment_incentive_check = IncentiveList::where('name','Transport ('.$attendance->location.')')->first();
        // $payment->transport_nominal = is_null($payment_incentive_check) ? 0 : $payment_incentive_check->nominal;
        // $payment->approved = false;
        // $payment->save();

        // //Saving it in accounting
        // //main fee
        // $payment_accounting = new Accounting;
        // $payment_accounting->date = $attendance->date;
        // $payment_accounting->transaction_type = "Teacher's Fee";
        // $payment_accounting->sub_transaction = auth()->user()->name."'s Fee";
        // $payment_accounting->detail = "Main fee";
        // $payment_accounting->nominal = $payment->fee_nominal*-1;
        // $payment_accounting->pic = 1;
        // $payment_accounting->payment_method = "Other";
        // $payment_accounting->notes = "This payment is automated";
        // $payment_accounting->approved = false;
        // $payment_accounting->save();
        // //incentives : lunch
        // if ($payment->lunch_nominal >0){
        //     $payment_accounting = new Accounting;
        //     $payment_accounting->date = $attendance->date;
        //     $payment_accounting->transaction_type = "Teacher's Fee";
        //     $payment_accounting->sub_transaction = auth()->user()->name."'s Lunch Incentives";
        //     $payment_accounting->detail = "Lunch Incentives";
        //     $payment_accounting->nominal = $payment->lunch_nominal*-1;
        //     $payment_accounting->pic = 1;
        //     $payment_accounting->payment_method = "Other";
        //     $payment_accounting->notes = "This payment is automated";
        //     $payment_accounting->approved = false;
        //     $payment_accounting->save();
        // }
        // //incentives : transport
        // if ($payment->transport_nominal >0){
        //     $payment_accounting = new Accounting;
        //     $payment_accounting->date = $attendance->date;
        //     $payment_accounting->transaction_type = "Teacher's Fee";
        //     $payment_accounting->sub_transaction = auth()->user()->name."'s Transport Incentives";
        //     $payment_accounting->detail = "Transport Incentives";
        //     $payment_accounting->nominal = $payment->transport_nominal*-1;
        //     $payment_accounting->pic = 1;
        //     $payment_accounting->payment_method = "Other";
        //     $payment_accounting->notes = "This payment is automated";
        //     $payment_accounting->approved = false;
        //     $payment_accounting->save();
        // }
        return response()->json([
            'success' => true,
            'attendance_id' => $request->input('attendance_id'),
            'progress_report' => $new_pr
        ], 200);
    }

    public function Students(){
        $students = Students::get();
        $view = view('student-list');

        return $view->with('students',$students);
    }

    public function StudentsData($student_id){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get programs and payment status
        $student_programs = StudentProgram::where('id_student',$student_id)->get();
        $student_tuition_payment = TuitionFee::whereIn('id_student',$student_programs->pluck('id_student')->toArray())->get();

        $view = view('student');

        return $view->with('student', $studentbio)->with('programs', $student_tuition_payment);
    }

    public function AttendanceHistory(){
        $view = view('attendance-history');

        return $view;
    }

    public function StudentsProgressReport($student_id, $program){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get attendance id first based on program
        $attendee_data = Attendee::where('id_student',$student_id)->get();
        $attendance_ids = Attendance::whereIn('id', $attendee_data->pluck('id_attendance')->toArray())->where('program',$program)->get();
        /*
        $attendance_ids = array();
        foreach($attendee_data as $ad){
            array_push($attendance_ids, Attendance::where([
                ['id', $ad->id_attendance],
                ['program', $program]
                ])->first());
        }*/

        /*
        $progress_reports = array();
        foreach($attendance_ids as $aids){
            $pr = Progress::where([
                ['id_student',$student_id],
                ['id_attendance',$aids->id]
            ])->first();
            array_push($progress_reports, $pr);
        }*/
        $progress_reports = Progress::where('id_student',$student_id)->whereIn('id_attendance', $attendance_ids->pluck('id')->toArray())->get();
        $view = view('progress-report-list')->with('progress_report',$progress_reports)->with('attendance',$attendance_ids)->with('student', $studentbio)->with('program',$program)->with('student_id',$student_id);

        return $view;
    }

    private function CountCurrentUserFee(){
        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',auth()->user()->id_teacher)->get();
        $fee1 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('fee_nominal');
        $fee2 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('lunch_nominal');
        $fee3 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('transport_nominal');
        $fees = $fee1+$fee2+$fee3;
        return $fees;
    }

    public function CurrentUserProfile(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->distinct()->get();

        //get this month's fee
        $fees = self::CountCurrentUserFee();
        $view = view('teacher')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedules)->with('fees',$fees)->with('user_id',$user_id);
        return $view;
    }



    public function ProfilePictureChange(Request $request){
        //PROSES UPLOAD KE SERVER
        $data =  $request["image"];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $destinationPath = 'uploads/profile-pictures';
        $image_name = auth()->user()->id_teacher.'_'.auth()->user()->name.'.png';

        file_put_contents($destinationPath.'/'.$image_name,$data);


        //PROSES PERUBAHAN DI DATABASE
        $teacher = Teachers::where('id',auth()->user()->id_teacher)->update(['photo' => $image_name]);
        $teacher = User::where('id_teacher',auth()->user()->id_teacher)->update(['photo' => $image_name]);

        return response()->json([
            'success' => true,
            'data' => '/'.$destinationPath.'/'.$image_name
        ],200);
    }

    public function CurrentUserStudents(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->distinct()->get();
        //Method 2 : join
        $schedules_detail = Schedule::join('teach_schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules', 'schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->where('id_teacher',auth()->user()->id_teacher)->get();
        //return $schedules_detail;

        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();

        $view = view('teacher-students-list')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule_details',$schedules_detail)->with('schedule',$schedules)->with('fees',$fees);
        return $view->with('user_id',$user_id);
    }

    public function CurrentUserAttendance(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        //Get attendance detail
        $teach_presence = Attendance::select('attendances.id','attendances.date', 'students.name', 'attendances.location', 'attendances.class_type','fees.approved as fee_approval','teach_presences.approved as presence_approval')->join('attendees','attendances.id','=','attendees.id_attendance')->join('students','attendees.id_student','=','students.id')->join('progress', function($join){
            $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        })->join('fees','attendances.id','=','fees.id_attendance')->join('teach_presences','teach_presences.id_attendance','=','attendances.id')->where('attendances.id_teacher',auth()->user()->id_teacher)->get();
        $teach_presence_approval=TeachPresence::where('id_teacher',auth()->user()->id_teacher)->get();
        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();
        $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence)->with('approval',$teach_presence_approval);
        //return $teach_presence;
        return $view->with('user_id',$user_id);
    }

    public function Schedule(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();
        $schedule = Schedule::select('schedules.id','schedules.day','schedules.begin','schedules.classroom_type','schedules.classroom_students','schedules.program','schedules.subject','students.name')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        $fees = self::CountCurrentUserFee();
        //return $schedule;
        $view = view('schedule')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedule)->with('fees',$fees);
        return $view->with('user_id',$user_id);
    }

    public function Earnings(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();
        $fee = Fee::join('attendances','fees.id_attendance','=','attendances.id')->whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->get();
        $salary = Salary::whereYear('date',date('y'))->where('id_teacher',$user_id)->get();
        $total_fee = self::CountCurrentUserFee();
        $incentives = Incentive::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();


        $view=view('teacher-earnings')->with('fee',$fee)->with('salary',$salary)->with('incentive',$incentives)->with('fees',$total_fee)->with('profile',$profile)->with('position',$position)->with('method',$method);
        return $view->with('user_id',$user_id);
    }

    public function Management(){
        $programs = Program::select('program')->get();
        $feelist = FeeList::get();
        $incentives = IncentiveList::get();
        $salary = SalaryList::get();
        $programs = Program::get();
        $view = view('management');
        return $view->with([
            'programs'=>$programs,
            'feelist' => $feelist,
            'incentive' =>$incentives,
            'salary' => $salary,
            'programs' => $programs,
        ]);
    }

    public function ProfileEdit(Request $request){
        $teacher = Teachers::where('id',$request->input('id_teacher'))->first();
        $teacher->address = $request->input('alamat');
        $teacher->phone_contact = $request->input('telp');
        $teacher->name=$request->input('nama');
        $teacher->nik = $request->input('nik');
        $teacher->birthdate = $request->input('tanggal-lahir');
        $teacher->save();
        return redirect()->back();
    }

    public function Notification(){
        $view = view('notification');
        return $view;
    }

    public function MarkNotification($notif_id){
        auth()->user()
            ->unreadNotifications
            ->when($notif_id, function ($query) use ($notif_id) {
                return $query->where('id', $notif_id);
            })
            ->markAsRead();

        return redirect()->back();
    }

    public function MarkAllNotification(){
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()->back();
    }
}
