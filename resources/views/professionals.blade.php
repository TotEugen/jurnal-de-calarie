<x-layouts.public>
    @php
        $professionals = [
            ['Madalina Burghelea', '18.03.2021', null, null],
            ['Stefan Comsa', '18.03.2021', null, null],
            ['Alexandru Gaftoi', '18.03.2021', 'Centrul Ecvestru Husar', 'centrul-ecvestru-husar'],
            ['Alexandra Coman', '18.03.2021', null, null],
            ['Manuela Lazar', '20.05.2021', null, null],
            ['Alexandru Sabau', '20.05.2021', 'Centrul de Echitatie Hipopas', 'hipopas'],
            ['Catalin Chites', '20.05.2021', null, null],
            ['Octavian Chereches', '20.05.2021', null, null],
            ['Attila Boer', '18.03.2021', 'ToniLand', 'toniland'],
            ['George Leca', '18.03.2021', null, null],
            ['Andrei Handolescu', '18.03.2021', null, null],
            ['Iulia Aldea', '18.03.2021', null, null],
            ['Elena Paraschiv', '18.03.2021', 'Echitatie Agrement Niscov', 'echitatie-agrement-niscov'],
            ['Alexandru Iacob', '18.03.2021', null, null],
            ['Mihai Badoi', '20.05.2021', null, null],
            ['Adrian Vascan', '20.05.2021', null, null],
            ['Razvan Burlacu', '20.05.2021', null, null],
            ['Marius Corcau', '20.05.2021', null, null],
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-10">
        <section class="overflow-hidden rounded-3xl bg-frte-mint px-7 py-12 md:px-12 dark:bg-[#17231d]">
            <p class="text-sm font-semibold uppercase tracking-wider text-frte-forest dark:text-frte-sage">Federatia Romana de Turism Ecvestru</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight text-frte-deep md:text-5xl dark:text-white">Profesionisti certificati FRTE</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-700 dark:text-zinc-300">Consulta lista monitorilor de echitatie de agrement certificati FRTE si acreditati international IGEQ.</p>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($professionals as [$name, $date, $center, $centerSlug])
                <article class="flex flex-col rounded-2xl border border-frte-sage/70 bg-white p-6 shadow-sm dark:border-frte-border dark:bg-[#17231d]">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-frte-mint text-frte-forest dark:bg-[#284638] dark:text-frte-sage"><flux:icon.identification class="size-6" /></div>
                    <h2 class="mt-5 text-xl font-semibold text-frte-forest dark:text-white">{{ $name }}</h2>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-xl bg-frte-paper p-4 dark:bg-[#1e3a2f]">
                            <p class="text-xs font-semibold uppercase tracking-wide text-frte-forest dark:text-frte-sage">Certificare FRTE</p>
                            <p class="mt-2 font-medium text-zinc-800 dark:text-white">Monitor Echitatie de Agrement</p>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Obtinuta la {{ $date }}</p>
                        </div>
                        <div class="rounded-xl bg-frte-mint/60 p-4 dark:bg-[#284638]">
                            <p class="text-xs font-semibold uppercase tracking-wide text-frte-forest dark:text-frte-sage">Acreditare IGEQ</p>
                            <p class="mt-2 font-medium text-zinc-800 dark:text-white">Leisure Horseback Riding Monitor</p>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Obtinuta la {{ $date }}</p>
                        </div>
                    </div>

                    @if ($center)
                        <p class="mt-5 text-sm text-zinc-600 dark:text-zinc-300">Activeaza la <a href="https://www.frte.org.ro/{{ $centerSlug }}" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-active hover:underline dark:text-frte-sage">{{ $center }}</a>.</p>
                    @endif

                    <a href="https://www.frte.org.ro/profesionisti-certificati-frte" target="_blank" rel="noopener noreferrer" class="mt-auto inline-flex items-center gap-2 pt-6 font-medium text-frte-active hover:text-frte-deep dark:text-frte-sage dark:hover:text-white">Vezi pe site-ul FRTE <span aria-hidden="true">→</span></a>
                </article>
            @endforeach
        </section>

        <section class="flex flex-col gap-5 rounded-2xl bg-frte-forest px-7 py-8 text-white md:flex-row md:items-center md:justify-between">
            <div><h2 class="text-2xl font-semibold">Vrei sa devii profesionist acreditat?</h2><p class="mt-2 max-w-3xl text-frte-mint">Descopera parcursul profesional FRTE, cursurile disponibile si nivelurile de pregatire pentru echitatia de agrement si turism ecvestru.</p></div>
            <flux:button href="https://www.frte.org.ro/cursuri-profesionisti-echitatie" target="_blank" rel="noopener noreferrer" variant="primary">Vezi cursurile FRTE</flux:button>
        </section>

        <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">Informatii preluate de pe pagina oficiala <a href="https://www.frte.org.ro/profesionisti-certificati-frte" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-active hover:underline">Profesionisti certificati FRTE</a>.</p>
    </div>
</x-layouts.public>
