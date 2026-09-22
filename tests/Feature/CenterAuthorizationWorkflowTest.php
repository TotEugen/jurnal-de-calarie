<?php

use App\Enums\AffiliationStatus;
use App\Enums\CenterApplicationStatus;
use App\Enums\CenterMembershipRole;
use App\Models\CenterApplication;
use App\Models\CenterMembership;
use App\Models\EquestrianCenter;
use App\Models\User;
use Livewire\Livewire;

test('a center can have a draft authorization application and an administrator', function () {
    $applicant = User::factory()->create();

    $center = EquestrianCenter::query()->create([
        'legal_name' => 'Centrul Ecvestru Demo SRL',
        'slug' => 'centrul-ecvestru-demo',
        'fiscal_code' => 'RO12345678',
        'email' => 'centru@example.com',
        'county' => 'Brașov',
        'locality' => 'Brașov',
        'address' => 'Strada Exemplu 1',
    ]);

    $application = CenterApplication::query()->create([
        'equestrian_center_id' => $center->id,
        'submitted_by' => $applicant->id,
    ]);

    $membership = CenterMembership::query()->create([
        'equestrian_center_id' => $center->id,
        'user_id' => $applicant->id,
        'role' => CenterMembershipRole::Center,
    ]);

    $center->refresh();
    $application->refresh();

    expect($center->affiliation_status)->toBe(AffiliationStatus::Pending)
        ->and($application->status)->toBe(CenterApplicationStatus::Draft)
        ->and($membership->role)->toBe(CenterMembershipRole::Center)
        ->and($center->applications)->toHaveCount(1)
        ->and($center->memberships)->toHaveCount(1);
});

test('an authenticated user can submit a center authorization application', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('pages::centers.apply')
        ->set('legal_name', 'Centrul Ecvestru Transilvania SRL')
        ->set('fiscal_code', 'RO87654321')
        ->set('email', 'contact@centru.test')
        ->set('county', 'Cluj')
        ->set('locality', 'Cluj-Napoca')
        ->set('address', 'Strada Cailor 10')
        ->call('submitApplication')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $application = CenterApplication::query()->firstOrFail();

    expect($application->status)->toBe(CenterApplicationStatus::Submitted)
        ->and($application->submitted_at)->not->toBeNull()
        ->and($user->fresh()->hasRole('center'))->toBeTrue();
});
