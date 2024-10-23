<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OffRequestEmailNotification extends Notification
{
    use Queueable;

    protected $offrequest;

    public function __construct($offrequest)
    {
        $this->offrequest = $offrequest;
    }

    public function via($notifiable)
    {
        // Mengirimkan notifikasi melalui email
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Cuti Baru dari ' . $this->offrequest->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Ada pengajuan cuti baru dari karyawan berikut:')
            ->line('Nama: ' . $this->offrequest->name)
            ->line('Judul Cuti: ' . $this->offrequest->title)
            ->line('Deskripsi: ' . $this->offrequest->description)
            ->line('Mulai: ' . $this->offrequest->start_event)
            ->line('Selesai: ' . $this->offrequest->end_event)
            // ->action('Lihat Detail', url('Employee/offrequest/' . $this->offrequest->id))
            ->line('Terima kasih telah menggunakan sistem kami!');
    }

    public function toArray($notifiable)
    {
        return [
            // Isi notifikasi dalam bentuk array
        ];
    }
}
