<?php

use App\Models\CenterProfessionalAffiliation;
use App\Models\CenterRiderMembership;
use App\Models\EquestrianCenter;
use App\Models\ProfessionalProfile;
use App\Models\RiderProfile;
use App\Models\RidingActivity;
use App\Models\Role;
use App\Models\User;

test('an affiliated monitor can see a riders journey recorded at other centers', function () {
    $monitorUser = User::factory()->create();
    $riderUser = User::factory()->create();
    $monitorRole = Role::query()->create(['code' => 'monitor', 'name' => 'Monitor']);
    $monitorUser->roles()->attach($monitorRole);

    $centerA = createCenter('centrul-a', 'a@example.test');
    $centerB = createCenter('centrul-b', 'b@example.test');

    $rider = RiderProfile::query()->create([
        'user_id' => $riderUser->id,
        'first_name' => 'Ana',
        'last_name' => 'Popescu',
        'birth_date' => '2000-01-01',
        'status' => 'active',
    ]);

    $monitor = ProfessionalProfile::query()->create([
        'user_id' => $monitorUser->id,
        'federation_status' => 'active',
    ]);

    CenterProfessionalAffiliation::query()->create([
        'equestrian_center_id' => $centerB->id,
        'professional_profile_id' => $monitor->id,
        'status' => 'active',
    ]);

    CenterRiderMembership::query()->create([
        'equestrian_center_id' => $centerB->id,
        'rider_profile_id' => $rider->id,
        'status' => 'active',
        'requested_by' => $riderUser->id,
        'validated_by' => $monitorUser->id,
        'validated_at' => now(),
    ]);

    RidingActivity::query()->create([
        'rider_profile_id' => $rider->id,
        'equestrian_center_id' => $centerA->id,
        'professional_profile_id' => $monitor->id,
        'performed_at' => now()->subMonth(),
        'duration_minutes' => 60,
        'activity_type' => 'antrenament',
    ]);

    expect($monitorUser->can('view', $rider))->toBeTrue()
        ->and($rider->activities()->where('equestrian_center_id', $centerA->id)->exists())->toBeTrue();
});

test('an unaffiliated monitor cannot see a riders journey without explicit access', function () {
    $monitorUser = User::factory()->create();
    $riderUser = User::factory()->create();

    ProfessionalProfile::query()->create([
        'user_id' => $monitorUser->id,
        'federation_status' => 'active',
    ]);

    $rider = RiderProfile::query()->create([
        'user_id' => $riderUser->id,
        'first_name' => 'Mihai',
        'last_name' => 'Ionescu',
        'birth_date' => '1998-05-12',
        'status' => 'active',
    ]);

    expect($monitorUser->can('view', $rider))->toBeFalse();
});

function createCenter(string $slug, string $email): EquestrianCenter
{
    return EquestrianCenter::query()->create([
        'legal_name' => strtoupper($slug),
        'slug' => $slug,
        'email' => $email,
        'county' => 'Cluj',
        'locality' => 'Cluj-Napoca',
        'address' => 'Strada Exemplu 1',
        'affiliation_status' => 'active',
    ]);
}
