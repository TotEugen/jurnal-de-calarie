<x-layouts.public>
    <div class="mx-auto max-w-7xl space-y-12 lg:space-y-16">
        @auth
            @php $rider = auth()->user()->riderProfile; @endphp

            <section class="overflow-hidden rounded-3xl bg-frte-mint px-7 py-12 md:px-12 lg:px-16 dark:bg-[#17231d]">
                <p class="text-sm font-semibold uppercase tracking-wider text-frte-forest dark:text-frte-sage">Bine ai revenit, {{ auth()->user()->name }}</p>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-frte-deep md:text-5xl dark:text-white">Jurnalul tau de calarie</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-zinc-700 dark:text-zinc-300">Consulta responsabilitatile membrilor FRTE si urmareste evolutia activitatii tale ecvestre.</p>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <a href="{{ route('frte.responsibilities') }}" class="group rounded-2xl bg-frte-forest p-8 text-white transition hover:-translate-y-1 hover:bg-frte-active dark:bg-[#1e3a2f] dark:hover:bg-[#28503f]" wire:navigate>
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide text-frte-mint">Ghid FRTE</p>
                            <h2 class="mt-3 text-2xl font-semibold">Responsabilitati membri FRTE</h2>
                            <p class="mt-3 leading-7 text-frte-mint">Reguli de siguranta si etica, interactiunea cu calul si alegerea unui centru ecvestru responsabil.</p>
                        </div>
                        <span class="text-2xl text-frte-mint transition group-hover:translate-x-1">→</span>
                    </div>
                    <p class="mt-8 font-medium text-white">Deschide ghidul</p>
                </a>

                <section class="rounded-2xl border border-frte-sage bg-white p-8 dark:border-frte-border dark:bg-[#17231d]">
                    <p class="text-sm font-medium uppercase tracking-wide text-frte-forest dark:text-frte-sage">Activitatea mea</p>
                    <h2 class="mt-3 text-2xl font-semibold text-frte-deep dark:text-white">Progres</h2>
                    @if ($rider)
                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="rounded-xl bg-frte-mint p-5 dark:bg-[#1e3a2f]"><p class="text-sm text-frte-forest dark:text-frte-sage">Activitati</p><p class="mt-1 text-3xl font-semibold text-frte-deep dark:text-white">{{ $rider->activities()->count() }}</p></div>
                            <div class="rounded-xl bg-frte-paper p-5 dark:bg-[#1e3a2f]"><p class="text-sm text-frte-forest dark:text-frte-sage">Grade obtinute</p><p class="mt-1 text-3xl font-semibold text-frte-deep dark:text-white">{{ $rider->gradeAwards()->count() }}</p></div>
                        </div>
                        <flux:button class="mt-6" :href="route('rider.dashboard')" variant="ghost" wire:navigate>Vezi progresul complet</flux:button>
                    @else
                        <p class="mt-3 text-zinc-600 dark:text-zinc-300">Progresul va fi afisat dupa activarea profilului de calaret.</p>
                    @endif
                </section>
            </section>
        @else
        <section class="rounded-3xl bg-frte-mint px-7 py-14 md:px-12 lg:grid lg:min-h-[58vh] lg:grid-cols-[1.2fr_0.8fr] lg:items-center lg:gap-12 lg:px-16 lg:py-20 dark:bg-[#17231d]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-frte-forest dark:text-frte-sage">Federatia Romana de Turism Ecvestru</p>
                <h1 class="mt-4 max-w-4xl text-5xl font-semibold leading-tight tracking-tight text-frte-deep md:text-6xl dark:text-white">Jurnal de Calarie</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-700 dark:text-zinc-300">Parcursul ecvestru al calaretului, validat de centre si monitori autorizati, intr-un registru digital unic.</p>
                <div class="mt-8 flex flex-wrap gap-3"><flux:button :href="route('account.choose')" variant="primary">Creeaza cont</flux:button><flux:button :href="route('login')">Autentificare</flux:button></div>
            </div>
            <div class="mt-10 flex justify-center lg:mt-0 lg:justify-end"><img src="/images/logo-frte.png" alt="FRTE" class="w-full max-w-sm object-contain" /></div>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <a href="{{ route('centers.apply') }}" class="group rounded-2xl border border-frte-sage/70 bg-white p-8 transition hover:-translate-y-1 hover:border-frte-forest dark:border-frte-border dark:bg-[#17231d]" wire:navigate><div class="mb-5 flex size-11 items-center justify-center rounded-xl bg-frte-mint text-frte-forest dark:bg-[#1e3a2f] dark:text-frte-sage"><flux:icon.building-office-2 class="size-6" /></div><p class="text-2xl font-semibold text-frte-forest dark:text-white">Afiliere centru de echitatie</p><p class="mt-3 text-zinc-600 dark:text-zinc-300">Consulta si completeaza formularul public pentru autorizarea si publicarea centrului.</p><p class="mt-7 font-medium text-frte-active transition group-hover:translate-x-1 dark:text-frte-sage">Deschide formularul →</p></a>
            <a href="{{ route('professionals.apply') }}" class="group rounded-2xl border border-frte-sage/70 bg-frte-mint/50 p-8 transition hover:-translate-y-1 hover:border-frte-forest dark:border-frte-border dark:bg-[#1e3a2f]" wire:navigate><div class="mb-5 flex size-11 items-center justify-center rounded-xl bg-frte-sage text-frte-deep dark:bg-[#38644f] dark:text-white"><flux:icon.identification class="size-6" /></div><p class="text-2xl font-semibold text-frte-forest dark:text-white">Autorizare monitor</p><p class="mt-3 text-zinc-600 dark:text-zinc-300">Transmite calificarea, identificatorii profesionali si solicitarea de evaluator.</p><p class="mt-7 font-medium text-frte-active transition group-hover:translate-x-1 dark:text-frte-sage">Deschide formularul →</p></a>
        </section>

        <section>
            <h2 class="mb-8 text-3xl font-semibold tracking-tight text-frte-forest dark:text-white">O platforma unificata</h2>
            <div class="grid gap-5 md:grid-cols-3">
                <a href="{{ route('federation.index') }}" class="group rounded-2xl border border-frte-sage/60 bg-white p-6 transition hover:-translate-y-1 hover:border-frte-forest dark:border-frte-border dark:bg-[#17231d]" wire:navigate><div class="mb-4 flex size-10 items-center justify-center rounded-full bg-frte-mint text-frte-forest dark:bg-[#1e3a2f] dark:text-frte-sage"><flux:icon.building-library class="size-5" /></div><h3 class="text-lg font-semibold text-frte-forest dark:text-white">Federatie</h3><p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">Descopera povestea, misiunea si valorile FRTE.</p><p class="mt-4 text-sm font-medium text-frte-active dark:text-frte-sage">Vezi federatia →</p></a>
                <a href="{{ route('centers.index') }}" class="group rounded-2xl border border-frte-sage/60 bg-frte-paper p-6 transition hover:-translate-y-1 hover:border-frte-forest dark:border-frte-border dark:bg-[#1e3a2f]" wire:navigate><div class="mb-4 flex size-10 items-center justify-center rounded-full bg-frte-sage text-frte-deep dark:bg-[#38644f] dark:text-white"><flux:icon.map-pin class="size-5" /></div><h3 class="text-lg font-semibold text-frte-forest dark:text-white">Centre</h3><p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">Descopera centrele de echitatie membre FRTE si serviciile oferite.</p><p class="mt-4 text-sm font-medium text-frte-active dark:text-frte-sage">Vezi centrele →</p></a>
                <a href="{{ route('professionals.index') }}" class="group rounded-2xl border border-frte-sage/60 bg-white p-6 transition hover:-translate-y-1 hover:border-frte-forest dark:border-frte-border dark:bg-[#17231d]" wire:navigate><div class="mb-4 flex size-10 items-center justify-center rounded-full bg-frte-mint text-frte-forest dark:bg-[#1e3a2f] dark:text-frte-sage"><flux:icon.users class="size-5" /></div><h3 class="text-lg font-semibold text-frte-forest dark:text-white">Monitori</h3><p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">Descopera profesionistii certificati FRTE si acreditarile lor.</p><p class="mt-4 text-sm font-medium text-frte-active dark:text-frte-sage">Vezi monitorii →</p></a>
            </div>
        </section>
        @endauth
    </div>
</x-layouts.public>
