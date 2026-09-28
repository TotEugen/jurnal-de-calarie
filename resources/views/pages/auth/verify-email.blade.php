<x-layouts::auth title="Confirma adresa de email">
    <div class="mt-4 flex flex-col gap-6">
        <div class="space-y-2 text-center">
            <flux:heading size="xl">Verifica emailul</flux:heading>
            <flux:text>
                Am trimis un cod de 6 cifre la <strong>{{ auth()->user()->email }}</strong>.
                Introdu codul pentru a activa contul.
            </flux:text>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="rounded-xl border border-emerald-300 bg-emerald-50 p-3 text-center text-sm font-medium text-emerald-900 dark:border-emerald-700 dark:bg-emerald-950 dark:text-emerald-100">
                Un cod nou a fost trimis pe email.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.code') }}" class="space-y-4">
            @csrf
            <flux:input
                name="code"
                label="Cod de confirmare"
                :value="old('code')"
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                placeholder="000000"
                required
                autofocus
            />
            @error('code')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <flux:button type="submit" variant="primary" class="w-full">
                Confirma contul
            </flux:button>
        </form>

        <div class="flex flex-col items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="ghost">
                    Retrimite codul
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    Deconectare
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
