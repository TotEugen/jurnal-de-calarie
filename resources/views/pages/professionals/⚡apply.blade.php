<?php

use App\Enums\CenterApplicationStatus;
use App\Models\ProfessionalApplication;
use App\Models\ProfessionalProfile;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.public'), Title('Cerere autorizare monitor')] class extends Component {
    public string $qualification_grade = '';
    public string $qualification_identifier = '';
    public string $passport_number = '';
    public string $qualification_obtained_at = '';
    public string $issuing_authority = '';
    public bool $requests_evaluator_authorization = false;
    public string $applicant_notes = '';

    public function mount(): void
    {
        if (! Auth::check()) {
            return;
        }

        $application = ProfessionalApplication::query()
            ->where('submitted_by', Auth::id())
            ->where('status', CenterApplicationStatus::Draft)
            ->latest()->first();

        if ($application) {
            $this->fill($application->only([
                'qualification_grade', 'qualification_identifier', 'passport_number',
                'issuing_authority', 'requests_evaluator_authorization', 'applicant_notes',
            ]));
            $this->qualification_obtained_at = $application->qualification_obtained_at->format('Y-m-d');
        }
    }

    public function saveDraft(): void
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('professionals.apply'));
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->persist(CenterApplicationStatus::Draft);
        Flux::toast(variant: 'success', text: 'Ciorna monitorului a fost salvată.');
    }

    public function submitApplication(): void
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('professionals.apply'));
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $application = $this->persist(CenterApplicationStatus::Submitted);
        $application->update(['submitted_at' => now()]);
        Flux::toast(variant: 'success', text: 'Cererea a fost transmisă Federației.');
        $this->redirectRoute('dashboard', navigate: true);
    }

    private function persist(CenterApplicationStatus $status): ProfessionalApplication
    {
        $data = $this->validate([
            'qualification_grade' => ['required', 'string', 'max:150'],
            'qualification_identifier' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:100'],
            'qualification_obtained_at' => ['required', 'date', 'before_or_equal:today'],
            'issuing_authority' => ['nullable', 'string', 'max:255'],
            'requests_evaluator_authorization' => ['boolean'],
            'applicant_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        return DB::transaction(function () use ($data, $status): ProfessionalApplication {
            $profile = ProfessionalProfile::query()->firstOrCreate(['user_id' => Auth::id()]);
            $application = ProfessionalApplication::query()
                ->where('professional_profile_id', $profile->id)
                ->where('status', CenterApplicationStatus::Draft)
                ->latest()->first();

            $application ??= new ProfessionalApplication([
                'professional_profile_id' => $profile->id,
                'submitted_by' => Auth::id(),
            ]);
            $application->fill($data + ['status' => $status])->save();

            return $application;
        });
    }
}; ?>

<div class="mx-auto w-full max-w-5xl space-y-8">
    <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
        <flux:heading size="xl">Cerere de autorizare monitor</flux:heading>
        <flux:text class="mt-2 max-w-3xl">Completează informațiile profesionale care vor fi verificate de Federație.</flux:text>
    </header>

    @guest
        <section class="border-y border-zinc-200 py-10 dark:border-zinc-700">
            <flux:heading size="lg">Autentificare necesară pentru trimitere</flux:heading>
            <flux:text class="mt-2 max-w-2xl">Condițiile formularului sunt publice, iar dosarul profesional se salvează într-un cont securizat.</flux:text>
            <div class="mt-5 flex gap-3"><flux:button :href="route('login')" variant="primary">Autentificare</flux:button><flux:button :href="route('register')">Creează cont</flux:button></div>
        </section>
    @else
    <form wire:submit="submitApplication" class="space-y-8">
        <div class="grid gap-5 md:grid-cols-2">
            <flux:input wire:model="qualification_grade" label="Grad / calificare" required />
            <flux:input wire:model="qualification_identifier" label="Identificator calificare" />
            <flux:input wire:model="passport_number" label="Număr pașaport profesional" />
            <flux:input wire:model="qualification_obtained_at" label="Data obținerii calificării" type="date" required />
            <div class="md:col-span-2"><flux:input wire:model="issuing_authority" label="Instituția emitentă" /></div>
        </div>
        <flux:checkbox wire:model="requests_evaluator_authorization" label="Solicit și autorizare ca evaluator" />
        <flux:textarea wire:model="applicant_notes" label="Observații pentru Federație" rows="4" />
        <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:justify-end dark:border-zinc-700">
            <flux:button type="button" wire:click="saveDraft" variant="ghost">Salvează ciorna</flux:button>
            <flux:button type="submit" variant="primary">Trimite către Federație</flux:button>
        </div>
    </form>
    @endguest
</div>
