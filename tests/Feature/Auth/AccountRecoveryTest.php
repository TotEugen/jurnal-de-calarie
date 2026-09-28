<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\AccountAccessCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_recovered_with_a_code_sent_by_email(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('recovery.send'), [
            'email' => $user->email,
        ])->assertRedirect(route('recovery.verify'));

        Notification::assertSentTo($user, AccountAccessCodeNotification::class, function ($notification) use ($user) {
            $this->post(route('recovery.verify.store'), ['code' => $notification->code])
                ->assertRedirect(route('recovery.reset'));

            $this->post(route('recovery.reset.store'), [
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])->assertRedirect(route('login'));

            $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));

            return true;
        });
    }

    public function test_security_page_can_be_unlocked_with_an_email_code(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('password.confirm'))
            ->post(route('security.code.send'))
            ->assertRedirect(route('password.confirm'));

        Notification::assertSentTo($user, AccountAccessCodeNotification::class, function ($notification) {
            $this->post(route('security.code.confirm'), ['code' => $notification->code])
                ->assertRedirect(route('security.edit'));

            $this->assertGreaterThan(0, session('auth.password_confirmed_at'));

            return true;
        });
    }

    public function test_invalid_recovery_code_is_rejected(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('recovery.send'), [
            'email' => $user->email,
        ]);

        $this->post(route('recovery.verify.store'), ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }
}
