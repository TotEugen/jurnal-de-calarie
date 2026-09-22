<?php

use App\Enums\CenterApplicationStatus;
use App\Models\CenterApplication;
use App\Models\ProfessionalApplication;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Cereri Federație')] class extends Component {
    public function mount(): void
    {
        Gate::authorize('access-federation');
    }

    public function with(): array
    {
        $visibleStatuses = [
            CenterApplicationStatus::Submitted,
            CenterApplicationStatus::UnderReview,
            CenterApplicationStatus::Resubmitted,
            CenterApplicationStatus::ChangesRequested,
        ];

        return [
            'centerApplications' => CenterApplication::query()
                ->with(['center', 'submitter'])
                ->whereIn('status', $visibleStatuses)
                ->latest('submitted_at')->get(),
            'professionalApplications' => ProfessionalApplication::query()
                ->with(['professional.user', 'submitter'])
                ->whereIn('status', $visibleStatuses)
                ->latest('submitted_at')->get(),
        ];
    }

}; ?>

<div class="mx-auto w-full max-w-7xl space-y-10">
    <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
        <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Federație</flux:text>
        <flux:heading size="xl" class="mt-1">Validări și autorizări</flux:heading>
        <flux:text class="mt-2">Canalele pentru centre și monitori sunt separate, cu decizii independente.</flux:text>
    </header>

    <section class="space-y-4">
        <div class="flex items-end justify-between"><div><flux:heading size="lg">Cereri centre</flux:heading><flux:text>Afiliere și publicare în lista centrelor autorizate.</flux:text></div><span class="text-sm font-medium">{{ $centerApplications->count() }} active</span></div>
        <div class="overflow-x-auto border-y border-zinc-200 dark:border-zinc-700">
            <table class="w-full min-w-[780px] text-left text-sm">
                <thead class="text-zinc-500"><tr><th class="py-3 pr-4">Centru</th><th class="px-4 py-3">Localitate</th><th class="px-4 py-3">Status</th><th class="py-3 pl-4 text-right">Acțiuni</th></tr></thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($centerApplications as $application)
                        <tr><td class="py-4 pr-4 font-medium">{{ $application->center->legal_name }}</td><td class="px-4 py-4">{{ $application->center->locality }}, {{ $application->center->county }}</td><td class="px-4 py-4">{{ $application->status->value }}</td><td class="py-4 pl-4 text-right"><flux:button size="sm" :href="route('federation.review', ['type' => 'centru', 'applicationId' => $application->id])" wire:navigate>Deschide dosarul</flux:button></td></tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-zinc-500">Nu există cereri active pentru centre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="space-y-4 border-t border-zinc-200 pt-8 dark:border-zinc-700">
        <div class="flex items-end justify-between"><div><flux:heading size="lg">Cereri monitori</flux:heading><flux:text>Calificări profesionale și drepturi de evaluator.</flux:text></div><span class="text-sm font-medium">{{ $professionalApplications->count() }} active</span></div>
        <div class="overflow-x-auto border-y border-zinc-200 dark:border-zinc-700">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="text-zinc-500"><tr><th class="py-3 pr-4">Solicitant</th><th class="px-4 py-3">Grad</th><th class="px-4 py-3">Pașaport</th><th class="px-4 py-3">Data calificării</th><th class="px-4 py-3">Evaluator</th><th class="py-3 pl-4 text-right">Acțiuni</th></tr></thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($professionalApplications as $application)
                        <tr><td class="py-4 pr-4 font-medium">{{ $application->submitter->name }}</td><td class="px-4 py-4">{{ $application->qualification_grade }}</td><td class="px-4 py-4">{{ $application->passport_number ?: '—' }}</td><td class="px-4 py-4">{{ $application->qualification_obtained_at->format('d.m.Y') }}</td><td class="px-4 py-4">{{ $application->requests_evaluator_authorization ? 'Da' : 'Nu' }}</td><td class="py-4 pl-4 text-right"><flux:button size="sm" :href="route('federation.review', ['type' => 'monitor', 'applicationId' => $application->id])" wire:navigate>Deschide dosarul</flux:button></td></tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-zinc-500">Nu există cereri active pentru monitori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
