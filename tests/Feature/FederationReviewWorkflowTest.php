<?php

use App\Enums\CenterApplicationStatus;
use App\Models\ProfessionalApplication;
use App\Models\ProfessionalProfile;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

test('requesting changes requires notes and records the decision history', function () {
    $federationUser = User::factory()->create();
    $federationRole = Role::query()->create(['code' => 'federation', 'name' => 'Federație']);
    $federationUser->roles()->attach($federationRole);

    $applicant = User::factory()->create();
    $professional = ProfessionalProfile::query()->create(['user_id' => $applicant->id]);
    $application = ProfessionalApplication::query()->create([
        'professional_profile_id' => $professional->id,
        'submitted_by' => $applicant->id,
        'status' => CenterApplicationStatus::Submitted,
        'qualification_grade' => 'Monitor echitație',
        'qualification_obtained_at' => '2025-01-15',
        'submitted_at' => now(),
    ]);

    $this->actingAs($federationUser);

    Livewire::test('pages::federation.review', [
        'type' => 'monitor',
        'applicationId' => $application->id,
    ])->call('decide', 'changes')
        ->assertHasErrors('review_notes');

    Livewire::test('pages::federation.review', [
        'type' => 'monitor',
        'applicationId' => $application->id,
    ])->set('review_notes', 'Încărcați documentul care confirmă gradul declarat.')
        ->call('decide', 'changes')
        ->assertHasNoErrors();

    $application->refresh();

    expect($application->status)->toBe(CenterApplicationStatus::ChangesRequested)
        ->and($application->statusHistory)->toHaveCount(1)
        ->and($application->statusHistory->first()->notes)
        ->toBe('Încărcați documentul care confirmă gradul declarat.');
});
