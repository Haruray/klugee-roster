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
        $income_approvals = Accounting::select('accountings.*','teachers.name')->where('nominal','>','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $expense_approvals = Accounting::select('accountings.*','teachers.name')->where('nominal','<','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $fees_approvals = Fee::select('fees.id as id_fee','fees.fee_nominal','fees.lunch_nominal','fees.transport_nominal','fees.note','fees.approved','fees.id_attendance','attendances.date','teachers.name')->where('approved','=','0')->join('attendances','attendances.id','=','fees.id_attendance')->join('teachers','teachers.id','=','attendances.id_teacher')->get();
        $referrals_approvals = Referral::select('referrals.*','students.name as registering_student_name')->where('status_referral','=','0')->orWhere('status_front_admin','=','0')->orWhere('status_scheduling')->join('students','students.id','=','referrals.registering_student_id')->get();
        $salary_approvals = Salary::select('salaries.*','teachers.name')->where('approved','=','0')->join('teachers','teachers.id','=','salaries.id_teacher')->get();
        $incentives = Incentive::where('approved','=','0')->get();
        //return $fees_approvals;
        return $view->with('income',$income_approvals)->with('expense',$expense_approvals)->with('fee',$fees_approvals)->with('salary',$salary_approvals)->with('incentive',$incentives)->with('referrals',$referrals_approvals);
    }

    public function AccountingApproval($accounting_id){
        $acc = Accounting::where('id',$accounting_id)->first();
        $acc->approved = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function AccountingDeletion($accounting_id){
        $acc = Accounting::where('id',$accounting_id)->delete();
        return redirect('/accounting/approvals');
    }

    public function FeeApproval($accounting_id){
        $acc = Fee::where('id',$accounting_id)->first();
        $acc->approved = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function FeeDeletion($accounting_id){
        $acc = Fee::where('id',$accounting_id)->delete();
        return redirect('/accounting/approvals');
    }

    public function SalaryApproval($accounting_id){
        $acc = Salary::where('id',$accounting_id)->first();
        $acc->approved = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function SalaryDeletion($accounting_id){
        $acc = Salary::where('id',$accounting_id)->delete();
        return redirect('/accounting/approvals');
    }

    public function IncentiveApproval($accounting_id){
        $acc = Incentive::where('id',$accounting_id)->first();
        $acc->approved = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function IncentiveDeletion($accounting_id){
        $acc = Incentive::where('id',$accounting_id)->delete();
        return redirect('/accounting/approvals');
    }

    public function ReferralApproval($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->status_referral = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function ReferralDeletion($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->update('referral_nominal',0);
        if ($acc->referral_nominal == 0 && $acc->pic_front_admin == 0 && $acc->pic_scheduling == 0){
            $acc->delete();
        }
        else{
            $acc->save();
        }
        return redirect('/accounting/approvals');
    }

    public function ReferralFrontApproval($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->status_front_admin = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function ReferralFrontDeletion($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->update('pic_front_admin',0);
        if ($acc->referral_nominal == 0 && $acc->pic_front_admin == 0 && $acc->pic_scheduling == 0){
            $acc->delete();
        }
        else{
            $acc->save();
        }
        return redirect('/accounting/approvals');
    }

    public function ReferralSchedulingApproval($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->status_scheduling = true;
        $acc->save();
        return redirect('/accounting/approvals');
    }

    public function ReferralSchedulingDeletion($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->update('pic_scheduling',0);
        if ($acc->referral_nominal == 0 && $acc->pic_front_admin == 0 && $acc->pic_scheduling == 0){
            $acc->delete();
        }
        else{
            $acc->save();
        }
        return redirect('/accounting/approvals');
    }



}
