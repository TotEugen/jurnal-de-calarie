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
        ->patch(route('riders.profile.update', $rider), [
            'first_name' => 'Ana Maria',
            'last_name' => 'Popescu',
            'birth_date' => now()->subYears(15)->format('Y-m-d'),
            'phone' => '0712345678',
            'contact_email' => 'minor@example.com',
            'emergency_contact_name' => 'Maria Popescu',
            'emergency_contact_phone' => '0799999999',
        ])
        ->assertRedirect(route('guardian.dashboard'));

    $this->assertDatabaseHas('rider_profiles', [
        'id' => $rider->id,
        'first_name' => 'Ana Maria',
        'phone' => '0712345678',
        'contact_email' => 'minor@example.com',
    ]);
});

test('a rider can edit their own profile', function () {
    $user = User::factory()->create();
    $rider = RiderProfile::query()->create([
        'user_id' => $user->id,
        'first_name' => 'Ion',
        'last_name' => 'Popescu',
        'birth_date' => now()->subYears(25),
        'phone' => '0700000000',
        'contact_email' => 'ion.initial@example.com',
    ]);

    $this->actingAs($user)
        ->patch(route('riders.profile.update', $rider), [
            'first_name' => 'Ion',
            'last_name' => 'Ionescu',
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'phone' => '0711111111',
            'contact_email' => 'ion.nou@example.com',
            'had_physical_journal' => '1',
            'physical_journal_issuing_center' => 'Club Ecvestru Transilvania',
            'physical_journal_series' => 'B-2022',
            'physical_journal_rider_code' => 'R-7788',
            'emergency_contact_name' => 'Maria Ionescu',
            'emergency_contact_phone' => '0799999999',
        ])
        ->assertRedirect(route('profile.edit'));

    $this->assertDatabaseHas('rider_profiles', [
        'id' => $rider->id,
        'last_name' => 'Ionescu',
        'phone' => '0711111111',
        'contact_email' => 'ion.nou@example.com',
        'had_physical_journal' => true,
        'physical_journal_issuing_center' => 'Club Ecvestru Transilvania',
        'physical_journal_series' => 'B-2022',
        'physical_journal_rider_code' => 'R-7788',
        'emergency_contact_name' => 'Maria Ionescu',
        'emergency_contact_phone' => '0799999999',
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
        ->patch(route('riders.profile.update', $rider), [
            'first_name' => 'Ana',
            'last_name' => 'Popescu',
            'birth_date' => now()->subYears(15)->format('Y-m-d'),
            'phone' => '0712345678',
            'contact_email' => 'minor@example.com',
            'emergency_contact_name' => 'Maria Popescu',
            'emergency_contact_phone' => '0799999999',
        ])
        ->assertForbidden();
});
