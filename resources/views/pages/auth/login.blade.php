<x-layouts::app :title="__('Connexion')">
    <x-firelit-hero>
        <x-slot:title>
            Prends ta place <em class="text-amber-200">autour du feu</em>
        </x-slot>
        <x-slot:subtitle>
            Sous la pleine lune, chaque visage cache un secret.
        </x-slot>

        <div class="mt-8 w-full max-w-sm mx-auto text-left">
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <flux:input
                    name="email"
                    :label="__('Email')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                />

                <flux:input
                    name="password"
                    :label="__('Mot de passe')"
                    type="password"
                    required
                    autocomplete="current-password"
                    viewable
                />

                <flux:checkbox name="remember" :label="__('Se souvenir de moi')" :checked="old('remember')" />

                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Se connecter') }}
                </flux:button>
            </form>
        </div>
    </x-firelit-hero>
</x-layouts::app>
