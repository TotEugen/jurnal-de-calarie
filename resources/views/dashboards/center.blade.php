<x-layouts::app title="Panou Centru">
    @php $center = auth()->user()->centerMemberships()->with('center')->latest()->first()?->center; @endphp
    <div class="mx-auto w-full max-w-7xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700"><flux:text class="font-medium text-emerald-700">Centru de echitație</flux:text><flux:heading size="xl" class="mt-1">{{ $center?->legal_name ?? 'Panoul centrului' }}</flux:heading><flux:text class="mt-2">Gestionează validările, echipa profesională și activitățile centrului.</flux:text></header>
        <section class="grid gap-px overflow-hidden rounded-lg border border-zinc-200 bg-zinc-200 md:grid-cols-4 dark:border-zinc-700 dark:bg-zinc-700">
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Călăreți în așteptare</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->riderMemberships()->where('status', 'pending')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Călăreți activi</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->riderMemberships()->where('status', 'active')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Monitori afiliați</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->professionalAffiliations()->where('status', 'active')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Statut</flux:text><p class="mt-2 text-lg font-semibold">{{ $center?->affiliation_status?->value ?? 'Neafiliat' }}</p></div>
        </section>
        <section class="grid gap-8 border-t border-zinc-200 pt-8 md:grid-cols-3 dark:border-zinc-700"><div><flux:heading size="lg">Validări călăreți</flux:heading><flux:text class="mt-2">Analizează cererile adulților și ale tutorilor pentru minori.</flux:text></div><div><flux:heading size="lg">Monitori</flux:heading><flux:text class="mt-2">Administrează afilierile profesioniștilor autorizați.</flux:text></div><div><flux:heading size="lg">Activități</flux:heading><flux:text class="mt-2">Consultă activitățile înregistrate în cadrul centrului.</flux:text></div></section>
    </div>
</x-layouts::app>
