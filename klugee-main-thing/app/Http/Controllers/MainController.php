<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Students;
use App\Attendance;
use App\Attendee;
use App\Progress;

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
        return $view->with('students',$students);
    }

    public function AttendanceInputProcess(Request $request){
        //TO DO :
        //Check student duplicate
        //If not present, don't make progress report
        //dont forget about studentprensences database, about spp paid or nah

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

    public function AttendanceProgressReport($attendance_id){
        //TO DO :
        //Check if progress report is filled or no
        //Add more score input if more students are present

        //Get every student on current attendance
        $students = array();
        $attendee = Attendee::where('id_attendance',$attendance_id)->get();
        foreach ($attendee as $a){
            array_push($students, Students::where('id',$a->id_student)->get()->first());
        }
        $view = view('progress-report-input');
        return $view->with('students', $students)->with('attendance_id',$attendance_id);
    }

    public function ProgressReportInputProcess(Request $request){
        //get all progress report with $attendance_id
        $progress_reports = Progress::where('id_attendance',$request->input('attendance_id'))->update([
            'level' => $request->input('level'),
                'unit' => $request->input('unit'),
                'last_exercise' => $request->input('last_exercise'),
                'score' => $request->input('score'),
        ]);

        $new_pr = Progress::where('id_attendance',$request->input('attendance_id'))->get();

        return response()->json([
            'success' => true,
            'attendance_id' => $request->input('attendance_id'),
            'progress_report' => $new_pr
        ], 200);
    }

}
