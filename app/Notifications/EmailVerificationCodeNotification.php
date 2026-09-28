<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly string $code) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Codul de confirmare - Jurnal de Calarie')
            ->greeting('Bun venit in Jurnal de Calarie!')
            ->line('Foloseste codul de mai jos pentru a confirma adresa de email si pentru a activa accesul la cont:')
            ->line($this->code)
            ->line('Codul este valabil 15 minute. Daca nu ai creat acest cont, poti ignora mesajul.');
    }
}
