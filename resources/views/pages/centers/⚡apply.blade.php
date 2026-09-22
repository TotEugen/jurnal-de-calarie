<?php

use App\Enums\CenterApplicationStatus;
use App\Enums\CenterMembershipRole;
use App\Models\CenterApplication;
use App\Models\CenterMembership;
use App\Models\EquestrianCenter;
use App\Models\Role;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.public'), Title('Cerere autorizare centru')] class extends Component {
    public string $legal_name = '';
    public string $fiscal_code = '';
    public string $registration_number = '';
    public string $email = '';
    public string $phone = '';
    public string $website = '';
    public string $county = '';
    public string $locality = '';
    public string $address = '';
    public string $applicant_notes = '';

    public function mount(): void
    {
        if (! Auth::check()) {
            return;
        }

        $draft = Auth::user()->centerApplications()
            ->where('status', CenterApplicationStatus::Draft)
            ->with('center')
            ->latest()
            ->first();

        if (! $draft) {
            $this->email = Auth::user()->email;
            return;
        }

        $this->fill($draft->center->only([
            'legal_name', 'fiscal_code', 'registration_number', 'email', 'phone',
            'website', 'county', 'locality', 'address',
        ]));
        $this->applicant_notes = $draft->applicant_notes ?? '';
    }

    public function saveDraft(): void
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('centers.apply'));
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->persist(CenterApplicationStatus::Draft);
        Flux::toast(variant: 'success', text: 'Ciorna a fost salvată.');
    }

    public function submitApplication(): void
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('centers.apply'));
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $application = $this->persist(CenterApplicationStatus::Submitted);
        $application->update(['submitted_at' => now()]);

        Flux::toast(variant: 'success', text: 'Cererea a fost transmisă către FRTE.');
        $this->redirectRoute('dashboard', navigate: true);
    }

    private function persist(CenterApplicationStatus $status): CenterApplication
    {
        $validated = $this->validate([
            'legal_name' => ['required', 'string', 'max:255'],
            'fiscal_code' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:255'],
            'county' => ['required', 'string', 'max:100'],
            'locality' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'applicant_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        return DB::transaction(function () use ($validated, $status): CenterApplication {
            $existing = Auth::user()->centerApplications()
                ->where('status', CenterApplicationStatus::Draft)
                ->with('center')
                ->latest()
                ->first();

            $centerData = collect($validated)->except('applicant_notes')->all();
            $centerData['slug'] = $existing?->center->slug
                ?? Str::slug($validated['legal_name']).'-'.Str::lower(Str::random(6));

            $center = $existing?->center;
            $center = $center
                ? tap($center)->update($centerData)
                : EquestrianCenter::query()->create($centerData);

            $application = $existing ?? CenterApplication::query()->create([
                'equestrian_center_id' => $center->id,
                'submitted_by' => Auth::id(),
            ]);

            $application->update([
                'status' => $status,
                'applicant_notes' => $validated['applicant_notes'] ?: null,
            ]);

            CenterMembership::query()->firstOrCreate([
                'equestrian_center_id' => $center->id,
                'user_id' => Auth::id(),
                'role' => CenterMembershipRole::Center,
            ]);

            $centerRole = Role::query()->firstOrCreate(
                ['code' => 'center'],
                ['name' => 'Centru de echitație'],
            );
            Auth::user()->roles()->syncWithoutDetaching($centerRole);

            return $application;
        });
    }
}; ?>

<div class="mx-auto w-full max-w-5xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
            <flux:heading size="xl">Cerere de autorizare a centrului ecvestru</flux:heading>
            <flux:text class="mt-2 max-w-3xl">Completează datele juridice și de contact. Poți salva o ciornă înainte de transmiterea către FRTE.</flux:text>
        </header>

    @guest
        <section class="border-y border-zinc-200 py-10 dark:border-zinc-700">
            <flux:heading size="lg">Autentificare necesară pentru trimitere</flux:heading>
            <flux:text class="mt-2 max-w-2xl">Formularul este public, iar completarea și urmărirea cererii se fac într-un cont securizat.</flux:text>
            <div class="mt-5 flex gap-3"><flux:button :href="route('login')" variant="primary">Autentificare</flux:button><flux:button :href="route('register')">Creează cont</flux:button></div>
        </section>
    @else
    <form wire:submit="submitApplication" class="space-y-10">
            <section class="space-y-5">
                <div>
                    <flux:heading size="lg">Date de identificare</flux:heading>
                    <flux:text>Informațiile oficiale ale entității care solicită afilierea.</flux:text>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="legal_name" label="Denumire juridică" required />
                    <flux:input wire:model="fiscal_code" label="Cod fiscal / CUI" />
                    <flux:input wire:model="registration_number" label="Număr de înregistrare" />
                    <flux:input wire:model="website" label="Website" type="url" placeholder="https://" />
                </div>
            </section>

            <section class="space-y-5 border-t border-zinc-200 pt-8 dark:border-zinc-700">
                <div>
                    <flux:heading size="lg">Contact și sediu</flux:heading>
                    <flux:text>Date folosite de FRTE pentru comunicarea privind cererea.</flux:text>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="email" label="E-mail" type="email" required />
                    <flux:input wire:model="phone" label="Telefon" type="tel" />
                    <flux:input wire:model="county" label="Județ" required />
                    <flux:input wire:model="locality" label="Localitate" required />
                    <div class="md:col-span-2">
                        <flux:input wire:model="address" label="Adresă completă" required />
                    </div>
                </div>
            </section>

            <section class="space-y-5 border-t border-zinc-200 pt-8 dark:border-zinc-700">
                <flux:textarea wire:model="applicant_notes" label="Observații pentru FRTE" rows="4" />
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end dark:border-zinc-700">
                <flux:button type="button" wire:click="saveDraft" variant="ghost">Salvează ciorna</flux:button>
                <flux:button type="submit" variant="primary">Trimite către FRTE</flux:button>
            </div>
    </form>
    @endguest
</div>
