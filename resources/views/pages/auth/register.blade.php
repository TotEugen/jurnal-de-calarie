<x-layouts.public>
    <div class="mx-auto w-full max-w-4xl">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-800">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Jurnal de Călărie</flux:text>
            <flux:heading size="xl" class="mt-1">Creează cont călăreț</flux:heading>
            <flux:text class="mt-2">Completează datele de mai jos pentru înregistrarea unui nou călăreț.</flux:text>
        </header>

        <form
            method="POST"
            action="{{ route('register.store') }}"
            class="mt-8 space-y-8"
            @submit="if (isMinor() && !guardianSaved) { $event.preventDefault(); guardianModalOpen = true; guardianError = 'Completează și salvează datele tutorelui înainte de a continua.'; }"
            x-data="{
                birthDate: @js(old('birth_date', '')),
                guardianModalOpen: @js($errors->hasAny(['guardian_first_name', 'guardian_last_name', 'guardian_age', 'guardian_phone', 'guardian_email', 'guardian_relationship'])),
                guardianSaved: @js((bool) old('guardian_first_name')),
                guardianError: '',
                guardianFirstName: @js(old('guardian_first_name', '')),
                guardianLastName: @js(old('guardian_last_name', '')),
                guardianAge: @js(old('guardian_age', '')),
                guardianPhone: @js(old('guardian_phone', '')),
                guardianEmail: @js(old('guardian_email', '')),
                guardianRelationship: @js(old('guardian_relationship', '')),
                isMinor() {
                    if (!this.birthDate) return false;
                    const birth = new Date(`${this.birthDate}T00:00:00`);
                    const today = new Date();
                    let age = today.getFullYear() - birth.getFullYear();
                    const beforeBirthday = today.getMonth() < birth.getMonth()
                        || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate());
                    return age - (beforeBirthday ? 1 : 0) < 18;
                },
                birthDateChanged() {
                    if (this.isMinor()) {
                        this.guardianModalOpen = true;
                    } else {
                        this.guardianModalOpen = false;
                        this.guardianSaved = false;
                    }
                },
                saveGuardian() {
                    if (!this.guardianFirstName || !this.guardianLastName || !this.guardianAge
                        || Number(this.guardianAge) < 18 || !this.guardianPhone
                        || !this.guardianEmail || !this.guardianEmail.includes('@') || !this.guardianRelationship) {
                        this.guardianError = 'Completează toate datele tutorelui. Vârsta trebuie să fie de cel puțin 18 ani.';
                        return;
                    }
                    this.guardianError = '';
                    this.guardianSaved = true;
                    this.guardianModalOpen = false;
                },
            }"
        >
            @csrf

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date personale</flux:heading></div>
                <flux:input name="last_name" label="Nume" :value="old('last_name')" required autofocus autocomplete="family-name" />
                <flux:input name="first_name" label="Prenume" :value="old('first_name')" required autocomplete="given-name" />
                <div>
                    <flux:input name="birth_date" label="Data nașterii" x-model="birthDate" @change="birthDateChanged()" type="date" required autocomplete="bday" />

                    <div x-show="isMinor() && guardianSaved" x-cloak class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm dark:border-emerald-800 dark:bg-emerald-950/50">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-emerald-900 dark:text-emerald-100">Datele tutorelui</p>
                                <p class="mt-1 text-emerald-800 dark:text-emerald-200" x-text="`${guardianFirstName} ${guardianLastName} · ${guardianAge} ani`"></p>
                                <p class="text-emerald-800 dark:text-emerald-200" x-text="`${guardianPhone} · ${guardianEmail}`"></p>
                            </div>
                            <button type="button" class="font-medium text-emerald-700 underline dark:text-emerald-300" @click="guardianModalOpen = true">Editează</button>
                        </div>
                    </div>
                </div>
                <flux:input name="phone" label="Telefon" :value="old('phone')" type="tel" x-bind:required="!isMinor()" autocomplete="tel" />
                <div class="md:col-span-2">
                    <flux:input name="email" label="Email" :value="old('email')" type="email" x-bind:required="!isMinor()" autocomplete="email" placeholder="email@exemplu.ro" />
                    <div x-show="isMinor()" x-cloak class="mt-3 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950/50 dark:text-amber-100">
                        Telefonul și emailul călărețului sunt opționale pentru minori. Vor putea fi adăugate ulterior din profil, iar după împlinirea vârstei de 18 ani va fi afișată o atenționare pentru activarea accesului propriu.
                    </div>
                </div>
            </section>

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date cont</flux:heading></div>
                <flux:text x-show="isMinor()" x-cloak class="md:col-span-2">Pentru un minor, parola este parola contului părintelui sau tutorelui. Dacă acel cont există deja, introdu parola lui actuală.</flux:text>
                <flux:input name="password" label="Parolă" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
                <flux:input name="password_confirmation" label="Confirmă parola" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
            </section>

            <section class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Declarații</flux:heading>
                <flux:checkbox name="had_physical_journal" value="1" :checked="old('had_physical_journal')" label="Am mai avut un jurnal de călărie în format fizic." />
                <div>
                    <flux:checkbox name="data_processing_consent" value="1" :checked="old('data_processing_consent')" required label="Sunt de acord cu prelucrarea datelor personale." />
                    @error('data_processing_consent')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end dark:border-zinc-800">
                <flux:button type="button" variant="ghost" onclick="history.length > 1 ? history.back() : window.location.href='{{ route('home') }}'">Anulează</flux:button>
                <flux:button type="submit" variant="primary" data-test="register-user-button">Creează cont</flux:button>
            </div>

            <div x-show="guardianModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/60 p-4" @keydown.escape.window="guardianModalOpen = false">
                <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-6 shadow-2xl dark:bg-zinc-900" @click.outside="guardianModalOpen = false">
                    <div>
                        <flux:heading size="lg">Date tutore sau părinte</flux:heading>
                        <flux:text class="mt-2">Călărețul este minor. Completează datele adultului responsabil. Dacă părintele este deja călăreț, folosește emailul contului său existent.</flux:text>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <flux:input name="guardian_last_name" label="Nume" x-model="guardianLastName" autocomplete="family-name" />
                        <flux:input name="guardian_first_name" label="Prenume" x-model="guardianFirstName" autocomplete="given-name" />
                        <flux:input name="guardian_age" label="Vârsta" x-model="guardianAge" type="number" min="18" max="120" />
                        <flux:input name="guardian_phone" label="Telefon" x-model="guardianPhone" type="tel" autocomplete="tel" />
                        <flux:input name="guardian_email" label="Email" x-model="guardianEmail" type="email" autocomplete="email" />
                        <flux:select name="guardian_relationship" label="Relația cu minorul" x-model="guardianRelationship">
                            <flux:select.option value="">Selectează relația</flux:select.option>
                            <flux:select.option value="parent">Părinte</flux:select.option>
                            <flux:select.option value="guardian">Tutore</flux:select.option>
                        </flux:select>
                    </div>

                    <p x-show="guardianError" x-text="guardianError" class="mt-4 text-sm text-red-600"></p>

                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button type="button" variant="ghost" @click="guardianModalOpen = false">Închide</flux:button>
                        <flux:button type="button" variant="primary" @click="saveGuardian()">Salvează datele tutorelui</flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.public>
