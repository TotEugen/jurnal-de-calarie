<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::emailVerification());
    }

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertSee('Cod de confirmare')
            ->assertSee($user->email);
    }

    public function test_email_can_be_verified_with_the_correct_code(): void
    {
        $user = User::factory()->unverified()->create();
        $code = '123456';

        $user->forceFill([
            'email_verification_code_hash' => hash('sha256', $code),
            'email_verification_code_expires_at' => now()->addMinutes(15),
        ])->save();

        Event::fake();

        $this->actingAs($user)
            ->post(route('verification.code'), ['code' => $code])
            ->assertRedirect(route('home'));

        $user->refresh();

        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->email_verification_code_hash);
        $this->assertNull($user->email_verification_code_expires_at);
        Event::assertDispatched(Verified::class);
    }

    public function test_email_is_not_verified_with_an_invalid_code(): void
    {
        $user = User::factory()->unverified()->create([
            'email_verification_code_hash' => hash('sha256', '123456'),
            'email_verification_code_expires_at' => now()->addMinutes(15),
        ]);

        $this->actingAs($user)
            ->post(route('verification.code'), ['code' => '654321'])
            ->assertSessionHasErrors('code');

        $this->assertFalse($user->refresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_an_expired_code(): void
    {
        $user = User::factory()->unverified()->create([
            'email_verification_code_hash' => hash('sha256', '123456'),
            'email_verification_code_expires_at' => now()->subMinute(),
        ]);

        $this->actingAs($user)
            ->post(route('verification.code'), ['code' => '123456'])
            ->assertSessionHasErrors('code');

        $this->assertFalse($user->refresh()->hasVerifiedEmail());
    }

    public function test_a_new_code_can_be_requested(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentTo($user, EmailVerificationCodeNotification::class);
        $this->assertNotNull($user->refresh()->email_verification_code_hash);
    }
}
