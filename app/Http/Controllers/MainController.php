<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

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
use App\Accounting;
use App\SalaryList;
use App\User;
use App\Referrer;

use Session;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firstlevel');
    }

    public function index()
    {
        $view = view('index');
        return $view;
    }

    public function AttendanceMenu(){
        $view = view('attendance');
        return view('attendance');
    }

    public function AttendanceInput(){
        $view = view('attendance-input');
        $students = Students::get();
        $programs = Program::get();
        return $view->with('students',$students)->with('programs',$programs)->with('another_teacher',false);
    }

    public function AttendanceInputProcess(Request $request){
        $max_stud = 10; //max students for input
        $should_pay_teach_fee = false;
        $user_id_attendance = auth()->user()->id_teacher;
        //If its another teacher, then add this :
        if (!is_null($request->input('attendance-teacher-id'))){
            $user_id_attendance = $request->input('attendance-teacher-id');
        }
        //Save attendance data
        $new_attendance = new Attendance;
        $new_attendance->id_teacher =$user_id_attendance;
        $new_attendance->date = $request->input('date');
        $new_attendance->time = $request->input('time');
        $new_attendance->program = $request->input('program');
        $new_attendance->location = $request->input('location');
        $new_attendance->class_type = $request->input('class-type');

        if (!$new_attendance->save()){
            //If it doesn't success, then return error message
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ], 401);
        }


        for ($i = 1 ; $i < $max_stud ; $i++){
            //Search for every student input form
            $is_there_any_student = true;
            $string_search = "student".$i;
            $string_search_2 = "student-attend-".$i;

            if ($request->input($string_search) != NULL){
                $is_there_any_student = true;
                //check if attendee a duplicate or not
                $attendee_check = Attendee::where([
                    ['id_attendance',$new_attendance->id],
                    ['id_student',Students::where('name',$request->input($string_search))->first()['id']]
                ])->first();
                if ($attendee_check!=NULL){
                    continue;
                }
                //Check if attendee is registered to input-ed program
                $student_program_check = Students::join('student_programs','student_programs.id_student','=','students.id')->where([
                    ['name',$request->input($string_search)],
                    ['program',$request->input('program')]
                    ])->get();
                if (count($student_program_check) <= 0){
                    Session::flash('unregistered-program', $request->input($string_search).' belum terdaftar di program yang dimasukkan.');
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to save data. Student is not registered to a program.'
                    ], 401);
                }
                //Save attendee data
                $new_attendee = new Attendee;
                $new_attendee->id_attendance = $new_attendance->id;
                $new_attendee->id_student = Students::where('name',$request->input($string_search))->first()['id'];
                if ($request->input($string_search_2)=='0'){
                    $new_attendee->alpha = true;
                }
                elseif($request->input($string_search_2)=='1'){
                    $new_attendee->present = true;
                }
                else{
                    $new_attendee->homework = true;
                }
                if (!$new_attendee->save()){
                    //If it doesn't success, then return error message
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to save data. Please reload and re-enter the data.'
                    ], 401);
                }

                $should_pay_teach_fee = true;
                //Create progress report
                $new_progress = new Progress;
                $new_progress->id_teacher = $new_attendance->id_teacher;
                $new_progress->id_student = $new_attendee->id_student;
                $new_progress->id_attendance = $new_attendance->id;
                if ($new_attendee->homework){
                    $new_progress->note = "Homework";
                }
                $new_progress->filled = false;
                $new_progress->save();
                $new_progress->student_alpha = $new_attendee->alpha;
                if (!$new_progress->save()){
                    //If it doesn't success, then return error message
                    return response()->json([
                        'success' => false,
                        'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                    ], 401);
                }

                //Student presences
                //walaupun engga hadir, tetap kepakai
                $student_fee = TuitionFee::where('id_student',$new_attendee->id_student)->where('program',$new_attendance->program)->first();

                $new_student_presence = new StudentPresence;
                $new_student_presence->id_student = $new_attendee->id_student;
                $new_student_presence->id_attendance = $new_attendance->id;
                $new_student_presence->spp_paid = $student_fee->quota > 0 ? true:false;
                $new_student_presence->save();

                //update spp
                $initial_quota = $student_fee->quota;
                if ($initial_quota>0){
                    $student_fee = TuitionFee::where('id_student',$new_attendee->id_student)->where('program',$new_attendance->program)->update([
                        'quota' => $initial_quota-1
                    ]);
                }
            }
            elseif (!$is_there_any_student){
                return response()->json([
                    'success' => false,
                    'message' => 'No student in input data',
                ], 401);
            }
        }

        //Save teacher's attendance
        $new_presence = new TeachPresence;
        $new_presence->id_teacher = $user_id_attendance;
        $new_presence->date = $request->input('date');
        $new_presence->id_attendance = $new_attendance->id;
        $new_presence->save();

        return response()->json([
            'success' => true,
            'attendance_id' => $new_attendance->id,
        ], 200);
    }

    private function CheckAttendeeDuplicate($attendance_id, $name){
        $attendee_data = GetAttendee($attendance_id);

        foreach ($attendee_data as $a){
            if ($a->name == $name){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function AttendanceEdit(Request $request){
        //UNTESTED
        $edited_attendance = Attendance::where('id',$request->input('attendance_id'))->update([
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'program'=> $request->input('program'),
            'location' => $request->input('location'),
            'class_type' => $request->input('class-type')
        ]);
        for ($i = 1 ; $i < $max_stud ; $i++){
            //Search for every student input form
            $string_search = "student".$i;
            $string_search_2 = "student-attend-".$i;
            if ($request->input($string_search) != NULL){
                //check sudah ada di database atau tidak
                //Kalau iya, edit aja
                if (self::CheckAttendeeDuplicate($request->input('attendance_id'), $request->input($string_search))){
                    $student_present_new = Attendee::where('name', $request->input($string_search))->first()->present;
                    $need_new_progress_report = !$student_present_new;
                    if ($request->input($string_search_2)=='no'){
                        $student_present_new = false;
                    }
                    else{
                        $student_present_new = true;
                    }
                    $updated_attendee = Attendee::where('name', $request->input($string_search))->update([
                        'present' => $student_present_new
                    ]);

                    if ($need_new_progress_report && $student_present_new){
                        $new_progress = new Progress;
                        $new_progress->id_teacher = $edited_attendance->id_teacher;
                        $new_progress->id_student = Attendee::where('name', $request->input($string_search))->first()->id;
                        $new_progress->id_attendance = $edited_attendance->id;
                        $new_progress->filled = false;
                        $new_progress->save();
                        if (!$new_progress->save()){
                            //If it doesn't success, then return error message
                            return response()->json([
                                'success' => false,
                                'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                            ], 401);
                        }
                    }

                }
                else{
                    //Save attendee data
                    $new_attendee = new Attendee;
                    $new_attendee->id_attendance = $edited_attendance->id;
                    $new_attendee->id_student = Students::where('name',$request->input($string_search))->first()['id'];
                    if ($request->input($string_search_2)=='no'){
                        $new_attendee->present = false;
                    }
                    else{
                        $new_attendee->present = true;
                    }
                    if (!$new_attendee->save()){
                        //If it doesn't success, then return error message
                        return response()->json([
                            'success' => false,
                            'message' => 'Fail to save data. Please reload and re-enter the data.'
                        ], 401);
                    }
                    //Create progress report
                    $new_progress = new Progress;
                    $new_progress->id_teacher = $edited_attendance->id_teacher;
                    $new_progress->id_student = $new_attendee->id_student;
                    $new_progress->id_attendance = $edited_attendance->id;
                    $new_progress->filled = false;
                    $new_progress->save();
                    if (!$new_progress->save()){
                        //If it doesn't success, then return error message
                        return response()->json([
                            'success' => false,
                            'message' => 'Fail to create progress report. Please contact the developer for this problem.'
                        ], 401);
                    }
                }


            }
        }

        return response()->json([
            'success' => true,
            'attendance_id' => $new_attendance->id,
        ], 200);
    }

    private function GetAttendee($attendance_id){
        $students = array();
        $attendee = Attendee::where('attendees.id_attendance',$attendance_id)
        ->join('students','students.id','attendees.id_student')
        ->get();
        return $attendee;
    }

    private function GetPresentAttendee($attendance_id){
        //get present attendee or with homework
        $students = array();
        $attendee = Attendance::select('students.id','students.name','students.nickname','attendees.alpha','attendees.present','attendees.homework')
        ->where('id_attendance',$attendance_id)
        ->join('attendees','attendances.id','=','attendees.id_attendance')
        ->join('students','students.id','=','attendees.id_student')
        ->whereRaw('(attendees.homework = 1 OR attendees.present = 1) AND attendees.alpha = 0')
        ->distinct()
        ->get();
        return $attendee;
    }

    public function AttendanceProgressReport($attendance_id){
        $view = view('progress-report-input');
        //Get every student on current attendance
        $students = self::GetPresentAttendee($attendance_id);
        $flag; //for checking whether all the students present or not
        $program = Attendance::where('id',$attendance_id)->first()->program;

        $attendee = Attendee::where('attendees.id_attendance',$attendance_id)
        ->join('tuition_fees','tuition_fees.id_student','=','attendees.id_student')
        ->join('students','students.id','=','attendees.id_student')
        ->join('student_presences',function($join){
            $join->on('student_presences.id_student','=','students.id')->on('student_presences.id_attendance','=','attendees.id_attendance');
        })
        ->where('program',$program)->get();
        if (count($attendee) == 0){
            //if there's no attendee, then it's obviously dont show the progress report
            $flag = false;
        }
        else{
            $flag = $attendee[0]->present;
            //Checking attendance
            $spp_warning = array(); //for SPP warning
            foreach ($attendee as $a){
                $flag = $flag || $a->present || $a->homework;
                if ($a->spp_paid == 0 && !$a->alpha){
                    $warning_string = $a->name.' belum membayar SPP '.$a->program.' untuk bulan ini.';
                    array_push($spp_warning,$warning_string);
                }
            }
        }
        if ($flag){ //Proceed if at least one of the attendee is present
            $filled = Progress::where('id_attendance',$attendance_id)->get();
            $indexReference = 0;
            for ($k = 0 ; $k < count($filled) ; $k++){
                if (!is_null($filled[$k]->level)){
                    $indexReference = $k;
                    break;
                }
            }
            if (is_null($filled[$indexReference]->level) || !$filled[$indexReference]->filled){ //if its not filled, the proceed to the form
                //SPP warning stuff
                Session::flash('spp-warning', $spp_warning);
                $levels = FeeList::select('level')->where('program', $program)->get();
                return $view->with('students', $students)->with('attendance_id',$attendance_id)->with('flag',$flag)->with('levels',$levels)->with('program', $program);

            }
            else{ //if its filled, then redirect to progress report confirm view
                return redirect('/attendance/progress-report/'.$attendance_id.'/filled');
            }
        }
        return $view->with('attendance_id',$attendance_id)->with('flag',$flag);


 }
    public function ProgressView($attendance_id){
        $progress = Attendance::
        join('progress','progress.id_attendance','=','attendances.id')
        ->where('attendances.id',$attendance_id)->get();
        $indexReference = 0;
        for ($k = 0 ; $k < count($progress) ; $k++){
            if (!is_null($progress[$k]->level)){
                $indexReference = $k;
                break;
            }
        }
        if (!is_null($progress[$indexReference]->level) && $progress[$indexReference]->filled){
            $data = Attendance::select('attendances.id','attendances.date'
            ,'attendances.time','attendances.program','attendances.location',
            'attendances.class_type'
            ,'progress.level','progress.unit','progress.last_exercise'
            ,'progress.score','students.name','students.nickname','students.id as student_id'
            ,'attendees.alpha','attendees.homework','attendees.present')
            ->join('progress','progress.id_attendance','=','attendances.id')
            ->join('attendees',function($join){
                $join->on('attendees.id_attendance','=','attendances.id')->on('attendees.id_attendance','=','progress.id_attendance');
            })
            ->join('students',function($join){
                $join->on('students.id','=','attendees.id_student')->on('students.id','=','progress.id_student');
            })
            ->where('attendances.id',$attendance_id)
            ->distinct()
            ->get();

            $data_not_null = Attendance::select('attendances.id','attendances.date','attendances.time','attendances.program','attendances.location',
            'attendances.class_type'
            ,'progress.level','progress.unit','progress.last_exercise','progress.score','students.name','students.nickname','students.id as student_id')
            ->join('progress','progress.id_attendance','=','attendances.id')
            ->join('attendees',function($join){
                $join->on('attendees.id_attendance','=','attendances.id')->on('attendees.id_attendance','=','progress.id_attendance');
            })
            ->join('students',function($join){
                $join->on('students.id','=','attendees.id_student')->on('students.id','=','progress.id_student');
            })
            ->where('attendances.id',$attendance_id)
            ->distinct()
            ->whereNotNull('progress.level')
            ->get();

            for ($k = 0 ; $k < count($data) ; $k++){
                if (!is_null($data[$k]->level)){
                    $indexReference = $k;
                    break;
                }
            }
            $levels = FeeList::select('level')->where('program',$data[$indexReference]->program)->get();
            $view = view('progress-report-confirm');
            return $view->with('progress_no_null',$data_not_null)
            ->with('progress',$data)->with('levels',$levels)->with('attendance_id',$attendance_id)->with('level_ouch',$data[$indexReference]->level);
        }
        else{
            return redirect('/attendance/progress-report/'.$attendance_id);
        }
    }

    public function AttendanceView($attendance_id){
        //Get every student on current attendance
        $students = self::GetAttendee($attendance_id);
        $attendance = Attendance::where('id', $attendance_id)->get()->first();
        $view = view('attendance-input-confirm');
        return $view->with('students', $students)->with('attendance',$attendance);
    }

    public function ProgressEdit(Request $request){
        $user_id_attendance = Attendance::where('id', $request->input('attendance_id'))->first()->id_teacher;
        $user_info = Teachers::where('id',$user_id_attendance)->first();
        //get all progress report with $attendance_id
        if ($request->input('level')==null || $request->input('unit')==null || $request->input('last_exercise')==null){
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ],401);
        }
        //Move the file to uploads\progress-reports
        //if there's a documentation image
        if (!is_null($request->file('documentation'))){
            $file = $request->file('documentation');
            $destinationPath = 'uploads/progress-reports';
            $documentation_file_name = $user_info->name."_Progress-report_".$request->input('attendance_id').'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath,$documentation_file_name);
        }
        else{
            $documentation_file_name = null;
        }

        //Update data individually per student
        $students = self::GetAttendee($request->input('attendance_id'));
        foreach ($students as $student){
            if (!$student->alpha){
                $score_id = 'score-'.$student->id;
                $progress_report_update = Progress::where([
                    ['id_attendance', $request->input('attendance_id')],
                    ['id_student', $student->id]
                ])->update([
                    'level' => $request->input('level'),
                    'unit' => $request->input('unit'),
                    'last_exercise' => $request->input('last_exercise'),
                    'score' => $request->input($score_id),
                    'documentation' =>$documentation_file_name,
                    'filled' => true
                ]);
            }
            else{
                $progress_report_update = Progress::where([
                    ['id_attendance', $request->input('attendance_id')],
                    ['id_student', $student->id]
                ])->update([
                    'filled' => true
                ]);
            }
        }

        return redirect('/attendance/progress-report/'.$request->input('attendance_id'));
    }

    public function ProgressReportInputProcess(Request $request){
        $user_id_attendance = Attendance::where('id', $request->input('attendance_id'))->first()->id_teacher;
        $user_info = Teachers::where('id',$user_id_attendance)->first();
        //get all progress report with $attendance_id
        if ($request->input('level')==null || $request->input('unit')==null || $request->input('last_exercise')==null){
            return response()->json([
                'success' => false,
                'message' => 'Fail to save data. Please reload and re-enter the data.'
            ],401);
        }
        //Move the file to uploads\progress-reports
        //if there's a documentation image
        if (!is_null($request->file('documentation'))){
            $file = $request->file('documentation');
            $destinationPath = 'uploads/progress-reports';
            $documentation_file_name = $user_info->name."_Progress-report_".$request->input('attendance_id').'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath,$documentation_file_name);
        }
        else{
            $documentation_file_name = null;
        }

        //Update data individually per student
        $students = self::GetAttendee($request->input('attendance_id'));
        foreach ($students as $student){
            if (!$student->alpha){
                $score_id = 'score-'.$student->id;
                $progress_report_update = Progress::where([
                    ['id_attendance', $request->input('attendance_id')],
                    ['id_student', $student->id]
                ])->update([
                    'level' => $request->input('level'),
                    'unit' => $request->input('unit'),
                    'last_exercise' => $request->input('last_exercise'),
                    'score' => $request->input($score_id),
                    'documentation' =>$documentation_file_name,
                    'filled' => true
                ]);
            }
            else{
                $progress_report_update = Progress::where([
                    ['id_attendance', $request->input('attendance_id')],
                    ['id_student', $student->id]
                ])->update([
                    'filled' => true
                ]);
            }
        }

        //get the new progress report data
        $new_pr = Progress::where('id_attendance',$request->input('attendance_id'))->get();

        return response()->json([
            'success' => true,
            'attendance_id' => $request->input('attendance_id'),
            'progress_report' => $new_pr
        ], 200);
    }

    public function Students(){
        $students = Students::get();
        $view = view('student-list');

        return $view->with('students',$students);
    }

    public function StudentsData($student_id){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get programs and payment status
        $student_programs = StudentProgram::where('id_student',$student_id)->get();
        $student_tuition_payment = TuitionFee::whereIn('id_student',$student_programs->pluck('id_student')->toArray())->get();

        $view = view('student');

        return $view->with('student', $studentbio)->with('programs', $student_tuition_payment);
    }

    public function AttendanceHistory($from, $to){
        //Get attendance detail
        $progress = Attendance::select('attendances.id','attendances.date', 'students.name', 'attendances.location', 'attendances.class_type','fees.approved as fee_approval','teach_presences.approved as presence_approval','progress.filled', 'attendances.program')
        ->join('attendees','attendances.id','=','attendees.id_attendance')
        ->join('students','attendees.id_student','=','students.id')->leftjoin('progress', function($join){
            $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        })->leftjoin('fees','attendances.id','=','fees.id_attendance')
        ->join('teach_presences','teach_presences.id_attendance','=','attendances.id')
        ->where('attendances.id_teacher',auth()->user()->id_teacher)->orderBy('attendances.date','DESC')
        ->where('attendances.date','>=', $from)
        ->where('attendances.date','<=', $to)
        ->get();
        $view = view('attendance-history')->with([
            'progress' => $progress,
            'from' => $from,
            'to' => $to,
        ]);
        return $view;
    }

    public function StudentsProgressReport($student_id, $program){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get attendance id first based on program
        $attendee_data = Attendee::where('id_student',$student_id)->get();
        $attendance_ids = Attendance::whereIn('id', $attendee_data->pluck('id_attendance')->toArray())->where('program',$program)->get();
        /*
        $attendance_ids = array();
        foreach($attendee_data as $ad){
            array_push($attendance_ids, Attendance::where([
                ['id', $ad->id_attendance],
                ['program', $program]
                ])->first());
        }*/

        /*
        $progress_reports = array();
        foreach($attendance_ids as $aids){
            $pr = Progress::where([
                ['id_student',$student_id],
                ['id_attendance',$aids->id]
            ])->first();
            array_push($progress_reports, $pr);
        }*/
        $progress_reports = Progress::where('progress.id_student',$student_id)
        ->whereIn('progress.id_attendance', $attendance_ids->pluck('id')->toArray())
        ->join('attendances','attendances.id','=','progress.id_attendance')
        ->join('student_presences',function($join){
            $join->on('student_presences.id_attendance','=','progress.id_attendance')->on('student_presences.id_student','=','progress.id_student');
        })
        ->where('progress.student_alpha',false)
        ->orderBy('attendances.date','DESC')
        ->get();

        $student_presences_unpaid = StudentPresence::whereIn('id_attendance',$attendance_ids->pluck('id')->toArray())
        ->where('id_student',$student_id)
        ->where('spp_paid', false)->count();

        $student_tuition_payment = TuitionFee::where('id_student',$student_id)
        ->where('program',$program)
        ->first()->quota;


        $view = view('progress-report-list')
        ->with('progress_report',$progress_reports)
        ->with('attendance',$attendance_ids)
        ->with('student', $studentbio)
        ->with('program',$program)
        ->with('student_id',$student_id)
        ->with('unpaid',$student_presences_unpaid)
        ->with('quota', $student_tuition_payment);

        return $view;
    }

    public function StudentsAttendanceHistory($student_id){
        //get student data
        $studentbio = Students::where('id',$student_id)->get()->first();
        //get attendance id first based on program
        $attendee_data = Attendee::where('id_student',$student_id)->get();
        $attendance_ids = Attendance::whereIn('id', $attendee_data->pluck('id_attendance')->toArray())
        ->get();

        // $progress_reports = Progress::where('progress.id_student',$student_id)
        // ->whereIn('progress.id_attendance', $attendance_ids->pluck('id')->toArray())
        // ->join('attendances','attendances.id','=','progress.id_attendance')
        // ->join('student_presences',function($join){
        //     $join->on('student_presences.id_attendance','=','progress.id_attendance')->on('student_presences.id_student','=','progress.id_student');
        // })
        // ->orderBy('attendances.date','DESC')
        // ->get();

        $progress_reports = Attendance::select('attendances.id'
        ,'attendances.id_teacher','attendances.date','attendances.time','attendances.program','attendances.location','attendances.class_type'
        ,'progress.id as id progress','progress.id_attendance','progress.documentation','progress.note','progress.student_alpha'
        ,'attendees.present','attendees.alpha','attendees.homework','progress.filled','student_presences.spp_paid')
        ->whereIn('attendances.id',$attendance_ids->pluck('id')->toArray())
        ->join('attendees','attendees.id_attendance','=','attendances.id')
        ->leftjoin('progress',function($join){
            $join->on('progress.id_attendance','=','attendances.id')
            ->on('progress.id_attendance','=','attendees.id_attendance');
        })
        ->leftjoin('student_presences',function($join){
            $join->on('student_presences.id_attendance','=','attendances.id')
            ->on('student_presences.id_attendance','=','progress.id_attendance')
            ->on('student_presences.id_attendance','=','attendees.id_attendance')
            ->on('student_presences.id_student','=','progress.id_student')
            ->on('student_presences.id_student','=','attendees.id_student')
            ;
        })
        ->where([
            ['attendees.id_student',$student_id],
            ['progress.id_student',$student_id],
            ['student_presences.id_student',$student_id]
        ])
        ->orderBy('attendances.date','DESC')
        ->distinct()
        ->get();

        //return $progress_reports;

        $student_presences_unpaid = StudentPresence::whereIn('id_attendance',$attendance_ids->pluck('id')->toArray())
        ->where('id_student',$student_id)
        ->where('spp_paid', false)->count();

        $student_tuition_payment = TuitionFee::where('id_student',$student_id)
        ->first()->quota;


        $view = view('student-attendance-history')
        ->with('progress_report',$progress_reports)
        ->with('attendance',$attendance_ids)
        ->with('student', $studentbio)
        ->with('student_id',$student_id)
        ->with('unpaid',$student_presences_unpaid)
        ->with('quota', $student_tuition_payment);

        return $view;
    }

    private function CountCurrentUserFee(){
        $teachPresences = Attendance::whereMonth('date', date('m'))->where('id_teacher',auth()->user()->id_teacher)->get();
        $fee1 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('fee_nominal');
        $fee2 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('lunch_nominal');
        $fee3 = Fee::whereIn('id_attendance', $teachPresences->pluck('id')->toArray())->sum('transport_nominal');
        $incentive = Incentive::where('id_teacher',auth()->user()->id_teacher)->whereMonth('date',date('m'))->sum('nominal');
        $fees = $fee1+$fee2+$fee3 + $incentive;
        return $fees;
    }

    public function CurrentUserProfile(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::select('teachers.*','users.user_type')->where('teachers.id',$user_id)->join('users','users.id_teacher','=','teachers.id')->first();
        $position = TeachPosition::where('id_teacher', $user_id)->get();
        $program = TeachProgram::where('id_teacher', $user_id)->get();
        $method = TeachMethod::where('id_teacher', $user_id)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',$user_id)->distinct()->get();

        //get this month's fee
        $fees = self::CountCurrentUserFee();
        $view = view('teacher')->with('profile',$profile)->with('program',$program)
        ->with('position',$position)->with('method',$method)->with('schedule',$schedules)->with('fees',$fees)->with('user_id',$user_id);
        return $view;
    }



    public function ProfilePictureChange(Request $request){
        //PROSES UPLOAD KE SERVER
        $data =  $request["image"];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $destinationPath = 'uploads/profile-pictures';
        $image_name = auth()->user()->id_teacher.'_'.auth()->user()->name.'_'.date('m-d-Y h:i:s').'.png';

        // $old_profile_pic = '/home/u413247919/domains/rosterklugee.com/public_html/'.$destinationPath.'/'.$old_profile_pic;
        //     File::delete($old_profile_pic);
                    $old_profile_pic = $destinationPath.'/'.auth()->user()->photo;
        File::delete($old_profile_pic);


        file_put_contents($destinationPath.'/'.$image_name,$data);


        //PROSES PERUBAHAN DI DATABASE
        $teacher = Teachers::where('id',auth()->user()->id_teacher)->update(['photo' => $image_name]);
        $teacher = User::where('id_teacher',auth()->user()->id_teacher)->update(['photo' => $image_name]);

        return response()->json([
            'success' => true,
            'data' => '/'.$destinationPath.'/'.$image_name
        ],200);
    }

    public function CurrentUserStudents(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::select('teachers.*','users.user_type')->where('teachers.id',$user_id)->join('users','users.id_teacher','=','teachers.id')->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        //Get teacher's schedule student count
        $schedules = TeachSchedule::select('id_student')->join('schedules','teach_schedules.id_schedule','=','schedules.id')->join('student_schedules','teach_schedules.id_schedule','=','student_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->distinct()->get();
        //Method 2 : join
        $schedules_detail = Schedule::join('teach_schedules','teach_schedules.id_schedule','=','schedules.id')
        ->join('student_schedules', 'schedules.id','=','student_schedules.id_schedule')
        ->join('students','student_schedules.id_student','=','students.id')
        ->where('id_teacher',auth()->user()->id_teacher)
        ->orderBy('students.name','ASC')
        ->get();
        //return $schedules_detail;

        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();

        $view = view('teacher-students-list')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule_details',$schedules_detail)->with('schedule',$schedules)->with('fees',$fees);
        return $view->with('user_id',$user_id);
    }

    public function CurrentUserAttendance(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::select('teachers.*','users.user_type')->where('teachers.id',$user_id)->join('users','users.id_teacher','=','teachers.id')->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        //Get attendance detail
        $teach_presence = Attendance::select('attendances.id','attendances.date', 'students.name',
        'attendances.location', 'attendances.class_type','fees.approved as fee_approval',
        'teach_presences.approved as presence_approval','progress.filled','attendees.present',
        'attendees.alpha','attendees.homework', 'progress.student_alpha')
        ->join('attendees','attendances.id','=','attendees.id_attendance')
        ->join('students','attendees.id_student','=','students.id')->leftjoin('progress', function($join){
            $join->on('attendances.id','=','progress.id_attendance')->on('progress.id_student','=','attendees.id_student');
        })->leftjoin('fees','attendances.id','=','fees.id_attendance')
        ->join('teach_presences','teach_presences.id_attendance','=','attendances.id')
        ->where('attendances.id_teacher',auth()->user()->id_teacher)
        ->orderBy('attendances.id','ASC')
        ->orderBy('attendances.date','DESC')
        ->get();
        $teach_presence_approval=TeachPresence::where('id_teacher',auth()->user()->id_teacher)->get();
        //fees
        //get this month's fee
        $fees = self::CountCurrentUserFee();
        $view = view('teacher-attendance-history')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('fees',$fees)->with('teach_presence',$teach_presence)->with('approval',$teach_presence_approval);
        //return $teach_presence;
        return $view->with('user_id',$user_id);
    }

    public function Schedule(){
        $user_id = auth()->user()->id_teacher;
        //Basic data
        $profile = Teachers::select('teachers.*','users.user_type')->where('teachers.id',$user_id)->join('users','users.id_teacher','=','teachers.id')->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();
        $schedule = Schedule::select('schedules.id','schedules.day','schedules.begin','schedules.classroom_type','schedules.classroom_students','schedules.program','schedules.subject','students.name')->join('student_schedules','schedules.id','=','student_schedules.id_schedule')->join('students','student_schedules.id_student','=','students.id')->join('teach_schedules','schedules.id','=','teach_schedules.id_schedule')->where('id_teacher',auth()->user()->id_teacher)->orderByRaw('FIELD(day,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')->orderBy('schedules.begin','ASC')->get();
        $fees = self::CountCurrentUserFee();
        //return $schedule;
        $view = view('schedule')->with('profile',$profile)->with('position',$position)->with('method',$method)->with('schedule',$schedule)->with('fees',$fees);
        return $view->with('user_id',$user_id);
    }

    public function Earnings($month,$year){
        $user_id = auth()->user()->id_teacher;
        $years = Attendance::selectRaw('YEAR(date) as year')->distinct()->get();
        //Basic data
        $profile = Teachers::where('id',auth()->user()->id_teacher)->first();
        $position = TeachPosition::where('id_teacher', auth()->user()->id_teacher)->get();
        $method = TeachMethod::where('id_teacher', auth()->user()->id_teacher)->get();

        $teachPresences = Attendance::whereMonth('date', $month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();
        $fee = Fee::join('attendances','fees.id_attendance','=','attendances.id')
        ->whereIn('id_attendance', $teachPresences->pluck('id')->toArray())
        ->whereMonth('attendances.date',$month)->whereYear('attendances.date',$year)
        ->orderBy('attendances.date','DESC')
        ->get();
        $salary = Salary::whereMonth('date',$month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();
        $total_fee = self::CountCurrentUserFee();
        $incentives = Incentive::whereMonth('date', $month)->whereYear('date',$year)->where('id_teacher',$user_id)->get();


        $view=view('teacher-earnings')->with('fee',$fee)->with('salary',$salary)->with('incentive',$incentives)->with('fees',$total_fee)->with('profile',$profile)->with('position',$position)->with('method',$method);
        return $view->with('user_id',$user_id)->with('month',$month)->with('year',$year)->with('years',$years);
    }

    public function Management(){
        $programs = Program::select('program')->get();
        $feelist = FeeList::get();
        $incentives = IncentiveList::get();
        $salary = SalaryList::get();
        $programs = Program::get();
        $partner = Referrer::select('referrers.*','students.parent','students.parent_name')
        ->join('students','students.id','=','referrers.parent_student_id')->get();
        $parent = Students::select('id','parent','parent_name')
        ->whereNotIn('id', Referrer::select('parent_student_id')->get())->get();
        $view = view('management');
        return $view->with([
            'programs'=>$programs,
            'feelist' => $feelist,
            'incentive' =>$incentives,
            'salary' => $salary,
            'programs' => $programs,
            'partner' => $partner,
            'parent' => $parent,
        ]);
    }

    public function ProfileEdit(Request $request){
        $teacher = Teachers::where('id',$request->input('id_teacher'))->first();
        $teacher->address = $request->input('alamat');
        $teacher->phone_contact = $request->input('telp');
        $teacher->name=$request->input('nama');
        $teacher->nik = $request->input('nik');
        $teacher->birthdate = $request->input('tanggal-lahir');
        $teacher->save();
        return redirect()->back();
    }

    public function Notification(){
        $view = view('notification');
        return $view;
    }

    public function MarkNotification($notif_id){
        auth()->user()
            ->unreadNotifications
            ->when($notif_id, function ($query) use ($notif_id) {
                return $query->where('id', $notif_id);
            })
            ->markAsRead();

        return redirect()->back();
    }

    public function MarkAllNotification(){
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()->back();
    }

    public function ChangePassword(Request $request){
        $user = User::where('id_teacher',$request->input('id-teacher'))->first();
        $user->password = Hash::make($request->input('password-form'));
        $user->save();
        return redirect()->back();
    }
}
