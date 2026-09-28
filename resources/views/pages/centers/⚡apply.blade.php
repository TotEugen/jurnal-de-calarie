<?php

use App\Enums\CenterApplicationStatus;
use App\Enums\CenterMembershipRole;
use App\Models\CenterApplication;
use App\Models\CenterMembership;
use App\Models\EquestrianCenter;
use App\Models\Role;
use App\Models\User;
use Flux\Flux;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.public'), Title('Chestionar inscriere centru')] class extends Component {
    public string $legal_name = '';
    public string $email = '';
    public string $phone = '';
    public string $website = '';
    public string $address = '';
    public string $county = '';
    public string $locality = '';
    public string $applicant_notes = '';
    public string $account_name = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $data_processing_consent = false;

    /** @var array<string, mixed> */
    public array $answers = [
        'facebook_page' => '', 'legal_representative' => '', 'founded_year' => '', 'center_story' => '',
        'horses_total' => '', 'horses_breakdown' => '', 'ponies_total' => '', 'ponies_breakdown' => '',
        'housing_systems' => [], 'housing_other' => '', 'average_housing_area' => '',
        'bedding_types' => [], 'bedding_other' => '', 'movement_spaces' => [], 'movement_other' => '',
        'feeding_description' => '', 'shoeing_description' => '', 'employees_total' => '',
        'employee_roles' => '', 'employee_training_level' => '', 'employee_certifications' => '', 'farrier_name' => '',
        'land_area' => '', 'facilities' => [], 'facilities_other' => '', 'facility_areas' => '',
        'tack_description' => '', 'feed_storage' => '', 'manure_management' => '', 'facilities_notes' => '',
        'services' => [], 'services_other' => '', 'ride_durations' => [], 'ride_duration_other' => '',
        'related_services' => [], 'related_services_other' => '', 'services_notes' => '',
        'mixed_stallions_and_mares' => '', 'mares_with_foals' => '', 'rider_weight_limit' => '',
        'max_riders_per_ride' => '', 'max_riders_per_professional' => '', 'safety_measures' => '',
        'insurance_types' => '', 'rider_facilities' => [], 'rider_facilities_other' => '', 'other_information' => '',
        'needs_challenges' => '', 'frte_expectations' => '', 'recommending_centers' => '',
        'questionnaire_contact_name' => '', 'questionnaire_contact_phone' => '',
    ];

    public function mount(): void
    {
        if (! Auth::check()) return;
        $draft = Auth::user()->centerApplications()->where('status', CenterApplicationStatus::Draft)->with('center')->latest()->first();
        if (! $draft) { $this->email = Auth::user()->email; return; }
        $this->fill($draft->center->only(['legal_name', 'email', 'phone', 'website', 'county', 'locality', 'address']));
        $this->applicant_notes = $draft->applicant_notes ?? '';
        $this->answers = array_replace($this->answers, $draft->questionnaire_answers ?? []);
    }

    public function saveDraft(): void
    {
        if (! $this->authenticate()) return;
        $this->persist(CenterApplicationStatus::Draft);
        Flux::toast(variant: 'success', text: 'Ciorna a fost salvata.');
    }

    public function submitApplication(): void
    {
        $newUser = null;

        if (! Auth::check()) {
            $credentials = $this->validate([
                'account_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'data_processing_consent' => ['accepted'],
            ], [
                'account_name.required' => 'Numele persoanei responsabile este obligatoriu.',
                'data_processing_consent.accepted' => 'Acordul privind prelucrarea datelor este obligatoriu.',
            ]);

            $newUser = User::query()->create([
                'name' => $credentials['account_name'],
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);
            Auth::login($newUser);
        }

        try {
            $application = $this->persist(CenterApplicationStatus::Submitted);
            $application->update(['submitted_at' => now()]);
        } catch (\Throwable $exception) {
            if ($newUser) {
                Auth::logout();
                $newUser->delete();
            }
            throw $exception;
        }

        if ($newUser) {
            event(new Registered($newUser));
            Flux::toast(variant: 'success', text: 'Contul a fost creat. Confirma codul primit pe email.');
            $this->redirectRoute('verification.notice', navigate: true);
            return;
        }

        Flux::toast(variant: 'success', text: 'Chestionarul a fost transmis catre FRTE.');
        $this->redirectRoute('dashboard', navigate: true);
    }

    private function authenticate(): bool
    {
        if (Auth::check()) return true;
        session()->put('url.intended', route('centers.apply'));
        $this->redirectRoute('login', navigate: true);
        return false;
    }

    private function persist(CenterApplicationStatus $status): CenterApplication
    {
        $required = $status === CenterApplicationStatus::Submitted ? 'required' : 'nullable';
        $validated = $this->validate([
            'legal_name' => [$required, 'string', 'max:255'], 'email' => [$required, 'email', 'max:255'], 'phone' => [$required, 'string', 'max:30'],
            'website' => [$required, 'url', 'max:255'], 'address' => [$required, 'string', 'max:500'], 'county' => ['nullable', 'string', 'max:100'],
            'locality' => ['nullable', 'string', 'max:100'], 'applicant_notes' => ['nullable', 'string', 'max:4000'],
            'answers.facebook_page' => [$required, 'url', 'max:255'], 'answers.legal_representative' => [$required, 'string', 'max:255'],
            'answers.founded_year' => [$required, 'integer', 'min:1800', 'max:'.now()->year], 'answers.center_story' => ['nullable', 'string', 'max:5000'],
            'answers.horses_total' => [$required, 'integer', 'min:0'], 'answers.horses_breakdown' => [$required, 'string', 'max:1000'],
            'answers.ponies_total' => [$required, 'integer', 'min:0'], 'answers.ponies_breakdown' => [$required, 'string', 'max:1000'],
            'answers.housing_systems' => [$required, 'array', 'min:1'], 'answers.housing_systems.*' => ['string'], 'answers.housing_other' => ['nullable', 'string', 'max:500'],
            'answers.average_housing_area' => [$required, 'string', 'max:1000'], 'answers.bedding_types' => [$required, 'array', 'min:1'], 'answers.bedding_types.*' => ['string'],
            'answers.bedding_other' => ['nullable', 'string', 'max:500'], 'answers.movement_spaces' => [$required, 'array', 'min:1'], 'answers.movement_spaces.*' => ['string'],
            'answers.movement_other' => ['nullable', 'string', 'max:500'], 'answers.feeding_description' => [$required, 'string', 'max:4000'],
            'answers.shoeing_description' => [$required, 'string', 'max:3000'], 'answers.employees_total' => [$required, 'integer', 'min:0'],
            'answers.employee_roles' => [$required, 'string', 'max:3000'], 'answers.employee_training_level' => [$required, 'string', 'max:3000'],
            'answers.employee_certifications' => ['nullable', 'string', 'max:4000'], 'answers.farrier_name' => [$required, 'string', 'max:255'],
            'answers.land_area' => [$required, 'string', 'max:500'], 'answers.facilities' => [$required, 'array', 'min:1'], 'answers.facilities.*' => ['string'],
            'answers.facilities_other' => ['nullable', 'string', 'max:500'], 'answers.facility_areas' => [$required, 'string', 'max:3000'],
            'answers.tack_description' => [$required, 'string', 'max:3000'], 'answers.feed_storage' => [$required, 'string', 'max:3000'],
            'answers.manure_management' => [$required, 'string', 'max:3000'], 'answers.facilities_notes' => ['nullable', 'string', 'max:3000'],
            'answers.services' => [$required, 'array', 'min:1'], 'answers.services.*' => ['string'], 'answers.services_other' => ['nullable', 'string', 'max:500'],
            'answers.ride_durations' => ['array'], 'answers.ride_durations.*' => ['string'], 'answers.ride_duration_other' => ['nullable', 'string', 'max:500'],
            'answers.related_services' => ['array'], 'answers.related_services.*' => ['string'], 'answers.related_services_other' => ['nullable', 'string', 'max:500'],
            'answers.services_notes' => ['nullable', 'string', 'max:3000'], 'answers.mixed_stallions_and_mares' => [$required, 'in:da,nu'],
            'answers.mares_with_foals' => [$required, 'in:da,nu'], 'answers.rider_weight_limit' => [$required, 'in:da,nu'],
            'answers.max_riders_per_ride' => [$required, 'integer', 'min:1'], 'answers.max_riders_per_professional' => [$required, 'integer', 'min:1'],
            'answers.safety_measures' => [$required, 'string', 'max:4000'], 'answers.insurance_types' => [$required, 'string', 'max:2000'],
            'answers.rider_facilities' => [$required, 'array', 'min:1'], 'answers.rider_facilities.*' => ['string'],
            'answers.rider_facilities_other' => ['nullable', 'string', 'max:500'], 'answers.other_information' => ['nullable', 'string', 'max:3000'],
            'answers.needs_challenges' => [$required, 'string', 'max:4000'], 'answers.frte_expectations' => [$required, 'string', 'max:4000'],
            'answers.recommending_centers' => [$required, 'string', 'max:1000'], 'answers.questionnaire_contact_name' => [$required, 'string', 'max:255'],
            'answers.questionnaire_contact_phone' => [$required, 'string', 'max:30'],
        ]);

        return DB::transaction(function () use ($validated, $status): CenterApplication {
            $existing = Auth::user()->centerApplications()->where('status', CenterApplicationStatus::Draft)->with('center')->latest()->first();
            $centerData = collect($validated)->except(['applicant_notes', 'answers'])->all();
            $centerData += ['fiscal_code' => null, 'registration_number' => null];
            $centerData['slug'] = $existing?->center->slug ?? Str::slug($validated['legal_name']).'-'.Str::lower(Str::random(6));
            $center = $existing?->center;
            $center = $center ? tap($center)->update($centerData) : EquestrianCenter::query()->create($centerData);
            $application = $existing ?? CenterApplication::query()->create(['equestrian_center_id' => $center->id, 'submitted_by' => Auth::id()]);
            $application->update(['status' => $status, 'applicant_notes' => $validated['applicant_notes'] ?: null, 'questionnaire_answers' => $validated['answers']]);
            CenterMembership::query()->firstOrCreate(['equestrian_center_id' => $center->id, 'user_id' => Auth::id(), 'role' => CenterMembershipRole::Center]);
            $role = Role::query()->firstOrCreate(['code' => 'center'], ['name' => 'Centru de echitatie']);
            Auth::user()->roles()->syncWithoutDetaching($role);
            return $application;
        });
    }
}; ?>

@php
    $groups = [
        'housing_systems' => ['La stanoaga', 'Boxe comune', 'Boxe individuale', 'Semi-libertate'],
        'bedding_types' => ['Talas', 'Paie', 'Rumegus'], 'movement_spaces' => ['Padocuri', 'Pasuni', 'Plimbator cai'],
        'facilities' => ['Manej acoperit', 'Manej exterior', 'Round pen', 'Pista de antrenament', 'Grajd', 'Padocuri pentru cai', 'Pasuni pentru cai', 'Plimbator pentru cai', 'Dusuri ecvidee', 'Selarie', 'Depozit furaje', 'Mijloace de transport pentru cai'],
        'services' => ['Lectii de calarie de agrement', 'Plimbari calare in natura (la lesa)', 'Ture calare', 'Trasee ecvestre'],
        'ride_durations' => ['Ture de scurta durata (1h)', 'Ture de cateva ore', 'Ture de o zi (max. 5-6 ore)', 'Ture de mai multe zile'],
        'related_services' => ['Cazare', 'Masa'], 'rider_facilities' => ['Receptie / spatiu de intampinare', 'Vestiare', 'Dusuri', 'Toalete', 'Parcare', 'Niciuna din cele de mai sus'],
    ];
@endphp

<div class="mx-auto w-full max-w-5xl space-y-8">
    <header class="rounded-3xl bg-gradient-to-br from-emerald-950 via-emerald-800 to-teal-600 p-8 text-white shadow-xl">
        <p class="text-sm font-semibold uppercase tracking-[.18em] text-emerald-100">Afiliere FRTE</p>
        <flux:heading size="xl" class="mt-2 !text-white">Chestionar de inscriere centru ecvestru</flux:heading>
        <p class="mt-3 max-w-3xl text-emerald-50">Completeaza informatiile despre centru, cai, echipa, facilitati, servicii si siguranta.</p>
    </header>
    <form wire:submit="submitApplication" class="space-y-8">
            @guest
                <x-center-form-section title="Contul centrului" description="Datele folosite pentru autentificare si urmarirea solicitarii.">
                    <flux:input wire:model="account_name" label="Persoana responsabila de cont (nume si prenume)" required />
                    <div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="password" label="Parola" type="password" autocomplete="new-password" viewable required /><flux:input wire:model="password_confirmation" label="Confirma parola" type="password" autocomplete="new-password" viewable required /></div>
                    <flux:checkbox wire:model="data_processing_consent" label="Sunt de acord cu prelucrarea datelor personale." required />
                    <flux:text>Ai deja cont? <flux:link :href="route('login')">Autentificare</flux:link></flux:text>
                </x-center-form-section>
            @endguest
            <x-center-form-section title="Datele centrului" description="Datele de identificare si contact ale centrului ecvestru.">
                <div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="legal_name" label="Denumire centru ecvestru" required /><flux:input wire:model="address" label="Adresa" required /><flux:input wire:model="phone" label="Telefon" type="tel" required /><flux:input wire:model="email" label="E-mail" type="email" required /><flux:input wire:model="website" label="Website" type="url" placeholder="https://" required /><flux:input wire:model="answers.facebook_page" label="Pagina de Facebook" type="url" placeholder="https://" required /><flux:input wire:model="answers.legal_representative" label="Reprezentant legal (Administrator, Manager, Proprietar)" required /><flux:input wire:model="answers.founded_year" label="Anul infiintarii" type="number" min="1800" :max="now()->year" required /></div>
                <flux:textarea wire:model="answers.center_story" label="Scrieti pe scurt povestea centrului" rows="4" />
            </x-center-form-section>
            <x-center-form-section title="Cai si adapostire" description="Efectivul, conditiile de adapostire si ingrijirea ecvideelor.">
                <div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="answers.horses_total" label="Numarul total de cai" type="number" min="0" required /><flux:textarea wire:model="answers.horses_breakdown" label="Dintre cai: iepe, armasari, masculi castrati si manji" required /></div>
                <div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="answers.ponies_total" label="Numarul total de ponei" type="number" min="0" required /><flux:textarea wire:model="answers.ponies_breakdown" label="Dintre ponei: iepe, armasari, masculi castrati si manji" required /></div>
                <x-center-checkbox-group title="Sistemul de adapostire" name="housing_systems" :options="$groups['housing_systems']" other="housing_other" />
                <flux:textarea wire:model="answers.average_housing_area" label="Suprafata medie alocata unui cal in adapost si unui ponei" required />
                <x-center-checkbox-group title="Tipul de asternut" name="bedding_types" :options="$groups['bedding_types']" other="bedding_other" />
                <x-center-checkbox-group title="Spatiile de miscare ale cailor" name="movement_spaces" :options="$groups['movement_spaces']" other="movement_other" />
                <flux:textarea wire:model="answers.feeding_description" label="Modul de furajare al cailor" rows="4" required /><flux:textarea wire:model="answers.shoeing_description" label="Frecventa potcovitului si tipul de potcoave folosite" rows="3" required />
            </x-center-form-section>
            <x-center-form-section title="Resurse umane">
                <flux:input wire:model="answers.employees_total" label="Numarul total de angajati implicati in activitatea ecvestra" type="number" min="0" required /><flux:textarea wire:model="answers.employee_roles" label="Rolul angajatilor responsabili de activitatea ecvestra" required /><flux:textarea wire:model="answers.employee_training_level" label="Nivelul de pregatire al persoanelor implicate" required /><flux:textarea wire:model="answers.employee_certifications" label="Cursuri, atestate sau certificari obtinute de fiecare angajat" /><flux:input wire:model="answers.farrier_name" label="Numele potcovarului care se ocupa de cai" required />
            </x-center-form-section>
            <x-center-form-section title="Facilitati">
                <flux:input wire:model="answers.land_area" label="Suprafata totala de teren detinuta de centru" required /><x-center-checkbox-group title="Facilitatile centrului" name="facilities" :options="$groups['facilities']" other="facilities_other" /><flux:textarea wire:model="answers.facility_areas" label="Suprafetele facilitatilor bifate" required /><flux:textarea wire:model="answers.tack_description" label="Descrierea harnasamentului folosit" required /><flux:textarea wire:model="answers.feed_storage" label="Modul de depozitare a furajelor" required /><flux:textarea wire:model="answers.manure_management" label="Modul de gestionare a gunoiului de grajd" required /><flux:textarea wire:model="answers.facilities_notes" label="Alte informatii despre facilitati" />
            </x-center-form-section>
            <x-center-form-section title="Servicii si siguranta">
                <x-center-checkbox-group title="Serviciile oferite in domeniul ecvestru" name="services" :options="$groups['services']" other="services_other" /><x-center-checkbox-group title="Durata turelor calare (daca oferiti ture)" name="ride_durations" :options="$groups['ride_durations']" other="ride_duration_other" /><x-center-checkbox-group title="Servicii conexe" name="related_services" :options="$groups['related_services']" other="related_services_other" /><flux:textarea wire:model="answers.services_notes" label="Alte informatii despre servicii" />
                <div class="overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700"><div class="grid grid-cols-[1fr_90px_90px] bg-emerald-50 px-4 py-3 text-sm font-semibold dark:bg-emerald-950/40"><span>Completati afirmatiile</span><span class="text-center">Da</span><span class="text-center">Nu</span></div>@foreach (['mixed_stallions_and_mares' => 'Folosesc atat iepe, cat si armasari in grupurile de cai.', 'mares_with_foals' => 'Folosesc iepe cu manji la lectii, plimbari sau ture.', 'rider_weight_limit' => 'Tin cont de o limita de greutate acceptata pentru calareti.'] as $key => $label)<div class="grid grid-cols-[1fr_90px_90px] items-center border-t border-zinc-200 px-4 py-3 dark:border-zinc-700"><span class="text-sm">{{ $label }}</span><label class="text-center"><input type="radio" wire:model="answers.{{ $key }}" value="da" required></label><label class="text-center"><input type="radio" wire:model="answers.{{ $key }}" value="nu" required></label></div>@endforeach</div>
                <div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="answers.max_riders_per_ride" label="Numarul maxim de calareti intr-o tura" type="number" min="1" required /><flux:input wire:model="answers.max_riders_per_professional" label="Numarul maxim de calareti per monitor / instructor / ghid" type="number" min="1" required /></div><flux:textarea wire:model="answers.safety_measures" label="Masurile de siguranta pentru calareti" rows="4" required /><flux:textarea wire:model="answers.insurance_types" label="Tipurile de asigurari detinute" required /><x-center-checkbox-group title="Facilitati pentru calareti" name="rider_facilities" :options="$groups['rider_facilities']" other="rider_facilities_other" /><flux:textarea wire:model="answers.other_information" label="Alte informatii" />
            </x-center-form-section>
            <x-center-form-section title="Nevoi, asteptari si recomandari">
                <flux:textarea wire:model="answers.needs_challenges" label="Principalele nevoi si provocari in domeniul ecvestru" rows="4" required /><flux:textarea wire:model="answers.frte_expectations" label="Asteptarile legate de activitatea Federatiei Romane de Turism Ecvestru" rows="4" required /><flux:textarea wire:model="answers.recommending_centers" label="Cele doua centre ecvestre membre FRTE care va recomanda" description="Scrieti denumirile ambelor centre." required /><div class="grid gap-5 md:grid-cols-2"><flux:input wire:model="answers.questionnaire_contact_name" label="Persoana care completeaza chestionarul (nume si prenume)" required /><flux:input wire:model="answers.questionnaire_contact_phone" label="Numar de telefon" type="tel" required /></div><flux:textarea wire:model="applicant_notes" label="Observatii suplimentare pentru FRTE" />
            </x-center-form-section>
            <div class="flex flex-col-reverse gap-3 pb-10 sm:flex-row sm:justify-end">@auth<flux:button type="button" wire:click="saveDraft" variant="ghost">Salveaza ciorna</flux:button>@endauth<flux:button type="submit" variant="primary">@auth Trimite catre FRTE @else Creeaza contul si trimite catre FRTE @endauth</flux:button></div>
    </form>
</div>
