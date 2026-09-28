<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        <div class="mb-6">
            <flux:button
                type="button"
                variant="ghost"
                icon="arrow-left"
                data-test="back-button"
                onclick="window.history.length > 1 ? window.history.back() : window.location.assign(@js(route('dashboard')))"
            >
                Inapoi
            </flux:button>
        </div>

        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
