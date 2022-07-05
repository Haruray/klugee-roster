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
use App\SalaryList;
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
        $referrals_approvals = Referral::select('referrals.*','students.name as registering_student_name')
        ->where([
            ['referral_nominal','>',0],
            ['pic_front_admin','>',0]
        ])
        ->where('status_referral','=','0')
        ->orWhere('status_front_admin','=','0')
        ->join('students','students.id','=','referrals.registering_student_id')->get();
        $salary_approvals = Salary::select('salaries.*','teachers.name')->where('approved','=','0')->join('teachers','teachers.id','=','salaries.id_teacher')->get();
        $incentives = Incentive::select('teachers.name as teacher_name','incentives.*')->where('approved','=','0')
        ->join('teachers','teachers.id','=','incentives.id_teacher')->get();
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
        //Saving it in accounting
        $payment = $acc;
        $attendance = Attendance::where('id',$payment->id_attendance)->first();
        $teacher_name = Teachers::where('id',$attendance->id_teacher)->first()->name;
        //main fee
        $payment_accounting = new Accounting;
        $payment_accounting->date = $attendance->date;
        $payment_accounting->transaction_type = "Teacher's Fee";
        $payment_accounting->sub_transaction = $teacher_name."'s Fee";
        $payment_accounting->detail = "Main fee";
        $payment_accounting->nominal = $payment->fee_nominal*-1;
        $payment_accounting->pic = 1;
        $payment_accounting->payment_method = "Other";
        $payment_accounting->notes = "This payment is automated";
        $payment_accounting->approved = true;
        $payment_accounting->save();
        //incentives : lunch
        if ($payment->lunch_nominal >0){
            $payment_accounting = new Accounting;
            $payment_accounting->date = $attendance->date;
            $payment_accounting->transaction_type = "Teacher's Fee";
            $payment_accounting->sub_transaction = $teacher_name."'s Lunch Incentives";
            $payment_accounting->detail = "Lunch Incentives";
            $payment_accounting->nominal = $payment->lunch_nominal*-1;
            $payment_accounting->pic = 1;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
        }
        //incentives : transport
        if ($payment->transport_nominal >0){
            $payment_accounting = new Accounting;
            $payment_accounting->date = $attendance->date;
            $payment_accounting->transaction_type = "Teacher's Fee";
            $payment_accounting->sub_transaction = $teacher_name."'s Transport Incentives";
            $payment_accounting->detail = "Transport Incentives";
            $payment_accounting->nominal = $payment->transport_nominal*-1;
            $payment_accounting->pic = 1;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
        }
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
        $salary = Salary::where('salaries.id',$accounting_id)
        ->join('teachers','teachers.id','=','salaries.id_teacher')
        ->first();
        $payment_accounting = new Accounting;
        $payment_accounting->date = $salary->date;
        $payment_accounting->transaction_type = "Teacher's Salary";
        $payment_accounting->sub_transaction = $salary->name."'s Salary";
        $payment_accounting->detail = "Main salary for ".$salary->date.' '.$salary->date;
        $payment_accounting->nominal = $salary->nominal*-1;
        $payment_accounting->pic = 1;
        $payment_accounting->payment_method = "Other";
        $payment_accounting->notes = "This payment is automated";
        $payment_accounting->approved = true;
        $payment_accounting->save();

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

        $incentive = Incentive::where('incentives.id',$accounting_id)
        ->join('teachers','teachers.id','=','incentives.id_teacher')
        ->first();
        $payment_accounting = new Accounting;
        $payment_accounting->date = $incentive->date;
        $payment_accounting->transaction_type = "Teacher's Salary";
        $payment_accounting->sub_transaction = $incentive->name."'s Salary";
        $payment_accounting->detail = "Main salary for ".date('F',$incentive->date).' '.date('Y',$incentive->date);
        $payment_accounting->nominal = $incentive->nominal*-1;
        $payment_accounting->pic = 1;
        $payment_accounting->payment_method = "Other";
        $payment_accounting->notes = "This payment is automated";
        $payment_accounting->approved = true;
        $payment_accounting->save();

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
        //Accounting
        if ($acc->status_referral==true && $acc->status_front_admin==1 || ($acc->pic_front_admin==0)){
            $acc = Referral::where('referrals.id',$accounting_id)->join('students','students.id','=','referrals.registering_student_id')->first();
            //kalau 2 2 nya disetujui, masukkan ke accounting
            //Parent's referral
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "Parent Referral Bonus";
            $payment_accounting->sub_transaction = $acc->referrer_name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
            //PIC front admin bonus
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "PIC Front Admin Referral Bonus";
            $payment_accounting->sub_transaction = User::where('user_type','admin')->first()->name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();

            //Tambahin Incentivenya ke admin
            $incentive = new Incentive;
            $incentive->name = "PIC Front Admin Referral Bonus";
            $incentive->date = $acc->date;
            $incentive->id_teacher = User::where('user_type','admin')->first()->id_teacher;
            $incentive->nominal = $acc->referral_nominal;
            $incentive->note = $acc->note;
            $incentive->approved = true;
            $incentive->save();
        }
        return redirect('/accounting/approvals');
    }

    public function ReferralDeletion($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->referral_nominal = 0;
        if ($acc->referral_nominal == 0 && $acc->pic_front_admin == 0){
            $acc->delete();
        }
        elseif($acc->pic_front_admin==0 && !$acc->status_front_admin){
            $acc->delete();
        }
        elseif($acc->status_front_admin == 1){
            //ini berarti sudah disetujui/direview semua
            $acc = Referral::where('referrals.id',$accounting_id)->join('students','students.id','=','referrals.registering_student_id')->first();

            //Parent's referral
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "Parent Referral Bonus";
            $payment_accounting->sub_transaction = $acc->referrer_name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
            //PIC front admin bonus
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "PIC Front Admin Referral Bonus";
            $payment_accounting->sub_transaction = User::where('user_type','admin')->first()->name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();

            //Tambahin Incentivenya ke admin
            $incentive = new Incentive;
            $incentive->name = "PIC Front Admin Referral Bonus";
            $incentive->date = $acc->date;
            $incentive->id_teacher = User::where('user_type','admin')->first()->id_teacher;
            $incentive->nominal = $acc->referral_nominal;
            $incentive->note = $acc->note;
            $incentive->approved = true;
            $incentive->save();
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
        if ($acc->status_referral==true && $acc->status_front_admin==1 || ($acc->referral_nominal==0)){
            $acc = Referral::where('referrals.id',$accounting_id)->join('students','students.id','=','referrals.registering_student_id')->first();
            //kalau 2 2 nya disetujui, masukkan ke accounting
            //Parent's referral
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "Parent Referral Bonus";
            $payment_accounting->sub_transaction = $acc->referrer_name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
            //PIC front admin bonus
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "PIC Front Admin Referral Bonus";
            $payment_accounting->sub_transaction = User::where('user_type','admin')->first()->name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
        }
        return redirect('/accounting/approvals');
    }

    public function ReferralFrontDeletion($accounting_id){
        $acc = Referral::where('id',$accounting_id)->first();
        $acc->pic_front_admin = 0;
        if ($acc->referral_nominal == 0 && $acc->pic_front_admin == 0){
            $acc->delete();
        }
        elseif($acc->referral_nominal==0 && !$acc->status_referral){
            $acc->delete();
        }
        elseif($acc->status_referral == 1){
            //ini berarti sudah disetujui/direview semua
            $acc = Referral::where('referrals.id',$accounting_id)->join('students','students.id','=','referrals.registering_student_id')->first();

            //Parent's referral
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "Parent Referral Bonus";
            $payment_accounting->sub_transaction = $acc->referrer_name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();
            //PIC front admin bonus
            $payment_accounting = new Accounting;
            $payment_accounting->date = $acc->date;
            $payment_accounting->transaction_type = "PIC Front Admin Referral Bonus";
            $payment_accounting->sub_transaction = User::where('user_type','admin')->first()->name."'s Referral Bonus";
            $payment_accounting->detail = "Referral for ".$acc->name."'s Registration";
            $payment_accounting->nominal = $acc->referral_nominal*-1;
            $payment_accounting->pic = User::where('user_type','admin')->first()->id_teacher;
            $payment_accounting->payment_method = "Other";
            $payment_accounting->notes = "This payment is automated";
            $payment_accounting->approved = true;
            $payment_accounting->save();

            //Tambahin Incentivenya ke admin
            $incentive = new Incentive;
            $incentive->name = "PIC Front Admin Referral Bonus";
            $incentive->date = $acc->date;
            $incentive->id_teacher = User::where('user_type','admin')->first()->id_teacher;
            $incentive->nominal = $acc->referral_nominal;
            $incentive->note = $acc->note;
            $incentive->approved = true;
            $incentive->save();
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

    public function SalaryEdit(Request $request){
        $salary = SalaryList::where('id', $request->input('salary-id'))->first();
        $salary->nominal = $request->input('salary-input');
        $salary->save();
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
        //Check official id uniqueness
        if (Teachers::where('official_id', $request->input('official-id'))->count() >0 ){
            Session::flash('gagal','Official ID is not unique.');
            return redirect()->back();
        }
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
        Session::flash('sukses','New user registered! Email : '.$user->email.' , Password : '.strtolower(str_replace(' ','',$teach->name)));
        return redirect()->back();
    }

    public function TeacherPayment(){
        $view = view('admin-teacher-payment');
        return $view;
    }

    public function TeacherSalary(){
        $teachers = Teachers::get();
        $view = view('admin-teacher-salary');
        return $view->with('teachers',$teachers);
    }

    public function TeacherSalaryProcess(Request $request){
        if ($request->input('teacher-name')!='all'){
            $teacher_data = Teachers::where('teachers.id',$request->input('teacher-name'))
            ->join('teach_positions','teachers.id','=','teach_positions.id_teacher')
            ->join('users','users.id_teacher','=','teachers.id')
            ->first();
            $salary = new Salary;
            $salary->date = $request->input('date');
            $salary->id_teacher = $request->input('teacher-name');
            $salary->nominal = SalaryList::where('position', $teacher_data->user_type)
            ->where('status',$teacher_data->position)->first()->nominal;
            if ($request->input('note')){$salary->note = $request->input('note');}
            else{
                $salary->note = $teacher_data->user_type.' '.$teacher_data->position.' salary.';
            }

            $salary->approved = false;
            $salary->save();

            return self::GenerateSalary($request->input('teacher-name'), $request->input('date'),
            $request->input('payment_method'), $request->input('teacher-name'), $request->input('no-kontrak'),
            $request->input('divisi'), $request->input('npsn'), $request->input('NILPNF'));
        }
        else{
            $teachers_data = Teachers::select('teachers.*','teach_positions.position','users.user_type')
            ->join('teach_positions','teachers.id','=','teach_positions.id_teacher')
            ->join('users','users.id_teacher','=','teachers.id')
            ->get();
            foreach($teachers_data as $t){
                $nominal_check = SalaryList::where('position', $t->user_type)
                ->where('status',$t->position)->first();
                $nominal = is_null($nominal_check) ? 0 : $nominal_check->nominal;
                $salary = new Salary;
                $salary->date = $request->input('date');
                $salary->id_teacher = $t->id;
                $salary->nominal = $nominal;
                if ($request->input('note')){$salary->note = $request->input('note');}
                else{
                    $salary->note = ucwords($t->user_type).' '.$t->position.' salary.';
                }

                $salary->approved = false;
                $salary->save();
            }
            Session::flash('sukses','Data successfully recorded.');
            return redirect()->back();
        }
    }

    public function GenerateSalary($id_teacher, $date, $payment, $nk, $div, $npsn, $nilnf){
        $month = date('m',strtotime($date));
        $year = date('Y',strtotime($date));
        //month dalam bentuk angka
        //PDF buat slip gaji
        $fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->get();
        $lunch_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher',$id_teacher)
        ->where('lunch_nominal','>',0)
        ->whereMonth('date','=',$month)->where('approved',true)
        ->whereYear('date','=',$year)->get();
        $transport_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->where('transport_nominal','>',0)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $incentive = Incentive::select('name','note')->selectRaw('SUM(nominal) as total')
        ->where('id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->groupBy('name')->get();
        $salary = Salary::select('date','nominal','note')->where('id_teacher',$id_teacher)
        ->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $teacher = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('teachers.id',$id_teacher)->first();
        $head = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('users.user_type','head of institution')->first();
        view()->share([
            'fee'=> $fee,
            'lunch' => $lunch_fee,
            'transport' => $transport_fee,
            'incentive' => $incentive,
            'salary' =>$salary,
            'teacher' => $teacher,
            'date' => $date,
            'head' => $head,
            'via' => $payment,
            'nk' => $nk,
            'div' => $div,
            'npsn' => $npsn,
            'nilnf' => $nilnf
        ]);
        $pdf = PDF::loadView('salary')->setPaper('b5')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('Salary '.$salary[0]->date.'-'.$teacher->name.'-'.$teacher->position.'.pdf');

    }

    public function GenerateSalaryProfile(Request $request){
        $id_teacher = $request->input('teacher');
        $date = date('Y-m-d');
        $payment = $request->input('payment_method');
        $nk = $request->input('no-kontrak');
        $div = $request->input('divisi');
        $npsn = $request->input('npsn');
        $nilnf = $request->input('NILPNF');
        $month = date('m',strtotime($date));
        $year = date('Y',strtotime($date));
        //month dalam bentuk angka
        //PDF buat slip gaji
        $fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->get();
        $lunch_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher',$id_teacher)
        ->where('lunch_nominal','>',0)
        ->whereMonth('date','=',$month)->where('approved',true)
        ->whereYear('date','=',$year)->get();
        $transport_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->where('transport_nominal','>',0)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $incentive = Incentive::select('name','note')->selectRaw('SUM(nominal) as total')
        ->where('id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->groupBy('name')->get();
        $salary = Salary::select('date','nominal','note')->where('id_teacher',$id_teacher)
        ->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $teacher = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('teachers.id',$id_teacher)->first();
        $head = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('users.user_type','head of institution')->first();
        view()->share([
            'fee'=> $fee,
            'lunch' => $lunch_fee,
            'transport' => $transport_fee,
            'incentive' => $incentive,
            'salary' =>$salary,
            'teacher' => $teacher,
            'date' => $date,
            'head' => $head,
            'via' => $payment,
            'nk' => $nk,
            'div' => $div,
            'npsn' => $npsn,
            'nilnf' => $nilnf
        ]);
        $pdf = PDF::loadView('salary')->setPaper('b5')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        return $pdf->download('Salary '.$date.'-'.$teacher->name.'-'.$teacher->position.'.pdf');

    }


    public function TesSalary(){
        $id_teacher = 1;
        $date = date('Y-m-d');
        $month = 7;
        $year = 2022;
        //month dalam bentuk angka
        //PDF buat slip gaji
        $fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->get();
        $lunch_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher',$id_teacher)
        ->where('lunch_nominal','>',0)
        ->whereMonth('date','=',$month)->where('approved',true)
        ->whereYear('date','=',$year)->get();
        $transport_fee = Fee::join('attendances','attendances.id','=','fees.id_attendance')
        ->where('attendances.id_teacher', $id_teacher)
        ->where('transport_nominal','>',0)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $incentive = Incentive::select('name','note')->selectRaw('SUM(nominal) as total')
        ->where('id_teacher', $id_teacher)
        ->whereMonth('date','=', $month)
        ->whereYear('date','=',$year)
        ->where('approved',true)->groupBy('name')->get();
        $salary = Salary::select('date','nominal','note')->where('id_teacher',$id_teacher)
        ->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)->where('approved',true)->get();
        $teacher = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('teachers.id',$id_teacher)->first();
        $head = Teachers::select('teachers.*','users.user_type','teach_positions.position')
        ->join('teach_positions','teach_positions.id_teacher','=','teachers.id')
        ->join('users','users.id_teacher','=','teachers.id')
        ->where('users.user_type','head of institution')->first();
        // view()->share([
        //     'fee'=> $fee,
        //     'lunch' => $lunch_fee,
        //     'transport' => $transport_fee,
        //     'incentive' => $incentive,
        //     'salary' =>$salary,
        //     'teacher' => $teacher,
        //     'date' => $date,
        //     'head' => $head,
        //     'via' => 'ATM'
        // ]);
        // $pdf = PDF::loadView('salary')->setPaper('b5')->setOrientation('landscape')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
        // return $pdf->download('Salary '.$salary[0]->date.'-'.$teacher->name.'-'.$teacher->position.'.pdf');

        $view = view('salary');
        return $view->with([
            'fee'=> $fee,
            'lunch' => $lunch_fee,
            'transport' => $transport_fee,
            'incentive' => $incentive,
            'salary' =>$salary,
            'teacher' => $teacher,
            'date' => $date,
            'head' => $head,
            'via' => 'ATM'
        ]);
    }

    public function TeacherIncentive(){
        $teachers = Teachers::get();
        $view = view('admin-teacher-incentive');
        return $view->with('teachers',$teachers);
    }

    public function TeacherIncentiveProcess(Request $request){
        if ($request->input('teacher-name')!='teacher' && $request->input('teacher-name')!='head teacher' &&
        $request->input('teacher-name')!='admin' && $request->input('teacher-name')!='super admin'){
            $incentive = new Incentive;
            $incentive->name = $request->input('name');
            $incentive->nominal = $request->input('nominal');
            $incentive->date = $request->input('date');
            $incentive->id_teacher = $request->input('teacher-name');
            $incentive->note = $request->input('note');
            $incentive->approved = false;

            if ($incentive->save()){
                Session::flash('sukses','Data successfully recorded.');
            }
            else{
                Session::flash('gagal','Error has occured. Failed to record data.');
            }
        }
        else{
            $teachers_data = Teachers::select('teachers.*','users.user_type')
            ->join('users','users.id_teacher','=','teachers.id')
            ->where('users.user_type', $request->input('teacher-name'))
            ->get();
            foreach($teachers_data as $t){
                $incentive = new Incentive;
                $incentive->name = $request->input('name');
                $incentive->nominal = $request->input('nominal');
                $incentive->date = $request->input('date');
                $incentive->id_teacher = $t->id;
                $incentive->note = $request->input('note');
                $incentive->approved = false;
                if ($incentive->save()){
                    Session::flash('sukses','Data successfully recorded.');
                }
                else{
                    Session::flash('gagal','Error has occured. Failed to record data.');
                }
            }
        }

        return redirect('/accounting/teacher-payment/incentive');
    }

    public function PartnerAdd(Request $request){
        $referrer = new Referrer;
        $referrer->referrer_name = Students::where('id',$request->input('parent'))->first()->parent_name;
        $referrer->parent_student_id = $request->input('parent');
        $referrer->save();
        return redirect()->back();
    }

    public function PartnerDelete($partner_id){
        $referrer = Referrer::where('id',$partner_id)->delete();
        return redirect()->back();
    }


}
