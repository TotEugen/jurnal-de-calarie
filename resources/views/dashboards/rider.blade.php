<x-layouts::app title="Panou Calaret">
    @php
        $rider = auth()->user()->riderProfile ?? null;
        $currentAward = $rider?->gradeAwards()->with('grade')->get()->sortByDesc(fn ($award) => $award->grade?->rank ?? 0)->first();
        $currentGrade = $currentAward?->grade;
        $currentLevel = $currentGrade?->name ?? 'Fara grad validat';
        $currentCycle = match (true) {
            ($currentGrade?->rank ?? 0) >= 4 => 'Ciclul 2',
            ($currentGrade?->rank ?? 0) >= 1 => 'Ciclul 1',
            default => 'Inceputul parcursului',
        };
    @endphp
    <div class="mx-auto w-full max-w-7xl space-y-8">
        <header class="overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-700 to-lime-500 p-8 text-white shadow-xl"><p class="font-medium text-emerald-100">Calaret</p><flux:heading size="xl" class="mt-1 !text-white">Jurnalul meu de calarie</flux:heading><p class="mt-2 text-emerald-50">Parcurs unic, indiferent de centrul in care desfasori activitatea.</p></header>
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950"><flux:text>Centre active</flux:text><p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-200">{{ $rider?->centerMemberships()->where('status', 'active')->count() ?? 0 }}</p></div>
            <div class="rounded-2xl border border-teal-200 bg-teal-50 p-5 dark:border-teal-900 dark:bg-teal-950"><flux:text>Sesiuni in jurnal</flux:text><p class="mt-2 text-3xl font-bold text-teal-800 dark:text-teal-200">{{ $rider?->sessions()->count() ?? 0 }}</p></div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900 dark:bg-amber-950"><flux:text>In asteptarea confirmarii</flux:text><p class="mt-2 text-3xl font-bold text-amber-800 dark:text-amber-200">{{ $rider?->sessions()->where('status', 'pending_monitor')->count() ?? 0 }}</p></div>
            <div class="rounded-2xl border border-lime-200 bg-lime-50 p-5 dark:border-lime-900 dark:bg-lime-950"><flux:text>Grade obtinute</flux:text><p class="mt-2 text-3xl font-bold text-lime-800 dark:text-lime-200">{{ $rider?->gradeAwards()->count() ?? 0 }}</p></div>
        </section>
        <section class="overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-white to-lime-50 p-6 shadow-sm dark:border-emerald-900 dark:from-emerald-950 dark:via-zinc-900 dark:to-lime-950 sm:p-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Nivel actual</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-950 dark:text-white">{{ $currentLevel }}</p>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">{{ $currentCycle }} · {{ $currentGrade ? 'Etapa '.$currentGrade->rank.' din 6' : 'Prima etapa: Calaret de Bronz' }}</p>
                </div>
                <div class="min-w-64">
                    <div class="flex justify-between text-sm"><span>Progres program</span><span>{{ $currentGrade?->rank ?? 0 }}/6 etape</span></div>
                    <div class="mt-2 h-3 overflow-hidden rounded-full bg-emerald-100 dark:bg-emerald-900">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-600 to-lime-400" style="width: {{ (($currentGrade?->rank ?? 0) / 6) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="grid gap-5 md:grid-cols-3"><a href="{{ route('rider.sessions') }}" class="rounded-3xl bg-emerald-700 p-6 text-white shadow-lg transition hover:-translate-y-1 hover:bg-emerald-800" wire:navigate><p class="text-xl font-bold">Sesiuni si progres</p><p class="mt-2 text-sm text-emerald-100">Adauga experienta de astazi si urmareste confirmarile.</p><p class="mt-6 font-semibold">Deschide jurnalul →</p></a><div class="rounded-3xl border border-teal-200 bg-teal-50 p-6 dark:border-teal-900 dark:bg-teal-950"><flux:heading size="lg">Centre</flux:heading><flux:text class="mt-2">Solicita validarea la un centru nou fara pierderea istoricului.</flux:text></div><div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 dark:border-amber-900 dark:bg-amber-950"><flux:heading size="lg">Grade si diplome</flux:heading><flux:text class="mt-2">Consulta promovarile si documentele emise.</flux:text></div></section>
    </div>
</x-layouts::app>
