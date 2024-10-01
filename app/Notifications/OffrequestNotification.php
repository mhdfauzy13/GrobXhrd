<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class OffrequestNotification extends Notification
{
    use Queueable;

    protected $offrequest;

    public function __construct($offrequest)
    {
        $this->offrequest = $offrequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'offrequest_id' => $this->offrequest->offrequest_id,
            'user_name' => $this->offrequest->user->name,
            'status' => $this->offrequest->status,
            'message' => "Pengajuan cuti dari {$this->offrequest->user->name} telah {$this->offrequest->status}."
        ];
    }
}
