<?php

use App\Models\GuardianProfile;
use App\Models\User;
use Livewire\Livewire;

test('an adult can create a guardian account', function () {
    Livewire::test('pages::auth.register-guardian')
        ->set('first_name', 'Maria')
        ->set('last_name', 'Popescu')
        ->set('birth_date', now()->subYears(30)->toDateString())
        ->set('email', 'maria@example.com')
        ->set('phone', '0722000000')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'Password123!')
        ->set('data_processing_consent', true)
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('verification.notice'));

    $user = User::query()->where('email', 'maria@example.com')->firstOrFail();
    expect($user->hasRole('guardian'))->toBeTrue()
        ->and(GuardianProfile::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

test('a minor cannot create a guardian account', function () {
    Livewire::test('pages::auth.register-guardian')
        ->set('first_name', 'Ana')->set('last_name', 'Pop')
        ->set('birth_date', now()->subYears(17)->toDateString())
        ->set('email', 'ana@example.com')->set('phone', '0722000000')
        ->set('password', 'Password123!')->set('password_confirmation', 'Password123!')
        ->set('data_processing_consent', true)->call('register')
        ->assertHasErrors(['birth_date']);
});
