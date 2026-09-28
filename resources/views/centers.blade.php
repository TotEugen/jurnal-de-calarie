<x-layouts.public>
    @php
        $centers = [
            ['Potcoava Mountain Hideaway', 'Runcu, Dambovita', 'La poalele Muntilor Leaota, cu cai Haflinger si ponei pentru experiente dedicate intregii familii.', 'Lectii de echitatie, plimbari in natura, excursii calare, tabere, cazare si masa, evenimente si educatie asistata de cai.', 'potcoava-mountain-hideaway'],
            ['Cross Country Farm', 'Prod, Sibiu - la 16 km de Sighisoara', 'Centru cu experienta indelungata, cai si ponei, spatiu generos si pensiune in stil country chic.', 'Pachete ecvestre pentru orice nivel, weekenduri, tabere, stagii de pregatire, echitatie in natura si lectii.', 'cross-country-farm'],
            ['Izlandi Lovak', 'Izvoare, Harghita', 'Activitati construite in jurul cailor islandezi, cunoscuti pentru temperamentul bland si alurile specifice.', 'Lectii, trasee calare, tabere, dezvoltare ecvestra pentru copii, coaching cu cai, teambuilding, cazare si consultanta.', 'izlandi-lovak'],
            ['Villa Abbatis', 'Apos, Sibiu', 'Centru ecvestru in inima Transilvaniei, intr-o zona cunoscuta pentru natura, gastronomie si sate sasesti.', 'Trasee calare tematice, plimbari cu trasura, calarie cu picnic si birdwatching calare.', 'villa-abbatis'],
            ['Pensiunea Ghiocelul', 'Avrig, Sibiu', 'Experiente de relaxare in natura si conectare cu calul, intr-un cadru linistit.', 'Lectii, echitatie in natura, excursii calare, tabere, cazare, evenimente si pensiune pentru cai.', 'pensiunea-ghiocelul'],
            ['Nana Farm', 'Nana, Calarasi', 'Oaza de liniste aflata la aproximativ 50 km de Bucuresti, pe Soseaua Oltenitei.', 'Lectii, plimbari in natura, tabere, hipoterapie, pensiune pentru cai, sedinte foto si petreceri.', 'nana-farm'],
            ['Asociatia Scoala Bate Saua', 'Ciofliceni, Ilfov', 'Activitati de acomodare cu calul si cursuri de initiere in discipline si stiluri ecvestre.', 'Lectii in manej, plimbari, excursii, tabere si stagii de dresaj natural, working equitation si turism ecvestru.', 'centru-echitatie-bate-saua'],
            ['Asociatia Descopera Natura', 'Cluj - in apropierea orasului', 'Proiecte care apropie oamenii de cai, natura si obiceiuri sanatoase.', 'Hipoterapie, educatie in natura prin Scoala lui Gerula si tabere pentru copii si adulti.', 'asociatia-descopera-natura'],
            ['Centrul Ecvestru Husar', 'Slanic, Prahova', 'Centru destinat atat incepatorilor, cat si calaretilor avansati, cu accent pe horse trekking.', 'Lectii, echitatie in natura, educatie asistata de cai, tabere si cazare.', 'centrul-ecvestru-husar'],
            ['Echitatie Agrement Niscov', 'Niscov, Buzau', 'Centru situat intr-o zona pitoreasca de deal din Muntii Buzaului.', 'Lectii in manej si trasee de echitatie in natura pentru incepatori si avansati.', 'echitatie-agrement-niscov'],
            ['Club Ecvestru Transilvania', 'Selimbar, Sibiu', 'Centru ecvestru pentru iubitorii de cai, aflat la numai cativa kilometri de Sibiu.', 'Lectii, pregatire pentru dresaj si sarituri, echitatie in natura si excursii calare.', 'club-ecvestru-transilvania'],
            ['Equester Riding Club', 'Voinesti, Iasi', 'Centru intr-o zona linistita, cu spatii verzi si aer curat, aproape de Iasi.', 'Lectii, hipoterapie, activitati corporate si teambuildinguri alaturi de cai.', 'equester-riding-club'],
            ['ToniLand', 'Cuzdrioara, Cluj', 'Centru deschis in orice sezon, cu activitati pentru calareti incepatori si avansati.', 'Lectii, echitatie in natura, tabere si pensiune completa pentru cai.', 'toniland'],
            ['Centrul de Echitatie si Hipoterapie Hipopas', 'Baita, Maramures', 'Scoala de echitatie aflata la aproximativ 15 km de Baia Mare.', 'Lectii, plimbari in natura, tabere de echitatie si hipoterapie.', 'hipopas'],
            ['Caii din Padure', 'Runcu, Gorj', 'Experiente ecvestre autentice in mijlocul naturii, in padurea din Runcu.', 'Lectii, drumetii calare, tabere pentru copii, plimbari cu trasura, evenimente si programe educative.', 'caii-din-padure'],
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-10">
        <section class="overflow-hidden rounded-3xl bg-frte-mint px-7 py-12 md:px-12 dark:bg-[#17231d]">
            <p class="text-sm font-semibold uppercase tracking-wider text-frte-forest dark:text-frte-sage">Federatia Romana de Turism Ecvestru</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight text-frte-deep md:text-5xl dark:text-white">Centre de echitatie membre FRTE</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-700 dark:text-zinc-300">Descopera centre in care poti invata sa calaresti, participa la activitati ecvestre sau explora natura calare.</p>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($centers as [$name, $location, $description, $services, $slug])
                <article class="flex flex-col rounded-2xl border border-frte-sage/70 bg-white p-6 shadow-sm dark:border-frte-border dark:bg-[#17231d]">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-frte-mint text-frte-forest dark:bg-[#284638] dark:text-frte-sage"><flux:icon.map-pin class="size-6" /></div>
                    <h2 class="mt-5 text-xl font-semibold text-frte-forest dark:text-white">{{ $name }}</h2>
                    <p class="mt-2 text-sm font-medium text-frte-active dark:text-frte-sage">{{ $location }}</p>
                    <p class="mt-4 leading-7 text-zinc-600 dark:text-zinc-300">{{ $description }}</p>
                    <div class="mt-5 rounded-xl bg-frte-paper p-4 dark:bg-[#1e3a2f]">
                        <p class="text-xs font-semibold uppercase tracking-wide text-frte-forest dark:text-frte-sage">Servicii</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-700 dark:text-zinc-300">{{ $services }}</p>
                    </div>
                    <a href="https://www.frte.org.ro/{{ $slug }}" target="_blank" rel="noopener noreferrer" class="mt-auto inline-flex items-center gap-2 pt-6 font-medium text-frte-active hover:text-frte-deep dark:text-frte-sage dark:hover:text-white">Detalii pe site-ul FRTE <span aria-hidden="true">→</span></a>
                </article>
            @endforeach
        </section>

        <section class="flex flex-col gap-5 rounded-2xl bg-frte-forest px-7 py-8 text-white md:flex-row md:items-center md:justify-between">
            <div><h2 class="text-2xl font-semibold">Reprezinti un centru ecvestru?</h2><p class="mt-2 text-frte-mint">Completeaza cererea pentru autorizare si publicare in platforma.</p></div>
            <flux:button :href="route('centers.apply')" variant="primary" wire:navigate>Deschide formularul</flux:button>
        </section>

        <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">Informatii preluate de pe pagina oficiala <a href="https://www.frte.org.ro/centre-membre" target="_blank" rel="noopener noreferrer" class="font-medium text-frte-active hover:underline">Centre membre FRTE</a>.</p>
    </div>
</x-layouts.public>
