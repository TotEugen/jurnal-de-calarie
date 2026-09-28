<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="settings-content-section mt-12 space-y-6 border-t pt-8">
    <div class="relative mb-5">
        <flux:heading class="!text-red-600 dark:!text-red-400">Sterge contul</flux:heading>
        <flux:subheading>Stergerea contului si a tuturor datelor asociate este definitiva.</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" data-test="delete-user-button">
            Sterge contul
        </flux:button>
    </flux:modal.trigger>

    <livewire:pages::settings.delete-user-modal />
</section>
