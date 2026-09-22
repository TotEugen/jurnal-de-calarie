<x-layouts.public>
    <div class="mx-auto max-w-7xl space-y-16">
        <section class="grid min-h-[60vh] items-center gap-10 border-b border-zinc-200 pb-12 lg:grid-cols-[1.4fr_0.6fr] dark:border-zinc-800">
            <div>
                <p class="font-medium text-emerald-700 dark:text-emerald-400">Federația Română de Turism Ecvestru</p>
                <h1 class="mt-4 max-w-4xl text-5xl font-semibold leading-tight text-zinc-900 md:text-6xl dark:text-white">Jurnal de Călărie</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-600 dark:text-zinc-300">Parcursul ecvestru al călărețului, validat de centre și monitori autorizați, într-un registru digital unic.</p>
                <div class="mt-8 flex flex-wrap gap-3"><flux:button :href="route('register')" variant="primary">Creează cont de călăreț</flux:button><flux:button :href="route('login')">Autentificare</flux:button></div>
            </div>
            <div class="flex justify-center lg:justify-end"><img src="/images/logo-frte.png" alt="FRTE" class="w-full max-w-xs object-contain" /></div>
        </section>

        <section class="grid gap-px overflow-hidden rounded-lg border border-zinc-200 bg-zinc-200 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-700">
            <a href="{{ route('centers.apply') }}" class="bg-white p-8 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-xl font-semibold">Afiliere centru de echitație</p><p class="mt-3 text-zinc-600 dark:text-zinc-400">Consultă și completează formularul public pentru autorizarea și publicarea centrului.</p><p class="mt-6 font-medium text-emerald-700">Deschide formularul →</p></a>
            <a href="{{ route('professionals.apply') }}" class="bg-white p-8 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-xl font-semibold">Autorizare monitor</p><p class="mt-3 text-zinc-600 dark:text-zinc-400">Transmite calificarea, identificatorii profesionali și solicitarea de evaluator.</p><p class="mt-6 font-medium text-emerald-700">Deschide formularul →</p></a>
        </section>

        <section class="grid gap-8 border-t border-zinc-200 py-10 md:grid-cols-4 dark:border-zinc-800">
            <div><h2 class="font-semibold">Federație</h2><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Autorizează și administrează registrele naționale.</p></div>
            <div><h2 class="font-semibold">Centre</h2><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Validează călăreți și gestionează echipa profesională.</p></div>
            <div><h2 class="font-semibold">Monitori</h2><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Consemnează activități și progresul călăreților.</p></div>
            <div><h2 class="font-semibold">Călăreți</h2><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Își păstrează parcursul unic în toate centrele.</p></div>
        </section>
    </div>
</x-layouts.public>
