<x-layouts.public>
    <div class="mx-auto w-full max-w-4xl">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-800">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Jurnal de Calarie</flux:text>
            <flux:heading size="xl" class="mt-1">Creeaza cont calaret</flux:heading>
            <flux:text class="mt-2">Completeaza datele de mai jos pentru inregistrarea unui nou calaret.</flux:text>
        </header>

        <form
            method="POST"
            action="{{ route('register.store') }}"
            class="mt-8 space-y-8"
            @submit="if (!consentAccepted) { $event.preventDefault(); consentError = 'Acordul privind prelucrarea datelor personale este obligatoriu pentru crearea contului.'; } else if (isMinor() && !guardianSaved) { $event.preventDefault(); guardianModalOpen = true; guardianError = 'Completeaza si salveaza datele tutorelui inainte de a continua.'; } else if (hadPhysicalJournal && !physicalJournalSaved) { $event.preventDefault(); physicalJournalModalOpen = true; physicalJournalError = 'Completeaza si salveaza datele jurnalului fizic inainte de a continua.'; }"
            x-data="{
                birthDate: @js(old('birth_date', '')),
                guardianModalOpen: @js($errors->hasAny(['guardian_first_name', 'guardian_last_name', 'guardian_age', 'guardian_phone', 'guardian_email', 'guardian_relationship'])),
                guardianCloseConfirmationOpen: false,
                guardianSaved: @js((bool) old('guardian_first_name')),
                guardianError: '',
                guardianFirstName: @js(old('guardian_first_name', '')),
                guardianLastName: @js(old('guardian_last_name', '')),
                guardianAge: @js(old('guardian_age', '')),
                guardianPhone: @js(old('guardian_phone', '')),
                guardianEmail: @js(old('guardian_email', '')),
                guardianRelationship: @js(old('guardian_relationship', '')),
                hadPhysicalJournal: @js((bool) old('had_physical_journal')),
                physicalJournalModalOpen: @js($errors->hasAny(['physical_journal_issuing_center', 'physical_journal_series', 'physical_journal_rider_code'])),
                physicalJournalCloseConfirmationOpen: false,
                physicalJournalSaved: @js((bool) old('physical_journal_issuing_center')),
                physicalJournalError: '',
                physicalJournalIssuingCenter: @js(old('physical_journal_issuing_center', '')),
                physicalJournalSeries: @js(old('physical_journal_series', '')),
                physicalJournalRiderCode: @js(old('physical_journal_rider_code', '')),
                consentAccepted: @js((bool) old('data_processing_consent')),
                consentError: '',
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
                        this.guardianError = 'Completeaza toate datele tutorelui. Varsta trebuie sa fie de cel putin 18 ani.';
                        return;
                    }
                    this.guardianError = '';
                    this.guardianSaved = true;
                    this.guardianModalOpen = false;
                },
                requestCloseGuardian() {
                    this.guardianCloseConfirmationOpen = true;
                },
                closeGuardian() {
                    this.guardianCloseConfirmationOpen = false;
                    this.guardianModalOpen = false;
                    this.guardianError = '';

                    if (!this.guardianSaved) {
                        this.birthDate = '';
                        this.guardianFirstName = '';
                        this.guardianLastName = '';
                        this.guardianAge = '';
                        this.guardianPhone = '';
                        this.guardianEmail = '';
                        this.guardianRelationship = '';
                    }
                },
                physicalJournalChanged() {
                    if (this.hadPhysicalJournal) {
                        this.physicalJournalModalOpen = true;
                    } else {
                        this.physicalJournalModalOpen = false;
                        this.physicalJournalSaved = false;
                    }
                },
                savePhysicalJournal() {
                    if (!this.physicalJournalIssuingCenter || !this.physicalJournalSeries || !this.physicalJournalRiderCode) {
                        this.physicalJournalError = 'Completeaza toate datele jurnalului fizic.';
                        return;
                    }
                    this.physicalJournalError = '';
                    this.physicalJournalSaved = true;
                    this.physicalJournalModalOpen = false;
                },
                requestClosePhysicalJournal() {
                    this.physicalJournalCloseConfirmationOpen = true;
                },
                closePhysicalJournal() {
                    this.physicalJournalCloseConfirmationOpen = false;
                    this.physicalJournalModalOpen = false;
                    this.physicalJournalError = '';

                    if (!this.physicalJournalSaved) {
                        this.hadPhysicalJournal = false;
                        this.physicalJournalIssuingCenter = '';
                        this.physicalJournalSeries = '';
                        this.physicalJournalRiderCode = '';
                    }
                },
            }"
        >
            @csrf

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date personale</flux:heading></div>
                <flux:input name="last_name" label="Nume" :value="old('last_name')" required autofocus autocomplete="family-name" />
                <flux:input name="first_name" label="Prenume" :value="old('first_name')" required autocomplete="given-name" />
                <div>
                    <flux:input name="birth_date" label="Data nasterii" x-model="birthDate" @change="birthDateChanged()" type="date" required autocomplete="bday" />

                    <div x-show="isMinor() && guardianSaved" x-cloak class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm dark:border-emerald-800 dark:bg-emerald-950/50">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-emerald-900 dark:text-emerald-100">Datele tutorelui</p>
                                <p class="mt-1 text-emerald-800 dark:text-emerald-200" x-text="`${guardianFirstName} ${guardianLastName} · ${guardianAge} ani`"></p>
                                <p class="text-emerald-800 dark:text-emerald-200" x-text="`${guardianPhone} · ${guardianEmail}`"></p>
                            </div>
                            <button type="button" class="font-medium text-emerald-700 underline dark:text-emerald-300" @click="guardianModalOpen = true">Editeaza</button>
                        </div>
                    </div>
                </div>
                <flux:input name="phone" label="Telefon" :value="old('phone')" type="tel" x-bind:required="!isMinor()" autocomplete="tel" />
                <div class="md:col-span-2">
                    <flux:input name="email" label="Email" :value="old('email')" type="email" x-bind:required="!isMinor()" autocomplete="email" placeholder="email@exemplu.ro" />
                    <div x-show="isMinor()" x-cloak class="mt-3 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950/50 dark:text-amber-100">
                        Telefonul si emailul calaretului sunt optionale pentru minori. Vor putea fi adaugate ulterior din profil, iar dupa implinirea varstei de 18 ani va fi afisata o atentionare pentru activarea accesului propriu.
                    </div>
                </div>
                <flux:input name="emergency_contact_name" label="Nume persoana contact de urgenta" :value="old('emergency_contact_name')" required autocomplete="name" />
                <flux:input name="emergency_contact_phone" label="Telefon persoana contact de urgenta" :value="old('emergency_contact_phone')" type="tel" required autocomplete="tel" />
            </section>

            <section class="grid gap-6 rounded-lg border border-zinc-200 bg-white p-6 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="md:col-span-2"><flux:heading size="lg">Date cont</flux:heading></div>
                <flux:text x-show="isMinor()" x-cloak class="md:col-span-2">Pentru un minor, parola este parola contului parintelui sau tutorelui. Daca acel cont exista deja, introdu parola lui actuala.</flux:text>
                <flux:input name="password" label="Parola" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
                <flux:input name="password_confirmation" label="Confirma parola" type="password" required autocomplete="new-password" viewable passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" />
            </section>

            <section class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Jurnal fizic</flux:heading>
                <flux:checkbox name="had_physical_journal" value="1" x-model="hadPhysicalJournal" @change="physicalJournalChanged()" label="Am mai avut un jurnal de calarie in format fizic." />

                <div x-show="hadPhysicalJournal && physicalJournalSaved" x-cloak class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm dark:border-emerald-800 dark:bg-emerald-950/50">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-emerald-900 dark:text-emerald-100">Date jurnal fizic</p>
                            <p class="mt-1 text-emerald-800 dark:text-emerald-200" x-text="physicalJournalIssuingCenter"></p>
                            <p class="text-emerald-800 dark:text-emerald-200" x-text="`${physicalJournalSeries} · ${physicalJournalRiderCode}`"></p>
                        </div>
                        <button type="button" class="font-medium text-emerald-700 underline dark:text-emerald-300" @click="physicalJournalModalOpen = true">Editeaza</button>
                    </div>
                </div>
            </section>

            <section class="space-y-5 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Acord privind datele personale</flux:heading>
                <flux:checkbox name="data_processing_consent" value="1" x-model="consentAccepted" @change="if (consentAccepted) consentError = ''" required label="Sunt de acord cu prelucrarea datelor personale. (obligatoriu)" />
                <p x-show="consentError" x-text="consentError" x-cloak class="text-sm text-red-600"></p>
                @error('data_processing_consent')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end dark:border-zinc-800">
                <flux:button type="button" variant="ghost" onclick="history.length > 1 ? history.back() : window.location.href='{{ route('home') }}'">Anuleaza</flux:button>
                <flux:button type="submit" variant="primary" data-test="register-user-button">Creeaza cont</flux:button>
            </div>

            <div x-show="guardianModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/60 p-4" @keydown.escape.window="guardianModalOpen && requestCloseGuardian()">
                <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-6 shadow-2xl dark:bg-zinc-900" @click.outside="requestCloseGuardian()">
                    <div>
                        <flux:heading size="lg">Date tutore sau parinte</flux:heading>
                        <flux:text class="mt-2">Calaretul este minor. Completeaza datele adultului responsabil. Daca parintele este deja calaret, foloseste emailul contului sau existent.</flux:text>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <flux:input name="guardian_last_name" label="Nume" x-model="guardianLastName" autocomplete="family-name" />
                        <flux:input name="guardian_first_name" label="Prenume" x-model="guardianFirstName" autocomplete="given-name" />
                        <flux:input name="guardian_age" label="Varsta" x-model="guardianAge" type="number" min="18" max="120" />
                        <flux:input name="guardian_phone" label="Telefon" x-model="guardianPhone" type="tel" autocomplete="tel" />
                        <flux:input name="guardian_email" label="Email" x-model="guardianEmail" type="email" autocomplete="email" />
                        <flux:select name="guardian_relationship" label="Relatia cu minorul" x-model="guardianRelationship">
                            <flux:select.option value="">Selecteaza relatia</flux:select.option>
                            <flux:select.option value="parent">Parinte</flux:select.option>
                            <flux:select.option value="guardian">Tutore</flux:select.option>
                        </flux:select>
                    </div>

                    <p x-show="guardianError" x-text="guardianError" class="mt-4 text-sm text-red-600"></p>

                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button type="button" variant="ghost" @click="requestCloseGuardian()">Inchide</flux:button>
                        <flux:button type="button" variant="primary" @click="saveGuardian()">Salveaza datele tutorelui</flux:button>
                    </div>
                </div>
            </div>

            <div x-show="guardianCloseConfirmationOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-zinc-950/70 p-4">
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-zinc-900" @click.stop>
                    <flux:heading size="lg">Esti sigur ca vrei sa parasesti formularul?</flux:heading>
                    <flux:text class="mt-3">Toate datele completate pentru tutore vor fi pierdute, iar data nasterii selectata va fi eliminata.</flux:text>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <flux:button type="button" variant="ghost" @click="guardianCloseConfirmationOpen = false">Raman in formular</flux:button>
                        <flux:button type="button" variant="danger" @click="closeGuardian()">Da, parasesc formularul</flux:button>
                    </div>
                </div>
            </div>

            <div x-show="physicalJournalModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/60 p-4" @keydown.escape.window="physicalJournalModalOpen && requestClosePhysicalJournal()">
                <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-6 shadow-2xl dark:bg-zinc-900" @click.outside="requestClosePhysicalJournal()">
                    <div>
                        <flux:heading size="lg">Date jurnal fizic</flux:heading>
                        <flux:text class="mt-2">Completeaza datele jurnalului de calarie emis anterior.</flux:text>
                    </div>

                    <div class="mt-6 grid gap-5">
                        <flux:select name="physical_journal_issuing_center" label="Centru emitent Jurnal de Calarie" x-model="physicalJournalIssuingCenter">
                            <flux:select.option value="">Selecteaza centrul afiliat</flux:select.option>
                            @foreach (config('frte.affiliated_centers') as $center)
                                <flux:select.option :value="$center">{{ $center }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:input name="physical_journal_series" label="Serie Jurnal" x-model="physicalJournalSeries" />
                        <flux:input name="physical_journal_rider_code" label="Cod calaret" x-model="physicalJournalRiderCode" />
                    </div>

                    <p x-show="physicalJournalError" x-text="physicalJournalError" class="mt-4 text-sm text-red-600"></p>

                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button type="button" variant="ghost" @click="requestClosePhysicalJournal()">Inchide</flux:button>
                        <flux:button type="button" variant="primary" @click="savePhysicalJournal()">Salveaza datele jurnalului</flux:button>
                    </div>
                </div>
            </div>

            <div x-show="physicalJournalCloseConfirmationOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-zinc-950/70 p-4">
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-zinc-900" @click.stop>
                    <flux:heading size="lg">Esti sigur ca vrei sa parasesti formularul?</flux:heading>
                    <flux:text class="mt-3">Toate datele completate pentru jurnalul fizic vor fi pierdute daca parasesti aceasta fereastra.</flux:text>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <flux:button type="button" variant="ghost" @click="physicalJournalCloseConfirmationOpen = false">Raman in formular</flux:button>
                        <flux:button type="button" variant="danger" @click="closePhysicalJournal()">Da, parasesc formularul</flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.public>
