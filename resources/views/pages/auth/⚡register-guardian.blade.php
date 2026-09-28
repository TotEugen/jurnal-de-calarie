<?php

use App\Models\GuardianProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.auth'), Title('Creeaza cont parinte sau tutore')] class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $birth_date = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $data_processing_consent = false;

    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'data_processing_consent' => ['accepted'],
        ], [
            'birth_date.before_or_equal' => 'Trebuie sa ai cel putin 18 ani pentru a crea un cont de parinte sau tutore.',
            'data_processing_consent.accepted' => 'Acordul privind prelucrarea datelor este obligatoriu.',
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::query()->create([
                'name' => $validated['first_name'].' '.$validated['last_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
            GuardianProfile::query()->create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'birth_date' => $validated['birth_date'],
                'phone' => $validated['phone'],
            ]);
            $role = Role::query()->firstOrCreate(['code' => 'guardian'], ['name' => 'Parinte / Tutore']);
            $user->roles()->attach($role);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $this->redirectRoute('verification.notice', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-7">
    <div class="text-center">
        <flux:heading size="xl">Creeaza cont parinte / tutore</flux:heading>
        <flux:text class="mt-2">Contul poate administra profilurile calaretilor minori aflati in grija ta.</flux:text>
    </div>
    <form wire:submit="register" class="flex flex-col gap-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <flux:input wire:model="first_name" label="Nume" required autofocus />
            <flux:input wire:model="last_name" label="Prenume" required />
        </div>
        <flux:input wire:model="birth_date" label="Data de nastere" type="date" :max="now()->subYears(18)->toDateString()" required />
        <flux:input wire:model="email" label="Email" type="email" autocomplete="email" required />
        <flux:input wire:model="phone" label="Telefon" type="tel" autocomplete="tel" required />
        <div class="grid gap-5 sm:grid-cols-2">
            <flux:input wire:model="password" label="Parola" type="password" autocomplete="new-password" viewable required />
            <flux:input wire:model="password_confirmation" label="Confirma parola" type="password" autocomplete="new-password" viewable required />
        </div>
        <flux:checkbox wire:model="data_processing_consent" label="Sunt de acord cu prelucrarea datelor personale." required />
        <flux:button type="submit" variant="primary" class="w-full">Creeaza cont</flux:button>
    </form>
    <flux:text class="text-center">Ai deja cont? <flux:link :href="route('login')" wire:navigate>Autentificare</flux:link></flux:text>
</div>
