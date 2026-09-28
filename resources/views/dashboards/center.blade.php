<x-layouts::app title="Panou Centru">
    @php $center = auth()->user()->centerMemberships()->with('center')->latest()->first()?->center; @endphp
    <div class="mx-auto w-full max-w-7xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700"><flux:text class="font-medium text-emerald-700">Centru de echitatie</flux:text><flux:heading size="xl" class="mt-1">{{ $center?->legal_name ?? 'Panoul centrului' }}</flux:heading><flux:text class="mt-2">Gestioneaza validarile, echipa profesionala si activitatile centrului.</flux:text></header>
        <section class="grid gap-px overflow-hidden rounded-lg border border-zinc-200 bg-zinc-200 md:grid-cols-4 dark:border-zinc-700 dark:bg-zinc-700">
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Calareti in asteptare</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->riderMemberships()->where('status', 'pending')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Calareti activi</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->riderMemberships()->where('status', 'active')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Monitori afiliati</flux:text><p class="mt-2 text-2xl font-semibold">{{ $center?->professionalAffiliations()->where('status', 'active')->count() ?? 0 }}</p></div>
            <div class="bg-white p-5 dark:bg-zinc-900"><flux:text>Statut</flux:text><p class="mt-2 text-lg font-semibold">{{ $center?->affiliation_status?->value ?? 'Neafiliat' }}</p></div>
        </section>
        <section class="grid gap-8 border-t border-zinc-200 pt-8 md:grid-cols-3 dark:border-zinc-700"><div><flux:heading size="lg">Validari calareti</flux:heading><flux:text class="mt-2">Analizeaza cererile adultilor si ale tutorilor pentru minori.</flux:text></div><div><flux:heading size="lg">Monitori</flux:heading><flux:text class="mt-2">Administreaza afilierile profesionistilor autorizati.</flux:text></div><div><flux:heading size="lg">Activitati</flux:heading><flux:text class="mt-2">Consulta activitatile inregistrate in cadrul centrului.</flux:text></div></section>
    </div>
</x-layouts::app>
