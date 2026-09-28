<x-layouts.public>
    @php
        $safetyRules = [
            ['Nu uita ca esti musafir', 'Respecta regulile gazdei. Poarta-te asa cum ti-ai dori sa se poarte musafirii tai cand vin la tine in vizita. Familiarizeaza-te cu regulile casei inainte de a incaleca.'],
            ['Poarta toca', 'Foloseste o toca ce respecta standardele de siguranta din industrie. Chiar daca instructorul, ghidul sau alti calareti nu poarta toca, decizia iti apartine: este capul tau si tu raspunzi de el.'],
            ['Fii prezent', 'Atunci cand calaresti esti mai mult decat un simplu pasager. Fii mereu atent la calul tau, la ceea ce faci si la locul in care te afli.'],
            ['Asculta instructiunile', 'Asculta si respecta instructiunile si sfaturile instructorului sau ale ghidului. Sunt importante pentru siguranta ta.'],
            ['Nu depasi ghidul', 'Depaseste ghidul doar daca acesta iti permite. Ramai in ritmul impus de el.'],
            ['Nu porni inainte', 'Porneste doar atunci cand toti calaretii sunt pregatiti de plecare.'],
            ['Fii atent la ceilalti calareti', 'Cand calaresti in grup, pastreaza distanta fata de calul dinaintea ta. Distanta de siguranta este de o lungime de cal in fata si in spate si de o latime pe laterale.'],
            ['Nu taia brusc calea celorlalti cai', 'Mai ales la galop sau la sarituri, nu ii depasi in viteza pe ceilalti calareti.'],
            ['Comunica cu ceilalti', 'Ca gest de politete, avertizeaza verbal despre pericole precum denivelari sau crengi si transmite mai departe instructiunile ghidului intr-un mod clar.'],
            ['Nu arunca lucruri catre cal sau de pe el cand esti calare', 'Calul este imprevizibil si sensibil la miscari bruste si zgomote. Descaleca atunci cand ai nevoie sa te imbraci sau sa te dezbraci.'],
            ['Poarta echipamentul adecvat si asigura-l corespunzator', 'Poarta toca, ochelari de soare cand este cazul si cizme in care te simti confortabil pentru mersul pe distante lungi.'],
            ['Respecta natura si mediul inconjurator', 'Nu lasa deseuri in urma ta, nu colecta exemplare de flora sau fauna si nu interactiona cu animalele salbatice.'],
        ];

        $horseRules = [
            ['Caii pot fi imprevizibili', 'Pot avea loc accidente, insa, cu atentie si respectarea masurilor de siguranta, multe dintre ele pot fi prevenite.'],
            ['Apropie-te corect de cal', 'Vino din lateral si vorbeste cu el pentru a-l informa ca esti acolo. Calul are doua unghiuri moarte in care nu vede: direct in fata si in spatele lui.'],
            ['Nu face zgomote puternice sau miscari bruste', 'Caii au simturi foarte ascutite si pot reactiona daca te percep ca pe un pericol, prin fuga sau lovire.'],
            ['Ai grija la spatele calului', 'Lasa suficient spatiu cand treci prin spatele lui pentru a evita o eventuala lovitura.'],
            ['Ramai aproape de corpul lui', 'Cand ii faci pansajul sau il harnasezi, ramai aproape de corpul lui si ai grija sa nu te calce.'],
            ['Nu infasura nimic in jurul incheieturii mainii', 'Tine darlogii, lesa si lonja cat mai lejer, astfel incat sa le poti elibera usor daca animalul se sperie si fuge.'],
            ['Intoarce-l cu fata spre tine in boxa', 'Cand duci calul la grajd, intoarce-l cu fata spre usa inainte de a-i scoate capastrul sau fraul.'],
            ['Intoarce capul calului catre gard', 'Cand eliberezi un cal in padoc, lasa suficient spatiu intre cai si asigura-te ca toata lumea ii elibereaza in acelasi timp.'],
            ['Cere ajutor', 'Cere ajutor cand ii faci pansajul, ii pui harnasamentul sau nu ai inteles ce ti se cere.'],
            ['Poarta incaltaminte care iti protejeaza picioarele', 'Nu te apropia de cai in slapi sau sandale. Riscul de a fi calcat pe picioare este mare.'],
            ['Bijuteriile se pot agata si pot provoca rani', 'Evita, pe cat posibil, bratarile, lantisoarele si cerceii lungi atunci cand te urci pe cal.'],
            ['Poarta imbracaminte inchisa cu nasturi sau fermoar', 'Imbracamintea care flutura cand esti calare poate speria calul si il poate face sa reactioneze nepotrivit.'],
        ];

        $centerRules = [
            ['Curatenia la grajd si in celelalte zone ale centrului', 'Pe langa faptul ca a tine boxele curate este o chestiune de igiena si sanatate pentru cai, curatenia arata preocuparea si respectul centrului fata de activitatea sa si fata de clienti.'],
            ['Observa cat de bine ingrijiti sunt caii', 'Priveste starea lor de spirit, daca arata bine, daca sunt raniti si daca petrec suficient timp liberi in natura. Un cal caruia nu ii sunt indeplinite nevoile zilnice poate crea dificultati la incalecare.'],
            ['Fii atent la reactiile cailor din centru', 'Mai ales la calul care ti-a fost alocat. Daca se sperie sau tresare cand ridici mana, este posibil sa fi fost abuzat fizic, iar sansele de reactii nepotrivite sunt mai mari.'],
            ['Fii atent la echipamentele folosite', 'Are fiecare cal echipamentul sau? Sunt echipamentele in stare buna? Niciun echipament care atinge calul nu trebuie sa il raneasca si niciun echipament nu trebuie prins sau carpit cu sarma.'],
            ['Purtarea tocii este obligatorie', 'Regula este valabila atat pentru calaria in manej, cat si pentru iesirile in natura. Un centru responsabil trebuie sa iti puna o toca la dispozitie daca nu ai una.'],
            ['Alegerea calului potrivit pentru tine', 'Calul trebuie ales in functie de varsta, greutatea si nivelul de pregatire al calaretului. Personalul centrului trebuie sa cunoasca aceste aspecte si sa creeze cupluri cal-calaret echilibrate.'],
            ['Verificarea echipamentului', 'Instructorul sau ghidul trebuie sa verifice chinga si nivelul scaritelor inainte de inceperea activitatii si sa nu permita calaria cu echipament necorespunzator.'],
        ];
    @endphp

    <div class="mx-auto max-w-5xl space-y-12">
        <header class="border-b border-zinc-200 pb-8 dark:border-zinc-800">
            <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Ghid pentru membri</flux:text>
            <flux:heading size="xl" class="mt-2">Responsabilitati membri FRTE</flux:heading>
            <flux:text class="mt-3 max-w-3xl text-lg">Recomandari pentru o experienta ecvestra sigura, etica si responsabila.</flux:text>
        </header>

        @foreach ([['Reguli de siguranta si etica', $safetyRules], ['Reguli privind interactiunea cu calul', $horseRules]] as [$heading, $rules])
            <section class="space-y-6">
                <flux:heading size="xl">{{ $heading }}</flux:heading>
                @if ($loop->first)
                    <flux:text class="max-w-4xl leading-7">Siguranta si etica in calarie reprezinta o combinatie de spirit practic, bune maniere si respect fata de cai si de ceilalti calareti. Tine cont de urmatoarele aspecte atunci cand practici calaria de agrement si turismul ecvestru.</flux:text>
                @else
                    <flux:text class="max-w-4xl leading-7">Atunci cand practici calaria exista riscuri la care te expui, de aceea este important sa respecti bunele practici in interactiunea cu caii, chiar daca esti un calaret experimentat.</flux:text>
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($rules as [$title, $description])
                        <article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                            <div class="flex gap-4">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $loop->iteration }}</span>
                                <div><h3 class="font-semibold">{{ $title }}</h3><p class="mt-2 leading-7 text-zinc-600 dark:text-zinc-300">{{ $description }}</p></div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach

        <section class="space-y-5 border-t border-zinc-200 pt-10 dark:border-zinc-800">
            <flux:heading size="xl">Importanta calariei responsabile</flux:heading>
            <div class="space-y-4 leading-7 text-zinc-600 dark:text-zinc-300">
                <p>Calaria este o activitate complexa, cu foarte multe beneficii in plan personal pentru calaret, atat fizice, cat si psihice si emotionale. Acest lucru este posibil pentru ca implica lucrul cu un partener care, in ciuda faptului ca nu vorbeste, comunica si transmite energie.</p>
                <p>Calaria implica insa si un risc mare de accidentare, tocmai pentru ca presupune lucrul cu o fiinta vie, care are propriile emotii si trairi, nu cu o masinarie. Calul este un animal-prada, mult mai mare comparativ cu noi, care este foarte sensibil si poate reactiona nu din dorinta de a ne face rau, ci pentru a scapa de un potential pericol.</p>
                <p class="font-semibold text-zinc-900 dark:text-white">De aceea este important sa cunosti principalele caracteristici ale calului si sa fii atent la nevoile lui. Bunastarea lui inseamna un risc mai mic de accidentare pentru noi si o experienta calare buna pentru ansamblu.</p>
                <p>Centrul ecvestru este nucleul fara de care activitatea ecvestra nu ar putea exista. Modul in care centrul isi desfasoara activitatea face diferenta intre o experienta frumoasa de calarie, o calatorie de autocunoastere si autodepasire si o experienta nefericita, care te poate traumatiza pe viata.</p>
            </div>
        </section>

        <section class="space-y-6 border-t border-zinc-200 pt-10 dark:border-zinc-800">
            <div>
                <flux:heading size="xl">Cum poti recunoaste un centru ecvestru responsabil</flux:heading>
                <flux:text class="mt-3 max-w-4xl leading-7">Nu exista pur si simplu centre bune sau rele. Diferenta este data de nivelul de cunostinte si experienta al personalului, de frecventa incidentelor si de gradul de implicare si asumare a responsabilitatii din partea managementului.</flux:text>
            </div>

            <div class="rounded-xl bg-emerald-800 px-6 py-4 text-center font-semibold text-white">Siguranta calaretului + Bunastarea calului = Calarie responsabila</div>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($centerRules as [$title, $description])
                    <article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex gap-4">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $loop->iteration }}</span>
                            <div><h3 class="font-semibold">{{ $title }}</h3><p class="mt-2 leading-7 text-zinc-600 dark:text-zinc-300">{{ $description }}</p></div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="flex gap-4 rounded-xl border border-emerald-300 bg-emerald-50 p-6 !text-emerald-950 dark:border-emerald-700 dark:bg-emerald-100 dark:!text-emerald-950">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-lg text-white" aria-hidden="true">✓</span>
                <div>
                    <p class="font-semibold">Fiecare experienta te ajuta sa inveti</p>
                    <p class="mt-1 leading-7 !text-emerald-900">Nu te descuraja daca nu observi toate aceste lucruri din prima. Ele fac parte din calatoria ta si iti vor fi dezvaluite pe parcurs de cei care iti servesc drept calauza.</p>
                </div>
            </div>
        </section>

        <div class="border-t border-zinc-200 pt-8 dark:border-zinc-800">
            <flux:button :href="route('home')" variant="ghost" wire:navigate>← Inapoi la pagina principala</flux:button>
        </div>
    </div>
</x-layouts.public>
