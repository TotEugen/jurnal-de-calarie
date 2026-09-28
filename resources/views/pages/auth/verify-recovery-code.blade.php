<x-layouts::auth title="Verifica codul">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Introdu codul primit" description="Codul are 6 cifre si este valabil 10 minute." />
        <x-auth-session-status class="text-center" :status="session('status')" />
        <form method="POST" action="{{ route('recovery.verify.store') }}" class="space-y-5">
            @csrf
            <flux:input name="code" label="Cod de recuperare" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required autofocus />
            @error('code')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            <flux:button type="submit" variant="primary" class="w-full">Verifica codul</flux:button>
        </form>
        <flux:link :href="route('recovery.request')" class="text-center" wire:navigate>Trimite alt cod</flux:link>
    </div>
</x-layouts::auth>
