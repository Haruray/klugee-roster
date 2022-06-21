<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ScheduleNotification;
use Illuminate\Support\Facades\DB;

use Notification;
use App\Students;
use App\Teachers;
use App\TeachSchedule;
use App\Schedule;
use App\StudentSchedule;
use App\User;

class SendScheduleNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

    }

    public function CheckNotifDuplicate($data){
        $dbdata = DB::table('notifications')
        ->select('data')
        ->where('data',json_encode($data))
        ->get();
        return count($dbdata) > 0;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $notifs = array();
        $users =auth()->user();
        $schedules = Schedule::select('schedules.*','students.name as student_name','teachers.name as teacher_name')
        ->join('teach_schedules','teach_schedules.id_schedule','=','schedules.id')
        ->join('student_schedules','student_schedules.id_schedule','=','schedules.id')
        ->join('teachers','teachers.id','=','teach_schedules.id_teacher')
        ->join('students','students.id','=','student_schedules.id_student')
        ->where('teach_schedules.id_teacher',$event->user->id_teacher)->where('day',date('l'))
        ->where('begin','<=',date('h:i:s'))
        ->whereRaw('DATE_ADD(schedules.begin, INTERVAL 55 MINUTE) >= CURTIME()')->get();

        foreach($schedules as $s){
            $string = 'SCHEDULE NOTIFICATION: Sekarang adalah jadwal untuk mengajar '.$s->student_name.' untuk program '.$s->program.' pada jam '.$s->begin.' . Materi untuk hari ini adalah '.$s->subject.'.';
            array_push($notifs,$string);
        }

        if (count($schedules) > 0 && !self::CheckNotifDuplicate($notifs))
            Notification::send($users, new ScheduleNotification($event->user, $notifs));
    }
}
