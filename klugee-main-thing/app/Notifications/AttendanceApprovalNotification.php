<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Teachers;
use App\TeachPresence;
use App\User;
class AttendanceApprovalNotification extends Notification
{
    use Queueable;

    public function __construct($user, $notifs)
    {
        $this->user = $user;
        $this->notifs = $notifs;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return $this->notifs;
    }
}
