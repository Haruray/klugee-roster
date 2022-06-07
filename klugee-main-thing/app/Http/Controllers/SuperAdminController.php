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
use App\Referral;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('superadmin');
        $this->middleware('admin');
    }

    public function Approvals(){
        $view = view('admin-approvals');
        $income_approvals = Accounting::where('nominal','>','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $expense_approvals = Accounting::where('nominal','<','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $fees_approvals = Fee::where('approved','=','0')->join('attendances','attendances.id','=','fees.id_attendance')->join('teachers','teachers.id','=','attendances.id_teacher')->get();
        $referrals_approvals = Referral::where('status_referral','=','0')->orWhere('status_front_admin','=','0')->orWhere('status_scheduling')->join('students','students.id','=','referrals.registering_student_id')->get();
        $salary_approvals = Salary::where('approved','=','0')->join('teachers','teachers.id','=','salaries.id_teacher')->get();
        $incentives = Incentive::where('approved','=','0')->get();
        return $view->with('income',$income_approvals)->with('expense',$expense_approvals)->with('fee',$fees_approvals)->with('salary',$salary_approvals)->with('incentive',$incentives)->with('referrals',$referrals_approvals);
    }

    public function AccountingApproval($accounting_id){
        //
    }

    public function AccountingDeletion($accounting_id){
        //
    }
}
