<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\AttendanceApprovalNotification;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Teachers;
use App\TeachPresence;
use App\User;
class SendAttendanceApprovalNotification
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
        $attendances = TeachPresence::select('teach_presences.id', 'teach_presences.id_teacher','teach_presences.date','teachers.name','teachers.is_teacher')->join('teachers','teachers.id','=','teach_presences.id_teacher')->where('approved',false)->orderBy('date','DESC')->get();
        $string = '('.date('d-m-Y').') There are Unapproved Attendances you need to confirm.';
        if ($event->user->user_type == "head teacher" && count($attendances) > 0 && !self::CheckNotifDuplicate($string)){
            $users =auth()->user();
            Notification::send($users, new AttendanceApprovalNotification($event->user, $string));
        }

    }
}
