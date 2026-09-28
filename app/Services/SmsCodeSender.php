<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SmsCodeSender
{
    public function send(string $phone, string $code): void
    {
        $driver = config('services.sms.driver', 'log');
        $message = "Codul tau Jurnal de Calarie este {$code}. Este valabil 10 minute.";

        if ($driver === 'log') {
            Log::info('SMS de securitate', ['phone' => $phone, 'message' => $message]);

            return;
        }

        $url = config('services.sms.webhook_url');

        if (! $url) {
            throw new RuntimeException('Serviciul SMS nu este configurat.');
        }

        Http::withToken((string) config('services.sms.token'))
            ->post($url, ['to' => $phone, 'message' => $message])
            ->throw();
    }
}
