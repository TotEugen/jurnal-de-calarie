<x-layouts::auth :title="__('Log in')">
    @php $guardianLogin = request('role') === 'guardian'; @endphp
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="$guardianLogin ? 'Autentificare tutore / parinte' : 'Autentificare calaret'"
            :description="$guardianLogin ? 'Introdu emailul si parola contului folosit pentru administrarea minorului.' : 'Introdu emailul si parola contului tau de calaret.'"
        />

        <flux:link :href="route('account.choose')" class="text-sm" wire:navigate>← Creeaza un cont nou</flux:link>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('recovery.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('recovery.request')" wire:navigate>
                        Ai uitat parola?
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Autentificare
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
