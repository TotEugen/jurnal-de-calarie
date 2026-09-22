<x-layouts::app title="Selectează panoul">
    <div class="mx-auto w-full max-w-6xl space-y-8">
        <header class="border-b border-zinc-200 pb-6 dark:border-zinc-700">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Jurnal de Călărie</flux:text>
            <flux:heading size="xl" class="mt-1">Bun venit, {{ auth()->user()->name }}</flux:heading>
            <flux:text class="mt-2">Alege spațiul de lucru corespunzător rolului tău.</flux:text>
        </header>
        <div class="grid gap-px overflow-hidden rounded-lg border border-zinc-200 bg-zinc-200 md:grid-cols-2 dark:border-zinc-700 dark:bg-zinc-700">
            @can('access-federation')
                <a href="{{ route('federation.applications') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Federație</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Validări, registre și administrarea sistemului.</p></a>
            @endcan
            @can('access-center')
                <a href="{{ route('center.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Centru de echitație</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Călăreți, monitori și activitatea centrului.</p></a>
            @endcan
            @can('access-monitor')
                <a href="{{ route('monitor.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Monitor</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Parcursul călăreților și înregistrarea activităților.</p></a>
            @endcan
            @can('access-rider')
                <a href="{{ route('rider.dashboard') }}" class="bg-white p-7 transition hover:bg-emerald-50 dark:bg-zinc-900 dark:hover:bg-emerald-950" wire:navigate><p class="text-lg font-semibold">Călăreț</p><p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Jurnal personal, progres, grade și centre.</p></a>
            @endcan
        </div>
    </div>
</x-layouts::app>
