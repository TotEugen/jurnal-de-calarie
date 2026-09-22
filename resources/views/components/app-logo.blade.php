@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Jurnal de Călărie" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center">
            <img src="/images/logo-frte.png" alt="FRTE" class="size-9 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Jurnal de Călărie" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center">
            <img src="/images/logo-frte.png" alt="FRTE" class="size-9 object-contain" />
        </x-slot>
    </flux:brand>
@endif
