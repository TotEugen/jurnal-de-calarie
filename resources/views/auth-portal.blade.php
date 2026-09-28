<x-layouts.public>
    <div class="mx-auto max-w-6xl space-y-10">
        <header class="overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-950 via-emerald-700 to-teal-500 px-7 py-12 text-white shadow-xl md:px-12">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-100">Inregistrare Jurnal de Calarie</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight md:text-5xl">Alege tipul contului</h1>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-emerald-50">Selecteaza rolul pentru care vrei sa creezi un cont nou in platforma.</p>
        </header>

        <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
            <a href="{{ route('register') }}" class="group flex min-h-72 flex-col rounded-3xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-emerald-500 hover:shadow-xl dark:border-emerald-900 dark:from-zinc-900 dark:to-emerald-950" wire:navigate>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-700 text-white"><flux:icon.user class="size-6" /></div>
                <h2 class="mt-6 text-xl font-bold text-emerald-950 dark:text-white">Calaret</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Acceseaza jurnalul, sesiunile si progresul tau ecvestru.</p>
                <p class="mt-auto pt-6 font-semibold text-emerald-700 transition group-hover:translate-x-1 dark:text-emerald-300">Creeaza cont calaret →</p>
            </a>

            <a href="{{ route('guardian.register') }}" class="group flex min-h-72 flex-col rounded-3xl border border-sky-200 bg-gradient-to-br from-white to-sky-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-sky-500 hover:shadow-xl dark:border-sky-900 dark:from-zinc-900 dark:to-sky-950" wire:navigate>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-sky-700 text-white"><flux:icon.users class="size-6" /></div>
                <h2 class="mt-6 text-xl font-bold text-sky-950 dark:text-white">Tutore / Parinte</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Administreaza profilurile si parcursul calaretilor minori aflati in grija ta.</p>
                <p class="mt-auto pt-6 font-semibold text-sky-700 transition group-hover:translate-x-1 dark:text-sky-300">Creeaza cont tutore →</p>
            </a>

            <a href="{{ route('centers.apply') }}" class="group flex min-h-72 flex-col rounded-3xl border border-teal-200 bg-teal-50 p-6 transition hover:-translate-y-1 hover:border-teal-500 hover:shadow-xl dark:border-teal-900 dark:bg-teal-950/50" wire:navigate>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-teal-700 text-white"><flux:icon.building-office-2 class="size-6" /></div>
                <h2 class="mt-6 text-xl font-bold text-teal-950 dark:text-white">Centru</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Administreaza activitatea centrului si confirmarile calaretilor.</p>
                <span class="mt-auto pt-6 font-semibold text-teal-700 transition group-hover:translate-x-1 dark:text-teal-300">Creeaza cont centru →</span>
            </a>

            <a href="{{ route('professionals.apply') }}" class="group flex min-h-72 flex-col rounded-3xl border border-amber-200 bg-amber-50 p-6 transition hover:-translate-y-1 hover:border-amber-500 hover:shadow-xl dark:border-amber-900 dark:bg-amber-950/50" wire:navigate>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-amber-600 text-white"><flux:icon.identification class="size-6" /></div>
                <h2 class="mt-6 text-xl font-bold text-amber-950 dark:text-white">Monitor</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Confirma sesiuni si urmareste parcursul calaretilor afiliati.</p>
                <span class="mt-auto pt-6 font-semibold text-amber-700 transition group-hover:translate-x-1 dark:text-amber-300">Creeaza cont monitor →</span>
            </a>

            <article class="flex min-h-72 flex-col rounded-3xl border border-slate-300 bg-slate-100 p-6 dark:border-slate-700 dark:bg-slate-900">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-slate-800 text-white"><flux:icon.shield-check class="size-6" /></div>
                <h2 class="mt-6 text-xl font-bold text-slate-950 dark:text-white">Admin</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">Acces pentru administrarea si validarea platformei.</p>
                <span class="mt-auto pt-6 text-sm font-semibold text-slate-600 dark:text-slate-300">Creare cont admin in pregatire</span>
            </article>
        </section>

        <section class="flex flex-col items-center justify-between gap-5 rounded-3xl border border-emerald-200 bg-white p-7 shadow-sm dark:border-emerald-900 dark:bg-zinc-900 sm:flex-row sm:px-9">
            <div><h2 class="text-xl font-bold text-emerald-950 dark:text-white">Ai deja cont?</h2><p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">Intra in cont folosind adresa de email si parola.</p></div>
            <flux:button :href="route('login')" variant="primary" wire:navigate>Autentificare</flux:button>
        </section>
    </div>
</x-layouts.public>
