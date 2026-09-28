<?php

use App\Models\CenterApplication;
use App\Models\ProfessionalApplication;
use App\Services\ApplicationReviewService;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Analiza dosar')] class extends Component {
    public string $type;
    public int $applicationId;
    public string $review_notes = '';

    public function mount(string $type, int $applicationId): void
    {
        Gate::authorize('access-federation');
        abort_unless(in_array($type, ['centru', 'monitor'], true), 404);
        $this->type = $type;
        $this->applicationId = $applicationId;
        $this->review_notes = $this->application()->review_notes ?? '';
    }

    public function with(): array
    {
        return ['application' => $this->application()];
    }

    public function decide(string $decision, ApplicationReviewService $service): void
    {
        Gate::authorize('access-federation');
        $this->validate(['review_notes' => ['nullable', 'string', 'max:3000']]);

        try {
            if ($this->type === 'centru') {
                $service->reviewCenter($this->application(), $decision, Auth::user(), $this->review_notes ?: null);
            } else {
                $service->reviewProfessional($this->application(), $decision, Auth::user(), $this->review_notes ?: null);
            }
        } catch (\InvalidArgumentException $exception) {
            $this->addError('review_notes', $exception->getMessage());
            return;
        }

        Flux::toast(variant: 'success', text: 'Decizia a fost salvata in istoricul dosarului.');
        $this->redirectRoute('federation.applications', navigate: true);
    }

    private function application(): CenterApplication|ProfessionalApplication
    {
        if ($this->type === 'centru') {
            return CenterApplication::query()
                ->with(['center', 'submitter', 'statusHistory.actor'])
                ->findOrFail($this->applicationId);
        }

        return ProfessionalApplication::query()
            ->with(['professional.user', 'submitter', 'statusHistory.actor'])
            ->findOrFail($this->applicationId);
    }
}; ?>

<div class="mx-auto w-full max-w-6xl space-y-8">
    <header class="flex flex-col gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-end sm:justify-between dark:border-zinc-700">
        <div><flux:text class="font-medium text-emerald-700">Federatie · Dosar {{ $type }}</flux:text><flux:heading size="xl" class="mt-1">{{ $type === 'centru' ? $application->center->legal_name : $application->submitter->name }}</flux:heading><flux:text class="mt-2">Status curent: {{ $application->status->value }}</flux:text></div>
        <flux:button :href="route('federation.applications')" variant="ghost" wire:navigate>Inapoi la cereri</flux:button>
    </header>

    @if ($type === 'centru')
        <section class="grid gap-6 md:grid-cols-3"><div><flux:text>Denumire juridica</flux:text><p class="mt-1 font-medium">{{ $application->center->legal_name }}</p></div><div><flux:text>CUI</flux:text><p class="mt-1 font-medium">{{ $application->center->fiscal_code ?: '—' }}</p></div><div><flux:text>Solicitant</flux:text><p class="mt-1 font-medium">{{ $application->submitter->name }}</p></div><div><flux:text>Localitate</flux:text><p class="mt-1 font-medium">{{ $application->center->locality }}, {{ $application->center->county }}</p></div><div><flux:text>E-mail</flux:text><p class="mt-1 font-medium">{{ $application->center->email }}</p></div><div><flux:text>Telefon</flux:text><p class="mt-1 font-medium">{{ $application->center->phone ?: '—' }}</p></div><div class="md:col-span-3"><flux:text>Adresa</flux:text><p class="mt-1 font-medium">{{ $application->center->address }}</p></div></section>
        @if ($application->questionnaire_answers)
            <section class="space-y-5 border-t border-zinc-200 pt-8 dark:border-zinc-700">
                <flux:heading size="lg">Raspunsurile din chestionar</flux:heading>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($application->questionnaire_answers as $question => $answer)
                        <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-950/30">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">{{ str($question)->replace('_', ' ')->headline() }}</p>
                            <p class="mt-2 whitespace-pre-line text-sm">{{ is_array($answer) ? implode(', ', $answer) : ($answer ?: '—') }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <section class="grid gap-6 md:grid-cols-3"><div><flux:text>Grad / calificare</flux:text><p class="mt-1 font-medium">{{ $application->qualification_grade }}</p></div><div><flux:text>Identificator</flux:text><p class="mt-1 font-medium">{{ $application->qualification_identifier ?: '—' }}</p></div><div><flux:text>Pasaport profesional</flux:text><p class="mt-1 font-medium">{{ $application->passport_number ?: '—' }}</p></div><div><flux:text>Data calificarii</flux:text><p class="mt-1 font-medium">{{ $application->qualification_obtained_at->format('d.m.Y') }}</p></div><div><flux:text>Institutie emitenta</flux:text><p class="mt-1 font-medium">{{ $application->issuing_authority ?: '—' }}</p></div><div><flux:text>Solicita evaluator</flux:text><p class="mt-1 font-medium">{{ $application->requests_evaluator_authorization ? 'Da' : 'Nu' }}</p></div></section>
    @endif

    <section class="space-y-4 border-t border-zinc-200 pt-8 dark:border-zinc-700">
        <flux:heading size="lg">Observatiile Federatiei</flux:heading>
        <flux:textarea wire:model="review_notes" label="Observatii pentru solicitant" rows="5" description="Obligatorii cand soliciti completari sau respingi dosarul." />
        <div class="flex flex-wrap gap-3"><flux:button wire:click="decide('review')">Marcheaza in analiza</flux:button><flux:button wire:click="decide('changes')">Solicita completari</flux:button><flux:button variant="primary" wire:click="decide('approve')" wire:confirm="Confirmi aprobarea acestui dosar?">Aproba</flux:button><flux:button variant="danger" wire:click="decide('reject')" wire:confirm="Confirmi respingerea acestui dosar?">Respinge</flux:button></div>
    </section>

    <section class="space-y-4 border-t border-zinc-200 pt-8 dark:border-zinc-700">
        <flux:heading size="lg">Istoric decizii</flux:heading>
        <div class="divide-y divide-zinc-200 border-y border-zinc-200 dark:divide-zinc-700 dark:border-zinc-700">
            @forelse ($application->statusHistory as $history)
                <div class="grid gap-2 py-4 md:grid-cols-[160px_1fr_2fr]"><p class="text-sm">{{ $history->created_at->format('d.m.Y H:i') }}</p><p class="text-sm font-medium">{{ $history->from_status }} → {{ $history->to_status }}</p><div><p class="text-sm">{{ $history->notes ?: 'Fara observatii' }}</p><p class="mt-1 text-xs text-zinc-500">{{ $history->actor->name }}</p></div></div>
            @empty
                <p class="py-6 text-sm text-zinc-500">Dosarul nu are inca decizii inregistrate.</p>
            @endforelse
        </div>
    </section>
</div>
