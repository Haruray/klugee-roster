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
use App\Accounting;

use Session;

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
        //TODO
        //Untuk teacher manager dan admin buat approvalnya perlu rework
        $view = view('admin-attendance-list');
        $attendances = TeachPresence::select('teach_presences.id', 'teach_presences.id_teacher','teach_presences.date','teachers.name','teachers.is_teacher')->join('teachers','teachers.id','=','teach_presences.id_teacher')->where('approved',false)->orderBy('date','DESC')->get();
        return $view->with('attendance',$attendances);
    }

    public function UserAttendanceApproval($id){
        $attendance = TeachPresence::where('id',$id)->update(['approved' => true]);
        return redirect('/user-attendances');
    }

    private function CountCurrentUserFee($user_id){
        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();
        $fee1 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('fee_nominal');
        $fee2 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('lunch_nominal');
        $fee3 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('transport_nominal');
        $fees = $fee1+$fee2+$fee3;
        return $fees;
    }

    public function UserSelectProfile($user_id){
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',$user_id)->distinct()->get();

        //get this month's fee
        $fees = self::CountCurrentUserFee($user_id);
        $view = view('teacher')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedules)->with('fees',$fees)->with('user_id',$user_id);
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
            $schedules_detail = Schedule::join('teach_schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules', 'schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->where('id_teacher',$user_id)->get();
            //return $schedules_detail;

            //fees
            //get this month's fee
            $fees = self::CountCurrentUserFee($user_id);

            $view = view('teacher-students-list')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule_details',$schedules_detail)->with('schedule',$schedules)->with('fees',$fees);
            return $view->with('user_id',$user_id);

    }
    public function UserSelectAttendance($user_id){
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        //Get attendance detail
        $teach_presence = Attendance::select('attendances.id','attendances.date', 'students.name', 'attendances.location', 'attendances.class_type','fees.approved as fee_approval','teach_presences.approved as presence_approval')->join('attendees','attendances.id','=','attendees.id_attendance')->join('students','attendees.id_student','=','students.id')->join('progress', function($join){
            $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        })->join('fees','attendances.id','=','fees.id_attendance')->join('teach_presences','teach_presences.date','=','attendances.date')->where('attendances.id_teacher',auth()->user()->id_teacher)->get();
           $teach_presence_approval=TeachPresence::where('id_teacher',$user_id)->get();
        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee($user_id);
        $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence)->with('approval',$teach_presence_approval);
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
        app('debugbar')->error($user_id);
        //PROSES UPLOAD KE SERVER
        $data =  $request["image"];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $destinationPath = 'uploads/profile-pictures';
        $name = User::where('id_teacher',$user_id)->first()->name;
        $image_name = $user_id.'_'.$name.'.png';

        file_put_contents($destinationPath.'/'.$image_name,$data);


        //PROSES PERUBAHAN DI DATABASE
        $teacher = Teachers::where('id',$user_id)->update(['photo' => $image_name]);

        return response()->json([
            'success' => true,
            'data' => '/'.$destinationPath.'/'.$image_name
        ],200);
    }

    public function UserSelectEarnings($user_id){
        //Basic data
        $profile = Teachers::where('id',$user_id)->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();
        $fee = Fee::join('attendances','fees.id_attendance','=','attendances.id')->whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->get();
        $salary = Salary::whereYear('date',date('y'))->where('id_teacher',$user_id)->get();
        $total_fee = self::CountCurrentUserFee($user_id);
        $incentives = Incentive::whereMonth('date', date('m'))->where('id_teacher',$user_id)->get();


        $view=view('teacher-earnings')->with('fee',$fee)->with('salary',$salary)->with('incentive',$incentives)->with('fees',$total_fee)->with('profile',$profile)->with('position',$position)->with('method',$method);
        return $view->with('user_id',$user_id);
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

    public function IncomeInputTransaction(){
        $user_id = auth()->user()->id;
        $teachers = Teachers::select('id','name')->get();
        $view = view('admin-transaction-input-income');
        return $view->with('user_id',$user_id)->with('teachers',$teachers);
    }

    public function ExpenseInputTransaction(){
        $user_id = auth()->user()->id;
        $teachers = Teachers::select('id','name')->get();
        $view = view('admin-transaction-input-expense');
        return $view->with('user_id',$user_id)->with('teachers',$teachers);
    }

    public function IncomeProcess(Request $request){
        $new_income = new Accounting;
        $new_income->date = $request->input('date');
        $new_income->transaction_type = $request->input('transaction_type');
        $new_income->sub_transaction = $request->input('sub_transaction');
        $new_income->detail = $request->input('detail');
        $new_income->nominal = $request->input('nominal');
        $new_income->notes = $request->input('notes');
        $new_income->pic = $request->input('pic');
        $new_income->payment_method = $request->input('payment_method');
        if ($new_income->save()){
            Session::flash('sukses','Data successfully recorded.');
        }
        else{
            Session::flash('gagal','Error has occured. Failed to record. data.');
        }

        return redirect('/accounting/input-transaction/');
    }
}
