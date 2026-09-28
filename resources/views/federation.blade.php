<x-layouts.public>
    <div class="mx-auto max-w-7xl space-y-10">
        <section class="grid items-center gap-10 overflow-hidden rounded-3xl bg-frte-mint px-7 py-12 md:px-12 lg:grid-cols-[1fr_360px] dark:bg-[#17231d]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-frte-forest dark:text-frte-sage">Federatia Romana de Turism Ecvestru</p>
                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-frte-deep md:text-5xl dark:text-white">Povestea FRTE</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-700 dark:text-zinc-300">FRTE promoveaza descoperirea Romaniei din saua calului intr-un mod responsabil, avand la baza doua principii esentiale: Siguranta calaretului si Bunastarea calului.</p>
            </div>
            <img src="/images/logo-frte.png" alt="Sigla Federatiei Romane de Turism Ecvestru" class="mx-auto w-full max-w-xs object-contain" />
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-frte-sage/70 bg-white p-7 dark:border-frte-border dark:bg-[#17231d]">
                <flux:heading size="xl">Despre federatie</flux:heading>
                <p class="mt-4 leading-7 text-zinc-600 dark:text-zinc-300">FRTE este o persoana juridica romana de drept privat, independenta, neguvernamentala si fara scop patrimonial. A fost infiintata in 2017 la initiativa unui grup de centre si asociatii ecvestre si este recunoscuta de Guvernul Romaniei.</p>
                <p class="mt-4 leading-7 text-zinc-600 dark:text-zinc-300">Din 2019, federatia este afiliata international la Federatia Internationala de Turism Ecvestru (FITE) si la Grupul International pentru Certificari Ecvestre (IGEQ).</p>
            </article>
            <article class="rounded-2xl bg-frte-forest p-7 text-white">
                <flux:heading size="xl" class="!text-white">Viziune si misiune</flux:heading>
                <p class="mt-4 leading-7 text-frte-mint"><strong class="text-white">Viziune:</strong> Romania sa devina o destinatie preferata pentru turismul ecvestru in Europa si nu numai.</p>
                <p class="mt-4 leading-7 text-frte-mint"><strong class="text-white">Misiune:</strong> crearea cadrului necesar dezvoltarii industriei calariei de agrement si turismului ecvestru in Romania.</p>
            </article>
        </section>

        <section>
            <flux:heading size="xl">Valorile FRTE</flux:heading>
            <div class="mt-6 grid gap-5 md:grid-cols-3">
                <article class="rounded-2xl border border-frte-sage/70 bg-white p-6 dark:border-frte-border dark:bg-[#17231d]"><flux:icon.shield-check class="size-8 text-frte-active" /><h2 class="mt-4 text-xl font-semibold text-frte-forest dark:text-white">Responsabilitate</h2><p class="mt-2 leading-7 text-zinc-600 dark:text-zinc-300">Piatra de temelie a unei industrii ecvestre sigure si sustenabile.</p></article>
                <article class="rounded-2xl border border-frte-sage/70 bg-frte-paper p-6 dark:border-frte-border dark:bg-[#1e3a2f]"><flux:icon.heart class="size-8 text-frte-active" /><h2 class="mt-4 text-xl font-semibold text-frte-forest dark:text-white">Respect</h2><p class="mt-2 leading-7 text-zinc-600 dark:text-zinc-300">Respect fata de oameni, animale si mediul inconjurator.</p></article>
                <article class="rounded-2xl border border-frte-sage/70 bg-white p-6 dark:border-frte-border dark:bg-[#17231d]"><flux:icon.sparkles class="size-8 text-frte-active" /><h2 class="mt-4 text-xl font-semibold text-frte-forest dark:text-white">Calitate</h2><p class="mt-2 leading-7 text-zinc-600 dark:text-zinc-300">Servicii ecvestre si produse turistice care sustin competitivitatea Romaniei.</p></article>
            </div>
        </section>

        <section class="rounded-2xl border border-frte-sage/70 bg-white p-7 dark:border-frte-border dark:bg-[#17231d]">
            <flux:heading size="xl">Directii de dezvoltare</flux:heading>
            <div class="mt-6 grid gap-x-10 gap-y-4 md:grid-cols-2">
                @foreach (['Acreditarea centrelor de agrement si turism ecvestru', 'Cursuri profesionale si standarde ocupationale', 'Program national de educare si diplome pentru calareti', 'Jurnalul de Calarie si evidenta experientelor ecvestre', 'Centralizarea informatiilor despre centre, cai si profesionisti', 'Trasee acreditate si pachete de turism ecvestru', 'Promovarea Romaniei ca destinatie ecvestra', 'Sprijinirea dezvoltarii nationale si internationale a industriei'] as $objective)
                    <div class="flex gap-3"><flux:icon.check-circle class="mt-1 size-5 shrink-0 text-frte-active" /><p class="leading-7 text-zinc-600 dark:text-zinc-300">{{ $objective }}</p></div>
                @endforeach
            </div>
        </section>

        <section class="grid gap-6 rounded-2xl bg-frte-forest p-7 text-white md:grid-cols-2">
            <div><flux:heading size="xl" class="!text-white">Contact FRTE</flux:heading><p class="mt-3 max-w-xl leading-7 text-frte-mint">Pentru informatii despre activitatea federatiei, membri, programe de formare sau colaborari, foloseste datele oficiale de contact.</p></div>
            <div class="space-y-3 md:justify-self-end">
                <p><strong>Adresa:</strong> Str. Remus 1-3, Etaj 5, Sector 3, Bucuresti</p>
                <p><strong>Telefon:</strong> <a href="tel:+40723467587" class="underline decoration-frte-sage underline-offset-4">+40 (0)723 467 587</a></p>
                <p><strong>Email:</strong> <a href="mailto:info@frte.org.ro" class="underline decoration-frte-sage underline-offset-4">info@frte.org.ro</a></p>
                <div class="flex flex-wrap gap-4 pt-2"><a href="https://www.facebook.com/FRTE.TurismEcvestru" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-mint hover:text-white">Facebook</a><a href="https://www.instagram.com/frte.org.ro/" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-mint hover:text-white">Instagram</a><a href="https://www.youtube.com/channel/UCovEbgDS4-9-rIDYVy6qzhw" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-mint hover:text-white">YouTube</a></div>
            </div>
        </section>

        <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">Informatii sintetizate din paginile oficiale <a href="https://www.frte.org.ro/" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-active hover:underline">FRTE</a> si <a href="https://www.frte.org.ro/despre-frte" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-active hover:underline">Despre FRTE</a>.</p>
    </div>
</x-layouts.public>
