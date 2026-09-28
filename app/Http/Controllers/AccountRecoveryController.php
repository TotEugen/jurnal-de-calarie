<?php

namespace App\Http\Controllers;

use App\Models\AccountAccessCode;
use App\Models\User;
use App\Notifications\AccountAccessCodeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountRecoveryController extends Controller
{
    public function request(): View
    {
        return view('pages.auth.forgot-password');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Nu am gasit un cont asociat acestei adrese de email.'])->withInput();
        }

        $code = $this->issueCode($user, 'password_reset', 'email');
        $user->notify(new AccountAccessCodeNotification($code, 'password_reset'));

        $request->session()->put([
            'recovery.user_id' => $user->id,
            'recovery.channel' => 'email',
        ]);

        return redirect()->route('recovery.verify')->with('status', 'Codul a fost trimis.');
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('recovery.user_id')) {
            return redirect()->route('recovery.request');
        }

        return view('pages.auth.verify-recovery-code');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $userId = $request->session()->get('recovery.user_id');

        if (! $this->consumeCode((int) $userId, 'password_reset', $request->string('code')->toString())) {
            return back()->withErrors(['code' => 'Codul este incorect sau a expirat.']);
        }

        $request->session()->put('recovery.authorized_at', time());

        return redirect()->route('recovery.reset');
    }

    public function resetForm(Request $request): View|RedirectResponse
    {
        if (! $this->recoveryIsAuthorized($request)) {
            return redirect()->route('recovery.request');
        }

        return view('pages.auth.reset-with-code');
    }

    public function reset(Request $request): RedirectResponse
    {
        abort_unless($this->recoveryIsAuthorized($request), 403);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::query()->whereKey($request->session()->get('recovery.user_id'))->firstOrFail()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->forget('recovery');

        return redirect()->route('login')->with('status', 'Parola a fost schimbata. Te poti autentifica.');
    }

    public function sendSecurityCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $code = $this->issueCode($user, 'security_confirmation', 'email');
        $user->notify(new AccountAccessCodeNotification($code, 'security_confirmation'));

        return back()->with('code_sent', 'email');
    }

    public function confirmSecurityCode(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        if (! $this->consumeCode($request->user()->id, 'security_confirmation', $request->string('code')->toString())) {
            return back()->withErrors(['code' => 'Codul este incorect sau a expirat.']);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('security.edit'));
    }

    private function issueCode(User $user, string $purpose, string $channel): string
    {
        AccountAccessCode::query()->where('user_id', $user->id)->where('purpose', $purpose)->delete();
        $code = (string) random_int(100000, 999999);

        AccountAccessCode::query()->create([
            'user_id' => $user->id,
            'purpose' => $purpose,
            'channel' => $channel,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    private function consumeCode(int $userId, string $purpose, string $code): bool
    {
        $record = AccountAccessCode::query()
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $record || $record->expires_at->isPast() || ! hash_equals($record->code_hash, hash('sha256', $code))) {
            return false;
        }

        $record->delete();

        return true;
    }

    private function recoveryIsAuthorized(Request $request): bool
    {
        return $request->session()->has('recovery.user_id')
            && (int) $request->session()->get('recovery.authorized_at', 0) >= time() - 600;
    }
}
