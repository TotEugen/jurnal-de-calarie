<!DOCTYPE html>
<html lang="ro">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-950 dark:text-white">
        <header class="border-b border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate>
                    <img src="/images/logo-frte.png" alt="FRTE" class="size-12 object-contain" />
                    <div><p class="font-semibold">Jurnal de Călărie</p><p class="text-xs text-zinc-500">Platformă digitală FRTE</p></div>
                </a>
                <nav class="flex items-center gap-2">
                    <flux:button :href="route('centers.apply')" variant="ghost" wire:navigate>Centre</flux:button>
                    <flux:button :href="route('professionals.apply')" variant="ghost" wire:navigate>Monitori</flux:button>
                    @auth
                        <flux:button :href="route('dashboard')" variant="primary" wire:navigate>Contul meu</flux:button>
                    @else
                        <flux:button :href="route('login')" variant="primary">Autentificare</flux:button>
                    @endauth
                </nav>
            </div>
        </header>
        <main class="px-5 py-10 lg:px-8">{{ $slot }}</main>
        @fluxScripts
    </body>
</html>
