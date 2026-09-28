<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationCodeController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Introdu codul primit pe email.',
            'code.digits' => 'Codul trebuie sa contina exact 6 cifre.',
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $validCode = $user->email_verification_code_hash
            && $user->email_verification_code_expires_at?->isFuture()
            && hash_equals($user->email_verification_code_hash, hash('sha256', $request->string('code')->toString()));

        if (! $validCode) {
            return back()->withErrors([
                'code' => 'Codul este incorect sau a expirat. Solicita un cod nou si incearca din nou.',
            ])->onlyInput('code');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->forceFill([
            'email_verification_code_hash' => null,
            'email_verification_code_expires_at' => null,
        ])->saveQuietly();

        return redirect()->route('home')->with('status', 'email-verified');
    }
}
