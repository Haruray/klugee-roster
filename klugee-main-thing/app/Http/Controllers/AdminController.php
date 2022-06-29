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
use App\Referrer;

use PDF;

use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('thirdlevel');
        //$this->middleware('superadmin');
    }

    public function Accounting(){
        $view = view('admin-accounting');
        return $view;
    }

    public function InputTransaction(){
        $view = view('admin-acccounting-input');
        return $view;
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
            view()->share('data',$new_income);
            $pdf = PDF::loadView('nota', $new_income)->setPaper('b6')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
            return $pdf->download('Nota '.$new_income->date.'-'.$new_income->transaction_type.'-'.$new_income->sub_transaction.'-'.$new_income->detail.'.pdf');
        }
        else{
            Session::flash('gagal','Error has occured. Failed to record data.');
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
            //pdf download
            view()->share('data',$new_income);
            $pdf = PDF::loadView('nota', $new_income)->setPaper('b6')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
            return $pdf->download('Nota '.$new_income->date.'-'.$new_income->transaction_type.'-'.$new_income->sub_transaction.'.pdf');
        }
        else{
            Session::flash('gagal','Error has occured. Failed to record data.');
        }

        return redirect('/accounting/input-transaction/expense');
    }

    public function TesNota(){
        $new_income = Accounting::first();
        //return view('nota')->with('data',$new_income);
        view()->share('data',$new_income);
        $pdf = PDF::loadView('nota', $new_income)->setPaper('b6')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('nota.pdf');
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

        $income = Accounting::select('accountings.*','teachers.name')
        ->join('teachers','accountings.pic','=','teachers.id')
        ->where('nominal','>','0')->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)
        ->orderBy('date','DESC')
        ->get();

        $view = view('admin-monthly-recap-income');
        return $view->with('years',$years)->with('income',$income)->with('selected_year',$year)->with('selected_month',$month);
    }

    public function FinancialRecapExpense($month, $year){
        $years = Accounting::selectRaw('YEAR(date) as year')->distinct()->get();

        $income = Accounting::select('accountings.*','teachers.name')
        ->join('teachers','accountings.pic','=','teachers.id')
        ->where('nominal','<','0')->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)
        ->orderBy('date','DESC')
        ->get();

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
        // - update student presence status paid nya ketika di update + kasih notif juga
        $quota_remainder = StudentPresence::join('attendances','attendances.id','=','student_presences.id_attendance')->where('id_student',$request->input('student'))->where('spp_paid',0)->where('program',$request->input('program'))->count();
        $tuition =  TuitionFee::where('id_student', $request->input('student'))->where('program',$request->input('program'))->get()->first();
        if (is_null($tuition)){
            //belum ada tuition fee terbayar
            //buat record baru
            $tuition = new TuitionFee;
            $tuition->id_student = $request->input('student');
            $tuition->program = $request->input('program');
            $tuition->quota = ($request->input('quota')-$quota_remainder) <= 0 ? 0 : $request->input('quota')-$quota_remainder;
            //tambahkan record baru untuk student_programs
            $student_program =new StudentProgram;
            $student_program->id_student = $request->input('student');
            $student_program->program = $request->input('program');
            if ($tuition->save()){
                $student_program->save();
                Session::flash('sukses','Data successfully recorded.');
            }
            else{
                Session::flash('gagal','Error has occured. Failed to record data.');
            }
        }
        else{
            //sudah ada fee terdaftar
            $tuition->quota = ($tuition->quota + $request->input('quota') - $quota_remainder) <= 0? 0 : $tuition->quota + $request->input('quota') - $quota_remainder;
            //Update spp paid di student presence
            if ($tuition->save()){
                Session::flash('sukses','Data successfully recorded.');
            }
            else{
                Session::flash('gagal','Error has occured. Failed to record data.');
            }
        }

        //updating the student presences
        $student_presence_spp_update = StudentPresence::join('attendances','attendances.id','=','student_presences.id_attendance')
        ->where('id_student',$request->input('student'))
        ->where('spp_paid',false)
        ->where('program',$request->input('program'))->orderBy('attendances.date', 'ASC')
        ->limit($request->input('quota') > $quota_remainder ? $quota_remainder : $request->input('quota'))
        ->update([
            'spp_paid' => true
        ]);

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
        view()->share('data',$accounting);
        $pdf = PDF::loadView('nota', $accounting)->setPaper('b6')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('Nota '.$accounting->date.'-'.$accounting->transaction_type.'-'.$accounting->sub_transaction.'.pdf');
    }

    public function ReferralReport($month, $year){
        $years = Referral::selectRaw('YEAR(date) as year')->distinct()->get();
        $referrals = Referral::whereMonth('date','=',$month)->whereYear('date','=',$year)->join('students','students.id','=','referrals.registering_student_id')->get();
        $view = view('admin-referrals-report');
        return $view->with('referrals',$referrals)->with('selected_year',$year)->with('selected_month',$month)->with('years',$years);
    }

    public function Report(){
        // $view = view('reportbook');
        // return $view;
        //view()->share('data',$new_income);
        $pdf = PDF::loadView('reportbook')->setOption('page-width','210')->setOption('page-height','297')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('Tes.pdf');
    }

    public function NewStudent(){
        $program = Program::get();
        $teachers = Teachers::get();
        $view = view('admin-new-student');
        return $view->with([
            'program' => $program,
            'teachers' => $teachers,
        ]);
    }

    public function NewStudentProcess(Request $request){
        $acc_ids = array();
        $student = new Students;
        $student->official_id = $request->input('official-id');
        $student->name = $request->input('name');
        $student->nickname = $request->input('nickname');
        $student->birthplace = $request->input('birthplace');
        $student->birthdate = $request->input('date');
        $student->school_name = $request->input('school');
        $student->parent = $request->input('parent');
        $student->parent_name = $request->input('parent-name');
        $student->parent_contact = $request->input('telp');
        $student->address = $request->input('address');
        $student->email = $request->input('email');
        $student->photo = "default-profile-img.png";
        $student->save();
        foreach($request->input('student-program') as $sp){
            $student_program = new StudentProgram;
            $student_program->id_student = $student->id;
            $student_program->program = $sp;
            $student_program->save();
            //SPP Stuff
            $tuition = new TuitionFee;
            $tuition->id_student = $student->id;
            $tuition->program = $sp;
            $tuition->quota =$request->input(explode(' ',$sp)[0].'-jatah');
            $tuition->save();
            //Accounting Income SPP
            $accounting = new Accounting;
            $accounting->date = date("Y-m-d");
            $accounting->transaction_type = "SPP";
            $accounting->sub_transaction = $sp." Program";
            $accounting->detail = $student->name;
            $accounting->nominal = $request->input(explode(' ',$sp)[0].'-nominal');
            $accounting->pic = $request->input('pic');
            $accounting->payment_method = $request->input('payment_method');
            $accounting->notes = $request->input('note');
            $accounting->approved = false;
            $accounting->save();
            array_push($acc_ids,$accounting->id);
        }
        //Accounting Income Registration
        $accounting = new Accounting;
        $accounting->date = date("Y-m-d");
        $accounting->transaction_type = "Registration";
        $accounting->sub_transaction = $sp." Program";
        $accounting->detail = $student->name;
        $accounting->nominal = $request->input('registration-nominal');
        $accounting->pic = $request->input('pic');
        $accounting->payment_method = $request->input('payment_method');
        $accounting->notes = $request->input('note');
        $accounting->approved = false;
        $accounting->save();
        array_push($acc_ids,$accounting->id);

        //Referrals
        if ($request->input('referral-bool')=='1'){
            $referral = new Referral;
            $referral->date = date("Y-m-d");
            $referral->registering_student_id = $student->id;
            $referral->referrer_parent_student_id = $request->input('referrer');
            $referral->referrer_name = Referrer::where('parent_student_id',$request->input('referrer'))->first()->referrer_name;
            $referral->referral_nominal = IncentiveList::where('name','Referral')->first()->nominal;
            $referral->status_referral = false;
            $referral->pic_front_admin = IncentiveList::where('name','PIC Referral')->first()->nominal;
            $referral->status_front_admin = false;
            $referral->note = $request->input('referral-note');
            $referral->save();
            //Diskon referral SPP
            $accounting = new Accounting;
            $accounting->date = date("Y-m-d");
            $accounting->transaction_type = "Diskon Referral";
            $accounting->sub_transaction = Referrer::where('parent_student_id',$request->input('referrer'))->first()->referrer_name;
            $accounting->detail = 'Pendaftaran Murid '.$student->name;
            $accounting->nominal = -20000;
            $accounting->pic = $request->input('pic');
            $accounting->payment_method = $request->input('payment_method');
            $accounting->notes = $request->input('note');
            $accounting->approved = false;
            $accounting->save();
            array_push($acc_ids,$accounting->id);
            //Diskon referral Registration
            $accounting = new Accounting;
            $accounting->date = date("Y-m-d");
            $accounting->transaction_type = "Diskon Pendaftaran Referral";
            $accounting->sub_transaction = Referrer::where('parent_student_id',$request->input('referrer'))->first()->referrer_name;
            $accounting->detail = 'Pendaftaran Murid '.$student->name;
            $accounting->nominal = -50000;
            $accounting->pic = $request->input('pic');
            $accounting->payment_method = $request->input('payment_method');
            $accounting->notes = $request->input('note');
            $accounting->approved = false;
            $accounting->save();
            array_push($acc_ids,$accounting->id);
        }
        return self::GenerateNota($acc_ids,'Pendaftaran Murid');
        return redirect('/students/'.$student->id);

    }

    public function GenerateNota($id_accounting, $title){
        $ids = array();
        if (!is_array($id_accounting)){
            array_push($ids,$id_accounting);
        }
        else{
            $ids = array_merge($ids, $id_accounting);
        }
        $accounting =  Accounting::whereIn('id',$ids)->get();
        $admin = User::join('teachers','teachers.id','=','users.id_teacher')->where('users.user_type','admin')->first();
        view()->share([
            'data'=>$accounting,
            'admin' => $admin,
            'title' => $title,
        ]);
        $pdf = PDF::loadView('nota', $accounting)->setPaper('b6')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('Nota '.$accounting[0]->date.'-'.$accounting[0]->transaction_type.'-'.$accounting[0]->sub_transaction.'.pdf');

    }
}
