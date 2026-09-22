<?php

namespace Tests\Feature\Auth;

use App\Models\EquestrianCenter;
use App\Models\Grade;
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
        $center = EquestrianCenter::query()->create([
            'legal_name' => 'Centrul Ecvestru Verde',
            'slug' => 'centrul-ecvestru-verde',
            'email' => 'centru@example.com',
            'county' => 'Cluj',
            'locality' => 'Cluj-Napoca',
            'address' => 'Strada Cailor 1',
            'affiliation_status' => 'active',
        ]);
        $grade = Grade::query()->create(['code' => 'bronze', 'name' => 'Bronz', 'rank' => 1]);

        $response = $this->post(route('register.store'), [
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
            'birth_date' => '1995-04-12',
            'phone' => '0712345678',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'equestrian_center_id' => $center->id,
            'grade_id' => $grade->id,
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
            'self_reported_grade_id' => $grade->id,
        ]);
        $this->assertDatabaseHas('center_rider_memberships', [
            'equestrian_center_id' => $center->id,
            'status' => 'pending',
            'is_initial_validation' => true,
        ]);
    }
}
