<!DOCTYPE html>
<html lang="ro">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-frte-paper text-zinc-900 dark:bg-[#101713] dark:text-white">
        <header class="border-b border-frte-sage/50 bg-white/80 backdrop-blur-sm dark:border-frte-border dark:bg-[#101713]/90">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate>
                    <img src="/images/logo-frte.png" alt="FRTE" class="size-12 object-contain" />
                    <div><p class="font-semibold text-frte-forest dark:text-white">Jurnal de Calarie</p><p class="text-xs text-zinc-500 dark:text-zinc-400">Platforma digitala FRTE</p></div>
                </a>
                <nav class="flex items-center gap-2">
                    <flux:button :href="route('centers.index')" variant="ghost" wire:navigate>Centre</flux:button>
                    <flux:button :href="route('professionals.index')" variant="ghost" wire:navigate>Monitori</flux:button>
                    @auth
                        <flux:button :href="route('dashboard')" variant="primary" wire:navigate>Contul meu</flux:button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:button type="submit" variant="ghost">Log out</flux:button>
                        </form>
                    @else
                        <flux:button :href="route('login')" variant="primary">Autentificare</flux:button>
                    @endauth
                </nav>
            </div>
        </header>
        <main class="px-5 py-8 lg:px-8 lg:py-12">{{ $slot }}</main>
        @fluxScripts
    </body>
</html>
