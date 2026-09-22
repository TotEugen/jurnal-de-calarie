<x-layouts::app title="Editează profilul călărețului">
    @php
        $isOwnProfile = $riderProfile->user_id === auth()->id();
        $cancelRoute = $isOwnProfile ? route('profile.edit') : route('guardian.dashboard');
    @endphp

    <div class="mx-auto w-full max-w-2xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">{{ $isOwnProfile ? 'Profilul meu' : 'Profil administrat' }}</flux:text>
            <flux:heading size="xl" class="mt-1">Editează profilul călărețului</flux:heading>
            <flux:text class="mt-2">Actualizează datele personale ale călărețului. Datele ecvestre sunt gestionate separat de monitori și federație.</flux:text>
        </header>

        <form method="POST" action="{{ route('riders.profile.update', $riderProfile) }}" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @method('PATCH')

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:input name="first_name" label="Nume" :value="old('first_name', $riderProfile->first_name)" required autocomplete="family-name" />
                <flux:input name="last_name" label="Prenume" :value="old('last_name', $riderProfile->last_name)" required autocomplete="given-name" />
            </div>

            <flux:input name="birth_date" label="Data nașterii" :value="old('birth_date', $riderProfile->birth_date->format('Y-m-d'))" type="date" required />

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:input name="phone" label="Telefon" :value="old('phone', $riderProfile->phone)" type="tel" autocomplete="tel" />
                <flux:input name="contact_email" label="Email" :value="old('contact_email', $riderProfile->contact_email)" type="email" autocomplete="email" />
            </div>

            <flux:text class="text-sm">Telefonul și emailul sunt opționale până la împlinirea vârstei de 18 ani.</flux:text>
            <flux:checkbox name="had_physical_journal" value="1" :checked="old('had_physical_journal', $riderProfile->had_physical_journal)" label="Am mai avut un jurnal de călărie în format fizic." />

            <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                <flux:button :href="$cancelRoute" variant="ghost" wire:navigate>Anulează</flux:button>
                <flux:button type="submit" variant="primary">Salvează profilul</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
