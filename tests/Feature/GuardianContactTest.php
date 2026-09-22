<?php

use App\Models\GuardianRelationship;
use App\Models\RiderProfile;
use App\Models\User;

test('a guardian can add contact details to a managed rider', function () {
    $guardian = User::factory()->create();
    $rider = RiderProfile::query()->create([
        'first_name' => 'Ana',
        'last_name' => 'Popescu',
        'birth_date' => now()->subYears(15),
        'phone' => null,
        'contact_email' => null,
    ]);

    GuardianRelationship::query()->create([
        'guardian_user_id' => $guardian->id,
        'rider_profile_id' => $rider->id,
        'relationship' => 'parent',
        'is_primary' => true,
    ]);

    $this->actingAs($guardian)
        ->patch(route('guardian.riders.contact.update', $rider), [
            'phone' => '0712345678',
            'contact_email' => 'minor@example.com',
        ])
        ->assertRedirect(route('guardian.dashboard'));

    $this->assertDatabaseHas('rider_profiles', [
        'id' => $rider->id,
        'phone' => '0712345678',
        'contact_email' => 'minor@example.com',
    ]);
});

test('a user cannot edit contact details for an unmanaged rider', function () {
    $user = User::factory()->create();
    $rider = RiderProfile::query()->create([
        'first_name' => 'Ana',
        'last_name' => 'Popescu',
        'birth_date' => now()->subYears(15),
    ]);

    $this->actingAs($user)
        ->patch(route('guardian.riders.contact.update', $rider), [
            'phone' => '0712345678',
            'contact_email' => 'minor@example.com',
        ])
        ->assertForbidden();
});
