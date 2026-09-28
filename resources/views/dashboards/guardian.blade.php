<x-layouts::app title="Profiluri administrate">
    @php
        $relationships = auth()->user()->guardianRelationships()->with('rider')->get();
    @endphp
    <div class="mx-auto w-full max-w-5xl space-y-8">
        <header class="flex flex-col gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-end sm:justify-between dark:border-zinc-700">
            <div>
                <flux:text class="font-medium text-emerald-700 dark:text-emerald-400">Parinte / Tutore</flux:text>
                <flux:heading size="xl" class="mt-1">Profiluri administrate</flux:heading>
                <flux:text class="mt-2">Profilurile minorilor raman separate de contul tau si isi pastreaza istoricul.</flux:text>
            </div>
            <flux:button icon="pencil-square" :href="route('profile.edit')" wire:navigate>Editeaza contul meu</flux:button>
        </header>

        <div class="space-y-4">
            @foreach ($relationships as $relationship)
                @php $rider = $relationship->rider; @endphp
                <article class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg">{{ $rider->first_name }} {{ $rider->last_name }}</flux:heading>
                    <flux:text class="mt-1">{{ $rider->birth_date->age }} ani · {{ $relationship->relationship === 'parent' ? 'Parinte' : 'Tutore' }}</flux:text>
                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-950/40"><p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Nivel actual</p><p class="mt-1 font-semibold">{{ $rider->gradeAwards()->latest('awarded_at')->with('grade')->first()?->grade?->name ?? 'Neevaluat' }}</p></div>
                        <div class="rounded-xl bg-sky-50 p-4 dark:bg-sky-950/40"><p class="text-xs uppercase tracking-wide text-sky-700 dark:text-sky-300">Sesiuni</p><p class="mt-1 font-semibold">{{ $rider->sessions()->count() }}</p></div>
                        <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-950/40"><p class="text-xs uppercase tracking-wide text-amber-700 dark:text-amber-300">Confirmate</p><p class="mt-1 font-semibold">{{ $rider->sessions()->whereNotNull('monitor_confirmed_at')->count() }}</p></div>
                    </div>

                    @if (! $rider->contact_email || ! $rider->phone)
                        <div class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950/50 dark:text-amber-100">
                            <p class="font-semibold">Date de contact incomplete</p>
                            <p class="mt-1">
                                {{ $rider->birth_date->age >= 18 ? 'Calaretul a implinit 18 ani. Adauga emailul si telefonul personal pentru activarea accesului propriu.' : 'Emailul si telefonul personal pot fi adaugate ulterior in profil.' }}
                            </p>
                            <flux:button class="mt-3" size="sm" :href="route('riders.profile.edit', $rider)" wire:navigate>Editeaza profilul</flux:button>
                        </div>
                    @else
                        <div class="mt-4 flex justify-end"><flux:button size="sm" variant="ghost" :href="route('riders.profile.edit', $rider)" wire:navigate>Editeaza profilul</flux:button></div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</x-layouts::app>
