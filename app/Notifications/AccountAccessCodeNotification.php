<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountAccessCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly string $code, public readonly string $purpose) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $action = $this->purpose === 'password_reset'
            ? 'resetarea parolei'
            : 'confirmarea accesului la setarile de securitate';

        return (new MailMessage)
            ->subject('Cod de securitate - Jurnal de Calarie')
            ->greeting('Codul tau de securitate')
            ->line("Foloseste acest cod pentru {$action}:")
            ->line($this->code)
            ->line('Codul este valabil 10 minute si poate fi folosit o singura data.');
    }
}
