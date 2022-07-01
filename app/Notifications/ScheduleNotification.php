<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

use App\Students;
use App\Teachers;
use App\TeachSchedule;
use App\Schedule;
use App\StudentSchedule;
use App\User;


class ScheduleNotification extends Notification
{
    use Queueable;

    public function __construct($user,$notifs)
    {
        $this->user = $user;
        $this->notifs = $notifs;
        date_default_timezone_set('Asia/Jakarta');
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function CheckNotifDuplicate($data){
        $dbdata = DB::table('notifications')
        ->select('data')
        ->where('data',json_encode($data))
        ->get();
        return count($dbdata) > 0;
    }

    public function toArray($notifiable)
    {
        return $this->notifs;
    }
}
