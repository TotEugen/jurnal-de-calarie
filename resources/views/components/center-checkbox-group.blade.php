@props(['title', 'name', 'options', 'other'])
<fieldset class="space-y-3">
    <legend class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $title }} <span aria-hidden="true">*</span></legend>
    <div class="grid gap-3 rounded-2xl bg-emerald-50 p-4 sm:grid-cols-2 dark:bg-emerald-950/35">
        @foreach ($options as $option)<flux:checkbox wire:model="answers.{{ $name }}" value="{{ $option }}" :label="$option" />@endforeach
        <div class="flex items-end gap-3 sm:col-span-2"><span class="pb-2 text-sm font-medium">Altul:</span><flux:input wire:model="answers.{{ $other }}" class="flex-1" /></div>
    </div>
    @error('answers.'.$name)<p class="text-sm text-red-600">{{ $message }}</p>@enderror
</fieldset>
