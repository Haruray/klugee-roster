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

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('superadmin');
        $this->middleware('fourthlevel');
    }

    public function Approvals(){
        $view = view('admin-approvals');
        $income_approvals = Accounting::select('accountings.*','teachers.name')->where('nominal','>','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $expense_approvals = Accounting::select('accountings.*','teachers.name')->where('nominal','<','0')->where('approved','=','0')->join('teachers','teachers.id','=','pic')->get();
        $fees_approvals = Fee::select('fees.id as id_fee','fees.fee_nominal','fees.lunch_nominal','fees.transport_nominal','fees.note','fees.approved','fees.id_attendance','attendances.date','teachers.name')->where('approved','=','0')->join('attendances','attendances.id','=','fees.id_attendance')
        ->join('teachers','teachers.id','=','attendances.id_teacher')
        ->orderBy('attendances.date')
        ->get();
        $referrals_approvals = Referral::select('referrals.*','students.name as registering_student_name')->where('status_referral','=','0')->orWhere('status_front_admin','=','0')->orWhere('status_scheduling')->join('students','students.id','=','referrals.registering_student_id')->get();
        $salary_approvals = Salary::select('salaries.*','teachers.name')->where('approved','=','0')->join('teachers','teachers.id','=','salaries.id_teacher')->get();
        $incentives = Incentive::where('approved','=','0')->get();
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

    public function FeeEdit(Request $request){
        $fee = FeeList::where('id', $request->input('fee-id'))->first();
        $fee->nominal_exclusive = $request->input('exclusive');
        $fee->nominal_semiprivate = $request->input('semiprivate');
        $fee->nominal_school = $request->input('school');
        $fee->save();
        return redirect()->back();
    }

    public function IncentiveEdit(Request $request){
        $incentive = IncentiveList::where('id', $request->input('incentive-id'))->first();
        $incentive->nominal = $request->input('incentive-input');
        $incentive->save();
        return redirect()->back();
    }

    public function FeeAdd(Request $request){
        $fee = new FeeList;
        $fee->program = $request->input('fee-program');
        $fee->level = $request->input('level-add');
        $fee->nominal_exclusive = $request->input('exclusive-add');
        $fee->nominal_semiprivate = $request->input('semiprivate-add');
        $fee->nominal_school = $request->input('school-add');
        $fee->save();
        return redirect()->back();
    }

    public function ProgramAdd(Request $request){
        $program = new Program;
        $program->program = $request->input('program-add');
        $program->save();
        $fee = new FeeList;
        $fee->program = $request->input('program-add');
        $fee->level = 1;
        $fee->nominal_exclusive = 0;
        $fee->nominal_semiprivate = 0;
        $fee->nominal_school = 0;
        $fee->save();
        return redirect()->back();
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
        return redirect('/users/'.$teach->id);
    }

}
