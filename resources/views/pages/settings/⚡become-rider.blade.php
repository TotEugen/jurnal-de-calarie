<?php

use App\Models\RiderProfile;
use App\Models\Role;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Devino calaret')] class extends Component {
    public bool $had_physical_journal = false;
    public string $physical_journal_issuing_center = '';
    public string $physical_journal_series = '';
    public string $physical_journal_rider_code = '';

    public function activate(): void
    {
        $user = Auth::user();
        abort_if($user->riderProfile, 409);
        $profile = $user->guardianProfile;
        abort_unless($profile, 403);

        $validated = $this->validate([
            'had_physical_journal' => ['accepted'],
            'physical_journal_issuing_center' => ['required', Rule::in(config('frte.affiliated_centers'))],
            'physical_journal_series' => ['required', 'string', 'max:100'],
            'physical_journal_rider_code' => ['required', 'string', 'max:100'],
        ], ['had_physical_journal.accepted' => 'Pentru activarea directa este necesar un jurnal fizic existent.']);

        DB::transaction(function () use ($user, $profile, $validated): void {
            RiderProfile::query()->create([
                'user_id' => $user->id, 'first_name' => $profile->first_name, 'last_name' => $profile->last_name,
                'birth_date' => $profile->birth_date, 'phone' => $profile->phone, 'contact_email' => $user->email,
                'had_physical_journal' => true, 'physical_journal_issuing_center' => $validated['physical_journal_issuing_center'],
                'physical_journal_series' => $validated['physical_journal_series'], 'physical_journal_rider_code' => $validated['physical_journal_rider_code'],
                'data_processing_consent_at' => now(), 'status' => 'active', 'activated_at' => now(),
            ]);
            $role = Role::query()->firstOrCreate(['code' => 'rider'], ['name' => 'Calaret']);
            $user->roles()->syncWithoutDetaching($role);
        });

        Flux::toast(variant: 'success', text: 'Profilul de calaret a fost activat.');
        $this->redirectRoute('dashboard', navigate: true);
    }
}; ?>

<section class="mx-auto w-full max-w-3xl space-y-7">
    @include('partials.settings-heading')
    <div class="rounded-3xl border border-emerald-200 bg-white p-7 shadow-sm dark:border-emerald-900 dark:bg-zinc-900">
        <flux:heading size="xl">Devino calaret</flux:heading>
        <flux:text class="mt-2">Poti adauga profilul de calaret aceluiasi cont de parinte sau tutore.</flux:text>
        <form wire:submit="activate" class="mt-7 space-y-6">
            <flux:checkbox wire:model.live="had_physical_journal" label="Am avut un jurnal de calarie in format fizic." />
            @if ($had_physical_journal)
                <flux:select wire:model="physical_journal_issuing_center" label="Centru emitent Jurnal de Calarie" required><flux:select.option value="">Selecteaza centrul</flux:select.option>@foreach(config('frte.affiliated_centers') as $center)<flux:select.option :value="$center">{{ $center }}</flux:select.option>@endforeach</flux:select>
                <div class="grid gap-5 sm:grid-cols-2"><flux:input wire:model="physical_journal_series" label="Serie jurnal" required /><flux:input wire:model="physical_journal_rider_code" label="Cod calaret" required /></div>
                <flux:button type="submit" variant="primary">Activeaza profilul de calaret</flux:button>
            @else
                <div class="rounded-2xl bg-sky-50 p-5 text-sm text-sky-950 dark:bg-sky-950/40 dark:text-sky-100">Daca nu ai jurnal fizic, vei putea trimite o cerere catre administrator. Aceasta optiune va fi adaugata intr-o etapa urmatoare.</div>
            @endif
        </form>
    </div>
</section>
