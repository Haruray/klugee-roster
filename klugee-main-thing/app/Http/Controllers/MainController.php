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


class MainController extends Controller
{
    public $user_type;
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user_type = auth()->user()->user_type;
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
        //TO DO :
        // - Check student duplicate
        // - If not present, don't make progress report
        // - dont forget about studentprensences database, about spp paid or nah
        // - same about teacher presence and fees
        // - If student not registered in a certain program, dont process the data and give warning

        $max_stud = 10; //max students for input
        //Save attendance data
        $new_attendance = new Attendance;
        $new_attendance->id_teacher = auth()->user()->id_teacher;
        $new_attendance->date = $request->input('date');
        $new_attendance->time = $request->input('time');
        $new_attendance->program = $request->input('program');
        $new_attendance->location = $request->input('location');
        $new_attendance->class_type = $request->input('class-type');
        if (!$new_attendance->save()){
            //If it doesn't success, then return error message
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ], 401);
        }
        for ($i = 1 ; $i < $max_stud ; $i++){
            //Search for every student input form
            $string_search = "student".$i;
            $string_search_2 = "student-attend-".$i;
            if ($request->input($string_search) != NULL){
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
            }
        }

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

    public function AttendanceProgressReport($attendance_id){
        //TO DO :
        // - Check if progress report is filled or no
        // - If no one is present, then give warning

        //Get every student on current attendance
        $students = self::GetAttendee($attendance_id);
        $flag = true;
        foreach (Attendee::where('id_attendance',$attendance_id)->get() as $attendee){
            if (!$attendee->present){
                $flag=false;
            }
        }
        $view = view('progress-report-input');
        return $view->with('students', $students)->with('attendance_id',$attendance_id)->with('flag',$flag);
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
        
        if ($request->input('level')==null || $request->input('unit')==null || $request->input('last_exercise')==null){
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ],401);
        }
        //Move the file to uploads\progress-reports
        $file = $request->file('documentation');
        $destinationPath = 'uploads/progress-reports';
        $documentation_file_name = auth()->user()->name."_Progress-report_".$request->input('attendance_id').'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$documentation_file_name);

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
        $student_tuition_payment = array();
        foreach ($student_programs as $sp){
            $program_payment = TuitionFee::where([
                'id_student' => $sp->id_student,
                'program' => $sp->program
            ])->get()->first();
            array_push($student_tuition_payment, $program_payment);
        }
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
        $view = view('progress-report-list')->with('progress_report',$progress_reports)->with('attendance',$attendance_ids)->with('student', $studentbio);

        return $view;
    }

    private function CountCurrentUserFee(){
        $teachPresences = TeachPresence::whereMonth('date', date('m'))->where('id_teacher',auth()->user()->id_teacher)->get();
        $fee1 = Fee::whereIn('id_teach_presences', $teachPresences->pluck('id')->toArray())->where('approved',1)->sum('fee_nominal');
        $fee2 = Fee::whereIn('id_teach_presences', $teachPresences->pluck('id')->toArray())->where('approved',1)->sum('lunch_nominal');
        $fee3 = Fee::whereIn('id_teach_presences', $teachPresences->pluck('id')->toArray())->where('approved',1)->sum('transport_nominal');
        $fees = $fee1+$fee2+$fee3;
        return $fees;
    }

    public function CurrentUserProfile(){
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();
        
        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->distinct()->get();

        //get this month's fee
        $fees = self::CountCurrentUserFee();
        $view = view('teacher')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedules)->with('fees',$fees);
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
        
        return response()->json([
            'success' => true,
            'data' => '/'.$destinationPath.'/'.$image_name
        ],200);
    }

    public function CurrentUserStudents(){
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();
        
        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->distinct()->get();
        //Get students based on the schedule
        //Method 1 : shitty
        //$schedules_students_id = StudentSchedule::select('id_student')->whereIn('id_schedule',$schedule_ids->pluck('id_schedule')->toArray())->get();
        //$students = Students::whereIn('id', $schedules_students_id)->get();
        //student's program based on the schedule
        //$students_program = Schedule::select('program')->whereIn('id',$schedule_ids->pluck('id_schedule')->toArray())->whereIn('id_student',  $schedules_students_id->pluck('id_student')->toArray())->get();
        
        //Method 2 : join
        $schedules_detail = Schedule::join('student_schedules', 'schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->get();
        //return $schedules_detail;

        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();

        $view = view('teacher-students-list')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule_details',$schedules_detail)->with('schedule',$schedules)->with('fees',$fees);
        return $view;
    }

    public function CurrentUserAttendance(){
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();
        
        //Get attendance detail
        $teach_presence = TeachPresence::join('attendances','teach_presences.id_attendance','=','attendances.id')->join('attendees','teach_presences.id_attendance','=','attendees.id_attendance')->join('students','attendees.id_student','=','students.id')->join('progress','attendances.id','=','progress.id_attendance')->join('fees','teach_presences.id','=','fees.id_teach_presences')->get();
        
        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();

        $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence);
        return $view;
    }

    public function Schedule(){
        $schedule = Schedule::join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->get();
        
        $view = view('schedule')->with('schedule',$schedule);

        return $view;
    }

    public function Earnings(){
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        $teachPresences = TeachPresence::whereMonth('date', date('m'))->where('id_teacher',auth()->user()->id_teacher)->get();
        $fee = Fee::join('teach_presences','fees.id_teach_presences','=','teach_presences.id')->join('attendances','teach_presences.id_attendance','=','attendances.id')->whereIn('id_teach_presences', $teachPresences->pluck('id')->toArray())->get();
        $salary = Salary::whereYear('date',date('y'))->where('id_teacher',auth()->user()->id_teacher)->get();
        $total_fee = self::CountCurrentUserFee();
        $incentives = Incentive::whereMonth('date', date('m'))->where('id_teacher',auth()->user()->id_teacher)->get();
        
        
        $view=view('teacher-earnings')->with('fee',$fee)->with('salary',$salary)->with('incentive',$incentives)->with('fees',$total_fee)->with('profile',$profile)->with('position',$position)->with('method',$method);
        return $view;
    }

}
