<?php

use App\Enums\CenterApplicationStatus;
use App\Models\ProfessionalApplication;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

test('only federation users can access the validation dashboard', function () {
    $regularUser = User::factory()->create();
    $this->actingAs($regularUser)
        ->get(route('federation.applications'))
        ->assertForbidden();

    $federationUser = User::factory()->create();
    $federationRole = Role::query()->create(['code' => 'federation', 'name' => 'Federație']);
    $federationUser->roles()->attach($federationRole);

    $this->actingAs($federationUser)
        ->get(route('federation.applications'))
        ->assertOk();
});

test('monitor privileges are granted only after federation approval', function () {
    $applicant = User::factory()->create();
    $this->actingAs($applicant);

    Livewire::test('pages::professionals.apply')
        ->set('qualification_grade', 'Monitor echitație')
        ->set('qualification_identifier', 'CAL-2026-001')
        ->set('passport_number', 'PAS-1001')
        ->set('qualification_obtained_at', '2025-06-15')
        ->set('issuing_authority', 'Organism autorizat')
        ->call('submitApplication')
        ->assertHasNoErrors();

    expect($applicant->fresh()->hasRole('monitor'))->toBeFalse();

    $application = ProfessionalApplication::query()->firstOrFail();
    expect($application->status)->toBe(CenterApplicationStatus::Submitted);

    $federationUser = User::factory()->create();
    $federationRole = Role::query()->create(['code' => 'federation', 'name' => 'Federație']);
    $federationUser->roles()->attach($federationRole);
    $this->actingAs($federationUser);

    Livewire::test('pages::federation.review', [
        'type' => 'monitor',
        'applicationId' => $application->id,
    ])->call('decide', 'approve')
        ->assertHasNoErrors();

    expect($applicant->fresh()->hasRole('monitor'))->toBeTrue()
        ->and($application->fresh()->status)->toBe(CenterApplicationStatus::Approved)
        ->and($application->professional->fresh()->federation_status)->toBe('active');
});
