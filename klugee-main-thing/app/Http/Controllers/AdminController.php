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

use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        //$this->middleware('superadmin');
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

        return redirect('/accounting/input-transaction/income');
    }

    public function ExpenseProcess(Request $request){
        $new_income = new Accounting;
        $new_income->date = $request->input('date');
        $new_income->transaction_type = $request->input('transaction_type');
        $new_income->sub_transaction = $request->input('sub_transaction');
        $new_income->detail = $request->input('detail');
        $new_income->nominal = $request->input('nominal') * -1;
        $new_income->notes = $request->input('notes');
        $new_income->pic = $request->input('pic');
        $new_income->payment_method = $request->input('payment_method');
        if ($new_income->save()){
            Session::flash('sukses','Data successfully recorded.');
        }
        else{
            Session::flash('gagal','Error has occured. Failed to record data.');
        }

        return redirect('/accounting/input-transaction/expense');
    }

    public function FinancialData(){
        $view = view('admin-financial');
        return $view;
    }

    public function FinancialReport($month, $year){
        $years = Accounting::selectRaw('YEAR(date) as year')->distinct()->get();
        $income_spp = Accounting::select('id','transaction_type','nominal')->where('transaction_type','SPP')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();
        $income_regis = Accounting::select('id','transaction_type','nominal')->where('transaction_type','Registration')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();
        $income_other = Accounting::select('id','transaction_type','sub_transaction','nominal')->where('transaction_type','Other')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();

        $expense = Accounting::selectRaw('transaction_type, SUM(nominal)*-1 as total')->where('nominal','<','0')->groupBy('transaction_type')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();
        $view = view('admin-monthly-report');
        return $view->with('years',$years)->with('income_spp',$income_spp)->with('income_regis',$income_regis)->with('income_other',$income_other)->with('expense',$expense)->with('selected_year',$year)->with('selected_month',$month);
    }

    public function FinancialRecap(){
        $view = view('admin-accounting-recap');
        return $view;
    }

    public function FinancialRecapIncome($month, $year){
        $years = Accounting::selectRaw('YEAR(date) as year')->distinct()->get();

        $income = Accounting::select('accountings.*','teachers.name')->join('teachers','accountings.pic','=','teachers.id')->where('nominal','>','0')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();

        $view = view('admin-monthly-recap-income');
        return $view->with('years',$years)->with('income',$income)->with('selected_year',$year)->with('selected_month',$month);
    }

    public function FinancialRecapExpense($month, $year){
        $years = Accounting::selectRaw('YEAR(date) as year')->distinct()->get();

        $income = Accounting::select('accountings.*','teachers.name')->join('teachers','accountings.pic','=','teachers.id')->where('nominal','<','0')->whereMonth('date','=',$month)->whereYear('date','=',$year)->get();

        $view = view('admin-monthly-recap-expense');
        return $view->with('years',$years)->with('income',$income)->with('selected_year',$year)->with('selected_month',$month);
    }

    public function SPP(){
        $user_id = auth()->user()->id;
        $teachers = Teachers::select('id','name')->get();
        $students = Students::select('id','name')->get();
        $programs = Program::get();
        $view = view('admin-transaction-spp');

        return $view->with('user_id',$user_id)->with('teachers',$teachers)->with('students',$students)->with('programs',$programs);
    }

    public function SPPProcess(Request $request){
        //TODO :
        // - mengurangi kuota berdasarkan progress report yg belum dibayar (DONE)
        $quota_remainder = StudentPresence::join('attendances','attendances.id','=','student_presences.id_attendances')->where('id_student',$request->input('student'))->where('spp_paid',0)->where('program',$request->input('program'))->count();
        $tuition =  TuitionFee::where('id_student', $request->input('student'))->where('program',$request->input('program'))->get()->first();
        if (is_null($tuition)){
            //belum ada tuition fee terbayar
            $tuition = new TuitionFee;
            $tuition->id_student = $request->input('student');
            $tuition->program = $request->input('program');
            $tuition->quota = $request->input('quota')-$quota_remainder < 0 ? 0 : $request->input('quota')-$quota_remainder;
            if ($tuition->save()){
                Session::flash('sukses','Data successfully recorded.');
            }
            else{
                Session::flash('gagal','Error has occured. Failed to record data.');
            }
        }
        else{
            //sudah ada fee terdaftar
            $tuition->quota = $tuition->quota + $request->input('quota') - $quota_remainder < 0? 0 : $tuition->quota + $request->input('quota') - $quota_remainder;
            if ($tuition->save()){
                Session::flash('sukses','Data successfully recorded.');
            }
            else{
                Session::flash('gagal','Error has occured. Failed to record data.');
            }
        }

        //Saving it to accounting
        $accounting = new Accounting;
        $accounting->date = $request->input('date');
        $accounting->transaction_type = "SPP";
        $accounting->sub_transaction = $request->input('program')." Program";
        $accounting->detail = Students::select('name')->where('id',$request->input('student'))->get()->first()->name;
        $accounting->nominal = $request->input('nominal');
        $accounting->pic = $request->input('pic');
        $accounting->payment_method = $request->input('payment_method');
        $accounting->notes = $request->input('notes');
        $accounting->approved = false;
        $accounting->save();
    }

    public function ReferralReport($month, $year){
        $years = Referral::selectRaw('YEAR(date) as year')->distinct()->get();
        $referrals = Referral::whereMonth('date','=',$month)->whereYear('date','=',$year)->join('students','students.id','=','referrals.registering_student_id');
        $view = view('admin-referrals-report');
        return $view->with('referrals',$referrals)->with('selected_year',$year)->with('selected_month',$month)->with('years',$years);
    }

    public function AddTeacherMenu(){
        $program = Program::select('program')->get();
        $view = view('admin-new-teacher');
        return $view->with('program',$program);
    }

    public function AddTeacherProcess(Request $request){
        $teach = new Teachers;
        $teach->official_id = $request->input("official-id");
        $teach->name = $request->input("name");
        $teach->nickname = $request->input("nickname");
        $teach->birthplace = $request->input("birthplace");
        $teach->birthdate = $request->input("date");
        $teach->address = $request->input("address");
        $teach->phone_contact = $request->input("telp");
        $teach->emergency_contact = $request->input("telp-emergency");
        $teach->nik = $request->input("nik");
        $teach->institution_name = $request->input("institution");
        $teach->join_date = $request->input("join-date");
        if (count($request->input("teaching-program")) > 0){
            $teach->is_teacher = true;
        }
        else{
            $teach->is_teacher = false;
        }
        $teach->status = true;
        $teach->photo = "default-profile-img.png";
        $teach->save();

        //teach methods
        foreach($request->input("teaching-method") as $tm){
            $teach_method = new TeachMethod;
            $teach_method->id_teacher = $teach->id;
            $teach_method->method = $tm;
            $teach_method->save();
        }
        //teach positions
        $teach_pos = new TeachPosition;
        $teach_pos->id_teacher = $teach->id;
        $teach_pos->position = $request->input("teacher-status");
        $teach_pos->save();

        //teach programs
        foreach($request->input("teaching-program") as $tp){
            $teach_program = new TeachProgram;
            $teach_program->id_teacher = $teach->id;
            $teach_program->program_name = $tp;
            $teach_program->save();
        }

        //users
        $user = new User;
        $user->name = $teach->nickname;
        $user->email = $request->input("email");
        $user->password = Hash::make(strtolower(str_replace(' ','',$teach->name)));
        $user->user_type = $request->input("user-type");
        $user->id_teacher = $teach->id;
        $user->photo = $teach->photo;
        $user->save();
        return redirect('/users/add');
    }

}
