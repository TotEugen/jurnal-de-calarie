<x-layouts::app title="Selecteaza panoul">
    <div class="mx-auto w-full max-w-6xl space-y-8">
        <header class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-950 via-emerald-700 to-teal-500 px-8 py-10 text-white shadow-xl sm:px-10">
            <div class="absolute -right-12 -top-16 size-64 rounded-full bg-lime-300/20 blur-2xl"></div>
            <div class="relative max-w-3xl">
                <p class="font-semibold uppercase tracking-[0.2em] text-emerald-100">Jurnal de Calarie</p>
                <flux:heading size="xl" class="mt-2 !text-white">Bun venit, {{ auth()->user()->name }}</flux:heading>
                <p class="mt-3 text-lg text-emerald-50">Continua-ti parcursul ecvestru si pastreaza fiecare experienta intr-un singur loc.</p>
            </div>
        </header>
        <div class="grid gap-5 md:grid-cols-2">
            @can('access-federation')
                <a href="{{ route('federation.applications') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Federatie</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Validari, registre si administrarea sistemului.</p></a>
            @endcan
            @can('access-center')
                <a href="{{ route('center.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Centru de echitatie</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Calareti, monitori si activitatea centrului.</p></a>
            @endcan
            @can('access-monitor')
                <a href="{{ route('monitor.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Monitor</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Parcursul calaretilor si inregistrarea activitatilor.</p></a>
            @endcan
            @can('access-rider')
                <a href="{{ route('rider.dashboard') }}" class="group overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50 p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-900 dark:from-zinc-900 dark:to-emerald-950" wire:navigate>
                    <div class="flex items-start justify-between"><div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-700 text-2xl text-white">♞</div><span class="text-emerald-700 transition group-hover:translate-x-1 dark:text-emerald-300">Deschide →</span></div>
                    <p class="mt-6 text-xl font-bold text-emerald-950 dark:text-white">Progres si sesiuni</p>
                    <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Jurnal personal, sesiunile de calarie, confirmari, grade si centre.</p>
                    <div class="mt-6 h-2 overflow-hidden rounded-full bg-emerald-100 dark:bg-emerald-900"><div class="h-full w-2/3 rounded-full bg-gradient-to-r from-emerald-600 to-lime-400"></div></div>
                </a>
            @endcan
            @can('access-guardian')
                <a href="{{ route('guardian.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Profiluri administrate</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Gestioneaza profilurile calaretilor minori aflati in grija.</p></a>
            @endcan
        </div>
    </div>
</x-layouts::app>
