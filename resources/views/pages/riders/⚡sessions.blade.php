<?php

use App\Enums\AffiliationStatus;
use App\Models\EquestrianCenter;
use App\Models\RiderProfile;
use App\Models\RiderSession;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app'), Title('Sesiunile mele')] class extends Component {
    public string $session_date = '';
    public string $session_time = '';
    public string $center_name = '';
    public string $duration_minutes = '';
    public array $activity_types = [];
    public string $other_activity = '';
    public string $horse_name = '';
    public string $learned_today = '';
    public string $key_takeaway = '';
    public string $next_experience = '';
    public string $instructor_name = '';
    public int $rating = 0;

    public function mount(): void
    {
        abort_unless(Auth::user()?->riderProfile, 403);
        $this->session_date = now()->format('Y-m-d');
        $this->session_time = now()->format('H:i');
    }

    #[Computed]
    public function centers(): array
    {
        return EquestrianCenter::query()
            ->where('affiliation_status', AffiliationStatus::Active)
            ->orderBy('legal_name')
            ->pluck('legal_name')
            ->merge(config('frte.affiliated_centers', []))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    #[Computed]
    public function sessions()
    {
        return $this->rider()->sessions()->latest('session_date')->latest('session_time')->get();
    }

    public function save(): void
    {
        $data = $this->validate([
            'session_date' => ['required', 'date', 'before_or_equal:today'],
            'session_time' => ['required', 'date_format:H:i'],
            'center_name' => ['required', 'string', 'max:255', 'in:'.implode(',', $this->centers)],
            'duration_minutes' => ['required', 'integer', 'between:1,720'],
            'activity_types' => ['required', 'array', 'min:1'],
            'activity_types.*' => ['in:lesson,trail,other'],
            'other_activity' => [Rule::requiredIf(fn () => in_array('other', $this->activity_types, true)), 'nullable', 'string', 'max:500'],
            'horse_name' => ['required', 'string', 'max:255'],
            'learned_today' => ['required', 'string', 'max:3000'],
            'key_takeaway' => ['required', 'string', 'max:3000'],
            'next_experience' => ['nullable', 'string', 'max:3000'],
            'instructor_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        $rider = $this->rider();
        $center = EquestrianCenter::query()->where('legal_name', $data['center_name'])->first();

        $rider->sessions()->create($data + [
            'equestrian_center_id' => $center?->id,
            'session_number' => ((int) $rider->sessions()->max('session_number')) + 1,
            'status' => 'pending_monitor',
            'submitted_at' => now(),
        ]);

        $this->reset([
            'center_name', 'duration_minutes', 'activity_types', 'other_activity', 'horse_name',
            'learned_today', 'key_takeaway', 'next_experience', 'instructor_name', 'rating',
        ]);
        $this->session_date = now()->format('Y-m-d');
        $this->session_time = now()->format('H:i');
        unset($this->sessions);

        Flux::toast(variant: 'success', text: 'Sesiunea a fost trimisa pentru confirmarea monitorului.');
    }

    private function rider(): RiderProfile
    {
        return RiderProfile::query()->where('user_id', Auth::id())->firstOrFail();
    }
}; ?>

<div class="mx-auto w-full max-w-7xl space-y-8">
    <header class="overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 via-emerald-700 to-teal-500 p-8 text-white shadow-xl">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-100">Jurnalul meu</p>
            <flux:heading size="xl" class="mt-2 !text-white">Sesiuni si progres</flux:heading>
            <p class="mt-3 text-emerald-50">Pastreaza fiecare experienta, apoi urmareste confirmarea monitorului si a centrului.</p>
        </div>
    </header>

    <div class="grid items-start gap-8 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm dark:border-emerald-900 dark:bg-zinc-900 sm:p-8">
            <div class="mb-7 flex items-start justify-between gap-4">
                <div><flux:heading size="lg">Adauga o sesiune</flux:heading><flux:text class="mt-1">Sesiunea va astepta confirmarea unui monitor calificat.</flux:text></div>
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">Sesiunea #{{ ($this->sessions->max('session_number') ?? 0) + 1 }}</span>
            </div>

            <form wire:submit="save" class="space-y-7">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <flux:input wire:model="session_date" label="Data" type="date" required />
                    <flux:input wire:model="session_time" label="Ora" type="time" required />
                    <flux:input wire:model="duration_minutes" label="Durata (minute)" type="number" min="1" max="720" required />
                </div>

                <flux:select wire:model="center_name" label="Centru afiliat" required>
                    <flux:select.option value="">Selecteaza centrul</flux:select.option>
                    @foreach ($this->centers as $center)
                        <flux:select.option :value="$center">{{ $center }}</flux:select.option>
                    @endforeach
                </flux:select>

                <fieldset class="rounded-2xl bg-emerald-50 p-5 dark:bg-emerald-950/40">
                    <legend class="px-1 font-semibold">In ce a constat sesiunea de astazi?</legend>
                    <div class="mt-3 grid items-center gap-3 sm:grid-cols-2 lg:grid-cols-[auto_auto_1fr]">
                        <flux:checkbox wire:model.live="activity_types" value="lesson" label="Lectie in manej" />
                        <flux:checkbox wire:model.live="activity_types" value="trail" label="Plimbare / traseu" />
                        <div class="flex min-w-0 items-center gap-3">
                            <flux:checkbox wire:model.live="activity_types" value="other" label="Altceva" />
                            <input
                                wire:model="other_activity"
                                type="text"
                                aria-label="Descrie alta activitate"
                                placeholder="Scrie activitatea"
                                @disabled(! in_array('other', $activity_types, true))
                                class="min-w-0 flex-1 rounded-lg border border-emerald-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 disabled:cursor-not-allowed disabled:bg-zinc-100 disabled:text-zinc-400 dark:border-emerald-800 dark:bg-zinc-900 dark:text-white dark:focus:ring-emerald-900 dark:disabled:bg-zinc-800"
                            />
                        </div>
                    </div>
                    @error('other_activity') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </fieldset>

                <div class="grid gap-5 sm:grid-cols-2">
                    <flux:input wire:model="horse_name" label="Numele calului" required />
                    <flux:input wire:model="instructor_name" label="Nume monitor / instructor / ghid" required />
                </div>

                <flux:textarea wire:model="learned_today" label="Ce am invatat sau experimentat astazi?" rows="4" required />
                <flux:textarea wire:model="key_takeaway" label="Care este cel mai important lucru retinut astazi?" rows="4" required />
                <flux:textarea wire:model="next_experience" label="Care va fi urmatoarea experienta?" rows="3" />

                <fieldset>
                    <legend class="font-semibold">Cum evaluezi sesiunea?</legend>
                    <div class="mt-3 flex gap-2" role="radiogroup" aria-label="Rating sesiune">
                        @for ($star = 1; $star <= 5; $star++)
                            <button
                                type="button"
                                wire:click="$set('rating', {{ $star }})"
                                class="cursor-pointer text-4xl leading-none transition hover:scale-110 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2"
                                aria-label="Acorda {{ $star }} stele"
                                aria-pressed="{{ $rating === $star ? 'true' : 'false' }}"
                            ><span class="{{ $rating >= $star ? 'text-amber-400' : 'text-zinc-300 dark:text-zinc-600' }}">★</span></button>
                        @endfor
                    </div>
                    @error('rating') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <div class="flex justify-end"><flux:button type="submit" variant="primary">Trimite sesiunea spre confirmare</flux:button></div>
            </form>
        </section>

        <aside class="space-y-4">
            <div class="rounded-3xl bg-emerald-950 p-6 text-white shadow-lg">
                <p class="text-sm text-emerald-200">Progres inregistrat</p>
                <p class="mt-2 text-4xl font-bold">{{ $this->sessions->count() }}</p>
                <p class="mt-1 text-sm text-emerald-100">sesiuni adaugate in jurnal</p>
            </div>

            @forelse ($this->sessions as $session)
                <article class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold">Sesiunea #{{ $session->session_number }}</span>
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">In asteptarea monitorului</span>
                    </div>
                    <p class="mt-3 text-sm font-medium text-emerald-700 dark:text-emerald-400">{{ $session->center_name }}</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ $session->session_date->format('d.m.Y') }} · {{ substr($session->session_time, 0, 5) }} · {{ $session->duration_minutes }} min</p>
                    <p class="mt-3 text-sm">Cal: {{ $session->horse_name }} · {{ $session->rating }}/5 ★</p>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-emerald-300 bg-emerald-50 p-6 text-center dark:border-emerald-800 dark:bg-emerald-950/30">
                    <p class="font-semibold">Prima sesiune incepe aici</p>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Completeaza formularul pentru a incepe istoricul progresului tau.</p>
                </div>
            @endforelse
        </aside>
    </div>
</div>
