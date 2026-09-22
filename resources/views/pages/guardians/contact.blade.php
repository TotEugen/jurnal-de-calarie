<x-layouts::app title="Date de contact călăreț">
    <div class="mx-auto w-full max-w-2xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Profil administrat</flux:text>
            <flux:heading size="xl" class="mt-1">{{ $riderProfile->first_name }} {{ $riderProfile->last_name }}</flux:heading>
            <flux:text class="mt-2">Adaugă datele personale de contact ale călărețului. Acestea rămân separate de datele tutorelui.</flux:text>
        </header>

        <form method="POST" action="{{ route('guardian.riders.contact.update', $riderProfile) }}" class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @method('PATCH')

            <flux:input name="phone" label="Telefon" :value="old('phone', $riderProfile->phone)" type="tel" required autocomplete="tel" />
            <flux:input name="contact_email" label="Email" :value="old('contact_email', $riderProfile->contact_email)" type="email" required autocomplete="email" />

            <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                <flux:button :href="route('guardian.dashboard')" variant="ghost" wire:navigate>Anulează</flux:button>
                <flux:button type="submit" variant="primary">Salvează datele</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
