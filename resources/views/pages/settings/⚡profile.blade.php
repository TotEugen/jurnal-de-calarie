<?php

use App\Concerns\ProfileValidationRules;
use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Setarile contului')] class extends Component {
    use ProfileValidationRules;
    use PasswordValidationRules;

    public string $name = '';
    public string $email = '';
    public string $current_password = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = $this->profileRules($user->id);

        if ($this->email !== $user->email) {
            $rules['current_password'] = $this->currentPasswordRules();
        }

        $validated = $this->validate($rules);
        unset($validated['current_password']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->reset('current_password');

        Flux::toast(variant: 'success', text: 'Datele contului au fost actualizate.');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">Setarile contului</flux:heading>

    <x-pages::settings.layout heading="Datele contului" subheading="Actualizeaza numele si adresa de email folosita pentru autentificare">
        <form wire:submit="updateProfileInformation" class="my-7 w-full space-y-6">
            <flux:input wire:model="name" label="Nume afisat" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" label="Email" type="email" required autocomplete="email" />

                <div class="mt-8">
                    <flux:input
                        wire:model="current_password"
                        label="Parola curenta"
                        description="Este necesara numai daca schimbi adresa de email. Campul trebuie completat manual."
                        type="password"
                        autocomplete="off"
                        data-1p-ignore
                        data-lpignore="true"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                    />
                </div>

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                                Adresa ta de email nu este verificata.

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                    Retrimite mesajul de verificare.
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                Un nou link de verificare a fost trimis la adresa ta de email.
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end pt-2">
                <div>
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        Salveaza datele contului
                    </flux:button>
                </div>

            </div>
        </form>

        @if (auth()->user()->riderProfile)
            <div class="settings-content-section mt-12 border-t pt-8">
                <flux:heading size="lg">Datele calaretului</flux:heading>
                <flux:text class="mt-2">Poti corecta datele introduse la inregistrare: numele, data nasterii, telefonul, emailul de contact, contactul de urgenta si informatiile despre jurnalul fizic.</flux:text>
                <flux:button class="mt-4" :href="route('riders.profile.edit', auth()->user()->riderProfile)" wire:navigate>
                    Editeaza datele calaretului
                </flux:button>
            </div>
        @endif

        @if (auth()->user()->guardianProfile && ! auth()->user()->riderProfile)
            <div class="settings-content-section mt-12 border-t pt-8">
                <flux:heading size="lg">Vrei sa devii calaret?</flux:heading>
                <flux:text class="mt-2">Adauga profilul de calaret aceluiasi cont. Profilul de parinte si calaretii administrati raman disponibile.</flux:text>
                <flux:button class="mt-4" :href="route('rider.become')" wire:navigate>Devino calaret</flux:button>
            </div>
        @endif

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
