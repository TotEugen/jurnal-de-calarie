<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSee(route('account.choose'), escape: false);
    }

    public function test_authentication_portal_lists_all_account_types(): void
    {
        $this->get(route('account.choose'))
            ->assertOk()
            ->assertSee('Alege tipul contului')
            ->assertSee('Calaret')
            ->assertSee('Tutore / Parinte')
            ->assertSee('Centru')
            ->assertSee('Monitor')
            ->assertSee('Admin')
            ->assertSee('Creeaza cont calaret')
            ->assertSee('Creeaza cont tutore')
            ->assertSee('Creeaza cont centru')
            ->assertSee('Creeaza cont monitor')
            ->assertSee('Ai deja cont?')
            ->assertSee(route('login'), escape: false);

        $this->get(route('login', ['role' => 'guardian']))
            ->assertOk()
            ->assertSee('Autentificare tutore / parinte');

        $this->get(route('home'))
            ->assertSee(route('account.choose'), escape: false)
            ->assertSee(route('login'), escape: false);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrorsIn('email');

        $this->assertGuest();
    }

    public function test_users_with_two_factor_enabled_are_redirected_to_two_factor_challenge(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);

        $user = User::factory()->withTwoFactor()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.login'));
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
