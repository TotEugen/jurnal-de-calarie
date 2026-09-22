<x-layouts.public>
    <div class="mx-auto w-full max-w-4xl">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-800">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Jurnal de Călărie</flux:text>
            <flux:heading size="xl" class="mt-1">Creează cont călăreț</flux:heading>
            <flux:text class="mt-2">Completează datele de mai jos pentru înregistrarea unui nou călăreț.</flux:text>
        </header>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-8">
            @csrf

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date personale</flux:heading></div>
                <flux:input name="last_name" label="Nume" :value="old('last_name')" required autofocus autocomplete="family-name" />
                <flux:input name="first_name" label="Prenume" :value="old('first_name')" required autocomplete="given-name" />
                <flux:input name="birth_date" label="Data nașterii" :value="old('birth_date')" type="date" required autocomplete="bday" />
                <flux:input name="phone" label="Telefon" :value="old('phone')" type="tel" required autocomplete="tel" />
                <div class="md:col-span-2"><flux:input name="email" label="Email" :value="old('email')" type="email" required autocomplete="email" placeholder="email@exemplu.ro" /></div>
            </section>

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date cont</flux:heading></div>
                <flux:input name="password" label="Parolă" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
                <flux:input name="password_confirmation" label="Confirmă parola" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
            </section>

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date ecvestre</flux:heading></div>
                <flux:select name="equestrian_center_id" label="Centru ecvestru afiliat" required>
                    <flux:select.option value="">Selectează centrul</flux:select.option>
                    @foreach ($centers as $center)
                        <flux:select.option :value="$center->id" :selected="old('equestrian_center_id') == $center->id">{{ $center->legal_name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select name="grade_id" label="Nivel / categorie">
                    <flux:select.option value="">Fără nivel declarat</flux:select.option>
                    @foreach ($grades as $grade)
                        <flux:select.option :value="$grade->id" :selected="old('grade_id') == $grade->id">{{ $grade->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <div class="md:col-span-2">
                    <flux:checkbox name="data_processing_consent" value="1" :checked="old('data_processing_consent')" required label="Sunt de acord cu prelucrarea datelor personale." />
                    @error('data_processing_consent')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end dark:border-zinc-800">
                <flux:button type="button" variant="ghost" onclick="history.length > 1 ? history.back() : window.location.href='{{ route('home') }}'">Anulează</flux:button>
                <flux:button type="submit" variant="primary" data-test="register-user-button">Creează cont</flux:button>
            </div>
        </form>
    </div>
</x-layouts.public>
