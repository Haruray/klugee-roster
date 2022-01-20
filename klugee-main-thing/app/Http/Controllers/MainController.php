<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use ImageOptimizer;

use App\Students;
use App\Attendance;
use App\Attendee;
use App\Progress;
use App\Program;

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

        $max_stud = 10; //max students for input
        //Save attendance data
        $new_attendance = new Attendance;
        $new_attendance->id_teacher = auth()->user()->id_teacher;
        $new_attendance->date = $request->input('date');
        $new_attendance->time = $request->input('hour');
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
        $edited_attendance = Attendance::where('id',$request->input('attendance_id'))->update([
            'date' => $request->input('date'),
            'time' => $request->input('hour'),
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
        // - update the score individually
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
        ImageOptimizer::optimize(public_path().'/uploads/progress-reports/'.$documentation_file_name);

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

}
