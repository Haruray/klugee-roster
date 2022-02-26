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

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
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
        $users = Teachers::select('teachers.id', 'teachers.name as teachername', 'users.user_type', 'teachers.photo')->join('users','teachers.id','=','users.id_teacher')->get();
        return $view->with('users',$users);
    }

    public function ScheduleAdmin(){
        $view = view('admin-schedule');
        return $view;
    }

    public function Accounting(){
        $view = view('admin-accounting');
        return $view;
    }

    public function InputTransaction(){
        $view = view('admin-acccounting-input');
        return $view;
    }

    public function UserAttendances(){
        $view = view('admin-attendance-list');
        $attendances = TeachPresence::select('teach_presences.id', 'teach_presences.id_teacher','teach_presences.date','teachers.name','teachers.is_teacher')->join('teachers','teachers.id','=','teach_presences.id_teacher')->where('approved',false)->orderBy('date','DESC')->get();
        return $view->with('attendance',$attendances);
    }

    public function UserAttendanceApproval($id){
        $attendance = TeachPresence::where('id',$id)->update(['approved' => true]);
        return redirect('/user-attendances');
    }
}
