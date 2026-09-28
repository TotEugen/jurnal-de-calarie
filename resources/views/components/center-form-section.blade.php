@props(['title', 'description' => null])
<section {{ $attributes->class('space-y-6 rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm dark:border-emerald-900 dark:bg-zinc-900 md:p-8') }}>
    <div><flux:heading size="lg">{{ $title }}</flux:heading>@if($description)<flux:text class="mt-1">{{ $description }}</flux:text>@endif</div>
    {{ $slot }}
</section>
