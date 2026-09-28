<div class="flex items-start gap-10 max-md:flex-col">
    <div class="settings-navigation w-full shrink-0 pb-4 md:w-[220px]">
        <flux:navlist aria-label="Setari">
            <flux:navlist.item :href="route('profile.edit')" wire:navigate>Contul meu</flux:navlist.item>
            <flux:navlist.item :href="route('security.edit')" wire:navigate>Parola si securitate</flux:navlist.item>
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>Aspect</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading size="lg">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-7 w-full max-w-2xl">
            {{ $slot }}
        </div>
    </div>
</div>
