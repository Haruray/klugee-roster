<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Students;
use App\Attendance;
use App\Attendee;

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
}