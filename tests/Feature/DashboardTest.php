<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_account_sidebar_links_back_to_the_homepage(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee(route('home'), escape: false);
    }

    public function test_public_application_forms_are_visible_to_guests(): void
    {
        $this->get(route('centers.apply'))->assertOk();
        $this->get(route('professionals.apply'))->assertOk();
    }

    public function test_each_portal_is_protected_by_its_role(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('center.dashboard'))->assertForbidden();
        $this->get(route('monitor.dashboard'))->assertForbidden();
        $this->get(route('rider.dashboard'))->assertForbidden();

        foreach (['center', 'monitor', 'rider'] as $code) {
            $role = Role::query()->create(['code' => $code, 'name' => ucfirst($code)]);
            $user->roles()->attach($role);
        }

        $this->get(route('center.dashboard'))->assertOk();
        $this->get(route('monitor.dashboard'))->assertOk();
        $this->get(route('rider.dashboard'))->assertOk();
    }

    public function test_a_guardian_dashboard_links_to_account_editing(): void
    {
        $user = User::factory()->create();
        $guardianRole = Role::query()->create(['code' => 'guardian', 'name' => 'Părinte / Tutore']);
        $user->roles()->attach($guardianRole);

        $this->actingAs($user)
            ->get(route('guardian.dashboard'))
            ->assertOk()
            ->assertSee('Editează contul meu')
            ->assertSee(route('profile.edit'), escape: false);
    }
}
