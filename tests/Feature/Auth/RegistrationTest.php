<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::registration());
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk()
            ->assertSee('Creează cont călăreț')
            ->assertSee('Anulează')
            ->assertSee(route('register.store'), escape: false);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
            'birth_date' => '1995-04-12',
            'phone' => '0712345678',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'had_physical_journal' => '1',
            'data_processing_consent' => '1',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['name' => 'Ion Popescu', 'email' => 'test@example.com']);
        $this->assertDatabaseHas('rider_profiles', [
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
            'phone' => '0712345678',
            'had_physical_journal' => true,
            'contact_email' => 'test@example.com',
        ]);
        $this->assertDatabaseCount('guardian_relationships', 0);
    }

    public function test_a_minor_must_provide_guardian_details(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Ana',
            'last_name' => 'Popescu',
            'birth_date' => now()->subYears(15)->format('Y-m-d'),
            'password' => 'password',
            'password_confirmation' => 'password',
            'data_processing_consent' => '1',
        ]);

        $response->assertSessionHasErrors([
            'guardian_first_name',
            'guardian_last_name',
            'guardian_age',
            'guardian_phone',
            'guardian_email',
            'guardian_relationship',
        ]);
        $this->assertGuest();
    }

    public function test_a_minor_can_register_with_guardian_details(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Ana',
            'last_name' => 'Popescu',
            'birth_date' => now()->subYears(15)->format('Y-m-d'),
            'password' => 'password',
            'password_confirmation' => 'password',
            'guardian_first_name' => 'Maria',
            'guardian_last_name' => 'Popescu',
            'guardian_age' => '42',
            'guardian_phone' => '0799999999',
            'guardian_email' => 'maria@example.com',
            'guardian_relationship' => 'parent',
            'data_processing_consent' => '1',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs(User::query()->where('email', 'maria@example.com')->first());
        $this->assertDatabaseHas('rider_profiles', [
            'first_name' => 'Ana',
            'last_name' => 'Popescu',
            'user_id' => null,
            'phone' => null,
            'contact_email' => null,
        ]);
        $this->assertDatabaseHas('guardian_relationships', [
            'first_name' => 'Maria',
            'last_name' => 'Popescu',
            'age' => 42,
            'phone' => '0799999999',
            'email' => 'maria@example.com',
            'relationship' => 'parent',
            'is_primary' => true,
        ]);
    }

    public function test_an_existing_rider_can_use_the_same_account_as_a_guardian(): void
    {
        $guardian = User::factory()->create([
            'email' => 'maria@example.com',
            'password' => 'password',
        ]);
        $guardian->roles()->attach(Role::query()->create(['code' => 'rider', 'name' => 'Călăreț']));

        $response = $this->post(route('register.store'), [
            'first_name' => 'Ana',
            'last_name' => 'Popescu',
            'birth_date' => now()->subYears(15)->format('Y-m-d'),
            'password' => 'password',
            'password_confirmation' => 'password',
            'guardian_first_name' => 'Maria',
            'guardian_last_name' => 'Popescu',
            'guardian_age' => '42',
            'guardian_phone' => '0799999999',
            'guardian_email' => 'maria@example.com',
            'guardian_relationship' => 'parent',
            'data_processing_consent' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($guardian);
        $this->assertDatabaseCount('users', 1);
        $this->assertTrue($guardian->fresh()->hasRole('rider'));
        $this->assertTrue($guardian->fresh()->hasRole('guardian'));
    }
}
