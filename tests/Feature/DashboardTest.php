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
        $response
            ->assertOk()
            ->assertSee('data-test="back-button"', escape: false)
            ->assertSee('Inapoi');
    }

    public function test_authenticated_homepage_shows_member_responsibilities_and_progress(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Responsabilitati membri FRTE')
            ->assertSee('Progres')
            ->assertSee('Log out')
            ->assertSee(route('logout'), escape: false)
            ->assertSee(route('frte.responsibilities'), escape: false);
    }

    public function test_guest_homepage_does_not_show_logout_button(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Autentificare')
            ->assertDontSee('Log out');
    }

    public function test_authenticated_users_can_read_the_frte_responsibilities_guide(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('frte.responsibilities'))
            ->assertOk()
            ->assertSee('Reguli de siguranta si etica')
            ->assertSee('Reguli privind interactiunea cu calul')
            ->assertSee('Cum poti recunoaste un centru ecvestru responsabil')
            ->assertSee('Importanta calariei responsabile')
            ->assertDontSee('Cum devine un centru ecvestru membru FRTE?');
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

    public function test_centers_page_lists_frte_member_centers(): void
    {
        $this->get(route('centers.index'))
            ->assertOk()
            ->assertSee('Centre de echitatie membre FRTE')
            ->assertSee('Potcoava Mountain Hideaway')
            ->assertSee('Caii din Padure')
            ->assertSee(route('centers.apply'), escape: false);
    }

    public function test_professionals_page_lists_frte_certified_monitors(): void
    {
        $this->get(route('professionals.index'))
            ->assertOk()
            ->assertSee('Profesionisti certificati FRTE')
            ->assertSee('Madalina Burghelea')
            ->assertSee('Marius Corcau')
            ->assertSee('Vrei sa devii profesionist acreditat?')
            ->assertSee('https://www.frte.org.ro/cursuri-profesionisti-echitatie', escape: false);
    }

    public function test_federation_page_presents_story_values_and_contact_details(): void
    {
        $this->get(route('federation.index'))
            ->assertOk()
            ->assertSee('Povestea FRTE')
            ->assertSee('Siguranta calaretului')
            ->assertSee('Bunastarea calului')
            ->assertSee('info@frte.org.ro')
            ->assertSee('+40 (0)723 467 587');
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
        $guardianRole = Role::query()->create(['code' => 'guardian', 'name' => 'Parinte / Tutore']);
        $user->roles()->attach($guardianRole);

        $this->actingAs($user)
            ->get(route('guardian.dashboard'))
            ->assertOk()
            ->assertSee('Editeaza contul meu')
            ->assertSee(route('profile.edit'), escape: false);
    }
}
