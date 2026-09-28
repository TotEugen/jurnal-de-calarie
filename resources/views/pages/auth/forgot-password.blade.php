<x-layouts::auth title="Recupereaza contul">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Ai uitat parola?" description="Introdu adresa de email pentru a primi codul de recuperare." />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('recovery.send') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="email"
                label="Email"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@exemplu.ro"
                :value="old('email')"
            />
            @error('email')
                <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-100">{{ $message }}</div>
            @enderror

            <flux:button variant="primary" type="submit" class="w-full">
                Trimite codul
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <flux:link :href="route('login')" wire:navigate>Inapoi la autentificare</flux:link>
        </div>
    </div>
</x-layouts::auth>
