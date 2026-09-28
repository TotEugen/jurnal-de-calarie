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
        'county' => 'Brasov',
        'locality' => 'Brasov',
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
        ->set('email', 'contact@centru.test')
        ->set('phone', '0722000000')
        ->set('website', 'https://centru.test')
        ->set('address', 'Strada Cailor 10')
        ->set('answers', [
            'facebook_page' => 'https://facebook.com/centru', 'legal_representative' => 'Ana Pop', 'founded_year' => '2020', 'center_story' => '',
            'horses_total' => '10', 'horses_breakdown' => '5 iepe, 2 armasari, 3 masculi castrati', 'ponies_total' => '2', 'ponies_breakdown' => '2 iepe',
            'housing_systems' => ['Boxe individuale'], 'housing_other' => '', 'average_housing_area' => '12 mp',
            'bedding_types' => ['Paie'], 'bedding_other' => '', 'movement_spaces' => ['Padocuri'], 'movement_other' => '',
            'feeding_description' => 'Fan si concentrate', 'shoeing_description' => 'La 6 saptamani', 'employees_total' => '3',
            'employee_roles' => 'Instructori si ingrijitori', 'employee_training_level' => 'Calificati', 'employee_certifications' => '', 'farrier_name' => 'Ion Pop',
            'land_area' => '5 hectare', 'facilities' => ['Manej exterior'], 'facilities_other' => '', 'facility_areas' => 'Manej 2000 mp',
            'tack_description' => 'Echipament verificat', 'feed_storage' => 'Depozit uscat', 'manure_management' => 'Colectare separata', 'facilities_notes' => '',
            'services' => ['Lectii de calarie de agrement'], 'services_other' => '', 'ride_durations' => [], 'ride_duration_other' => '',
            'related_services' => [], 'related_services_other' => '', 'services_notes' => '',
            'mixed_stallions_and_mares' => 'nu', 'mares_with_foals' => 'nu', 'rider_weight_limit' => 'da',
            'max_riders_per_ride' => '6', 'max_riders_per_professional' => '4', 'safety_measures' => 'Casca obligatorie',
            'insurance_types' => 'Raspundere civila', 'rider_facilities' => ['Toalete'], 'rider_facilities_other' => '', 'other_information' => '',
            'needs_challenges' => 'Formare profesionala', 'frte_expectations' => 'Sprijin si standarde', 'recommending_centers' => 'Nana Farm si Villa Abbatis',
            'questionnaire_contact_name' => 'Ana Pop', 'questionnaire_contact_phone' => '0722000000',
        ])
        ->call('submitApplication')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $application = CenterApplication::query()->firstOrFail();

    expect($application->status)->toBe(CenterApplicationStatus::Submitted)
        ->and($application->submitted_at)->not->toBeNull()
        ->and($user->fresh()->hasRole('center'))->toBeTrue();
});
