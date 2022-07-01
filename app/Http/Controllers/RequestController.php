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
use App\User;
use App\Referrer;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetStudentData(){
        $students = Students::get();
        return $students;
    }

    public function GetAttendanceData($id){
        $attendance = Attendance::where('id',$id)->get()->first();
        $attendee = Attendee::where('id_attendance',$attendance->id)->get();
        $students=array();
        foreach ($attendee as $d){
            array_push($students, Students::where('id',$d->id_student)->get()->first());
        }
        $arr['attendance'] = $attendance;
        $arr['attendee'] = $students;
        return $arr;
    }

    public function GetDocumentation($attendance_id){
        $documentation = Progress::where('id',$attendance_id)->first()->documentation;
        return response()->json([
            'success' => true,
            'documentation' => $documentation
        ],200);
    }

    public function GetSchedule($teacher_id){
        $schedule = Schedule::select('teachers.nickname','schedules.id','schedules.day','schedules.begin','schedules.classroom_type','schedules.classroom_students','schedules.program','schedules.subject','students.name')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->join('teachers','teachers.id','=','teach_schedules.id_teacher')->where('id_teacher',$teacher_id)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        return response()->json([
            'success' => true,
            'schedule' => $schedule,
        ],200);
    }

    public function GetScheduleWithId($schedule_id){
        $schedule = Schedule::select('students.id as student_id','teachers.nickname','schedules.id','schedules.day','schedules.begin','schedules.classroom_type','schedules.classroom_students','schedules.program','schedules.subject','students.name')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->join('teachers','teachers.id','=','teach_schedules.id_teacher')->where('schedules.id',$schedule_id)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        return response()->json([
            'success' => true,
            'schedule' => $schedule,
        ],200);
    }

    public function GetTeachers(){
        $teachers = Teachers::join('users','users.teachers.id_teacher','=','teacher.id')->get();
        return response()->json([
            'success' => true,
            'teachers' => $teachers
        ],200);
    }

    public function GetParentPartner(){
        $parent = Referrer::get();
        return response()->json([
            'success' => true,
            'parent' => $parent,
        ],200);
    }

    public function GetTeachingInfo($id){
        $data = Attendance::join('progress','progress.id_attendance','=','attendances.id')
            ->join('attendees','attendees.id_attendance','=','attendances.id')
            ->join('students','students.id','=','attendees.id_student')
            ->where('attendances.id',$id)->get();
        return response()->json([
            'success' => true,
            'progress' => $data,
        ],200);
    }
}
