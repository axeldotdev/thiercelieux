<x-layouts::auth :title="__('Connexion')">
    <div class="flex flex-col gap-6">
        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Se souvenir de moi')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Se connecter') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
