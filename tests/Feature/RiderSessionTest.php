<?php

use App\Models\RiderProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('a rider can open the sessions page and record a session awaiting monitor confirmation', function () {
    $user = User::factory()->create();
    $role = Role::query()->create(['code' => 'rider', 'name' => 'Calaret']);
    $user->roles()->attach($role);
    $rider = RiderProfile::query()->create([
        'user_id' => $user->id,
        'first_name' => 'Ana',
        'last_name' => 'Popescu',
        'birth_date' => '2000-05-10',
        'status' => 'active',
    ]);

    $this->actingAs($user)->get(route('rider.sessions'))
        ->assertOk()
        ->assertSee('Sesiuni si progres')
        ->assertSee('Potcoava Mountain Hideaway');

    Livewire::actingAs($user)
        ->test('pages::riders.sessions')
        ->set('session_date', now()->format('Y-m-d'))
        ->set('session_time', '10:30')
        ->set('center_name', 'Potcoava Mountain Hideaway')
        ->set('duration_minutes', '60')
        ->set('activity_types', ['lesson', 'other'])
        ->set('other_activity', 'Exercitii de echilibru')
        ->set('horse_name', 'Artemis')
        ->set('learned_today', 'Pozitia corecta in sa.')
        ->set('key_takeaway', 'Sa pastrez ritmul constant.')
        ->set('next_experience', 'Primul traseu scurt.')
        ->set('instructor_name', 'Maria Ionescu')
        ->set('rating', 5)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('rider_sessions', [
        'rider_profile_id' => $rider->id,
        'session_number' => 1,
        'center_name' => 'Potcoava Mountain Hideaway',
        'horse_name' => 'Artemis',
        'rating' => 5,
        'status' => 'pending_monitor',
    ]);
});

test('a rider session requires at least one activity and a rating', function () {
    $user = User::factory()->create();
    $role = Role::query()->create(['code' => 'rider', 'name' => 'Calaret']);
    $user->roles()->attach($role);
    RiderProfile::query()->create([
        'user_id' => $user->id,
        'first_name' => 'Ana',
        'last_name' => 'Popescu',
        'birth_date' => '2000-05-10',
        'status' => 'active',
    ]);

    Livewire::actingAs($user)
        ->test('pages::riders.sessions')
        ->set('session_date', now()->format('Y-m-d'))
        ->set('session_time', '10:30')
        ->set('center_name', 'Potcoava Mountain Hideaway')
        ->set('duration_minutes', '60')
        ->set('horse_name', 'Artemis')
        ->set('learned_today', 'Lectie')
        ->set('key_takeaway', 'Ritm')
        ->set('instructor_name', 'Maria Ionescu')
        ->call('save')
        ->assertHasErrors(['activity_types', 'rating']);
});
