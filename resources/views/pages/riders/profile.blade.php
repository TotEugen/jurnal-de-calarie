<x-layouts::app title="Editeaza profilul calaretului">
    @php
        $isOwnProfile = $riderProfile->user_id === auth()->id();
        $cancelRoute = $isOwnProfile ? route('profile.edit') : route('guardian.dashboard');
    @endphp

    <div class="mx-auto w-full max-w-2xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">{{ $isOwnProfile ? 'Profilul meu' : 'Profil administrat' }}</flux:text>
            <flux:heading size="xl" class="mt-1">Editeaza profilul calaretului</flux:heading>
            <flux:text class="mt-2">Actualizeaza datele personale ale calaretului. Datele ecvestre sunt gestionate separat de monitori si federatie.</flux:text>
        </header>

        <form method="POST" action="{{ route('riders.profile.update', $riderProfile) }}" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900" x-data="{ hadPhysicalJournal: @js((bool) old('had_physical_journal', $riderProfile->had_physical_journal)) }">
            @csrf
            @method('PATCH')

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:input name="first_name" label="Nume" :value="old('first_name', $riderProfile->first_name)" required autocomplete="family-name" />
                <flux:input name="last_name" label="Prenume" :value="old('last_name', $riderProfile->last_name)" required autocomplete="given-name" />
            </div>

            <flux:input name="birth_date" label="Data nasterii" :value="old('birth_date', $riderProfile->birth_date->format('Y-m-d'))" type="date" required />

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:input name="phone" label="Telefon" :value="old('phone', $riderProfile->phone)" type="tel" autocomplete="tel" />
                <flux:input name="contact_email" label="Email" :value="old('contact_email', $riderProfile->contact_email)" type="email" autocomplete="email" />
            </div>

            <flux:text class="text-sm">Telefonul si emailul sunt optionale pana la implinirea varstei de 18 ani.</flux:text>

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:input name="emergency_contact_name" label="Nume persoana contact de urgenta" :value="old('emergency_contact_name', $riderProfile->emergency_contact_name)" required autocomplete="name" />
                <flux:input name="emergency_contact_phone" label="Telefon persoana contact de urgenta" :value="old('emergency_contact_phone', $riderProfile->emergency_contact_phone)" type="tel" required autocomplete="tel" />
            </div>

            <flux:checkbox name="had_physical_journal" value="1" x-model="hadPhysicalJournal" label="Am mai avut un jurnal de calarie in format fizic." />

            <div x-show="hadPhysicalJournal" x-cloak class="space-y-5 rounded-lg border border-zinc-200 p-5 dark:border-zinc-700">
                <flux:heading size="lg">Date jurnal fizic</flux:heading>
                <flux:select name="physical_journal_issuing_center" label="Centru emitent Jurnal de Calarie" :value="old('physical_journal_issuing_center', $riderProfile->physical_journal_issuing_center)" x-bind:required="hadPhysicalJournal">
                    <flux:select.option value="">Selecteaza centrul afiliat</flux:select.option>
                    @foreach (config('frte.affiliated_centers') as $center)
                        <flux:select.option :value="$center">{{ $center }}</flux:select.option>
                    @endforeach
                </flux:select>
                <div class="grid gap-6 sm:grid-cols-2">
                    <flux:input name="physical_journal_series" label="Serie Jurnal" :value="old('physical_journal_series', $riderProfile->physical_journal_series)" x-bind:required="hadPhysicalJournal" />
                    <flux:input name="physical_journal_rider_code" label="Cod calaret" :value="old('physical_journal_rider_code', $riderProfile->physical_journal_rider_code)" x-bind:required="hadPhysicalJournal" />
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                <flux:button :href="$cancelRoute" variant="ghost" wire:navigate>Anuleaza</flux:button>
                <flux:button type="submit" variant="primary">Salveaza profilul</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
