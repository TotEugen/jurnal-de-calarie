<x-layouts::auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="Confirma accesul"
            description="Pentru siguranta, confirma parola sau foloseste un cod primit pe email."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                label="Parola contului"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                Continua
            </flux:button>
        </form>

        <div class="flex items-center gap-3 text-sm text-zinc-500"><span class="h-px flex-1 bg-zinc-300 dark:bg-zinc-700"></span>sau foloseste un cod<span class="h-px flex-1 bg-zinc-300 dark:bg-zinc-700"></span></div>

        <div>
            <form method="POST" action="{{ route('security.code.send') }}">
                @csrf
                <flux:button type="submit" variant="outline" class="w-full">Cod pe email</flux:button>
            </form>
        </div>

        @if (session('code_sent'))
            <form method="POST" action="{{ route('security.code.confirm') }}" class="space-y-4">
                @csrf
                <flux:input name="code" label="Cod de securitate" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required autofocus />
                @error('code')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                <flux:button type="submit" variant="primary" class="w-full">Confirma codul</flux:button>
            </form>
        @endif
    </div>
</x-layouts::auth>
