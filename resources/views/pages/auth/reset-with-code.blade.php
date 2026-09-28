<x-layouts::auth title="Alege parola noua">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Parola noua" description="Alege o parola sigura pentru contul tau." />
        <form method="POST" action="{{ route('recovery.reset.store') }}" class="space-y-5">
            @csrf
            <flux:input name="password" label="Parola noua" type="password" autocomplete="new-password" required viewable />
            <flux:input name="password_confirmation" label="Confirma parola noua" type="password" autocomplete="new-password" required viewable />
            <flux:button type="submit" variant="primary" class="w-full">Salveaza parola</flux:button>
        </form>
    </div>
</x-layouts::auth>
