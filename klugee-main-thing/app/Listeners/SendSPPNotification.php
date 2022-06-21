<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SPPNotification;
use Illuminate\Support\Facades\DB;
use Notification;
use App\User;
use App\Student;
use App\TuitionFee;

class SendSPPNotification
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
        $users =auth()->user();
        $tuition = TuitionFee::join('students','students.id','=','tuition_fees.id_student')->where('quota','<=',0)->get();
        //checking if its duplicate or nah
        $notifs = array();
        foreach($tuition as $t){
            $string = 'SPP NOTIFICATION : '.$t->name.' belum membayar SPP '.$t->program.' untuk bulan ini.';
            array_push($notifs, $string);
        }
        if ($users->user_type != "teacher" && count($tuition) > 0 && !self::CheckNotifDuplicate($notifs)){
            Notification::send($users, new SPPNotification($event->user, $notifs));
        }

    }
}
