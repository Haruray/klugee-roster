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
        $referrals = Referral::whereMonth('date','=',$month)->whereYear('date','=',$year)->join('students','students.id','=','referrals.registering_student_id');
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
}
