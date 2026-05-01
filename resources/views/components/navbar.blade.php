@php($homeHref = auth()->check() ? route('dashboard') : route('home'))

<flux:header container>
    <a href="{{ $homeHref }}" wire:navigate>
        <x-app-logo-icon class="size-10 object-contain" />
    </a>

    <flux:spacer />

    @auth
        <x-desktop-user-menu />
    @else
        <flux:button :href="route('login')" variant="filled" wire:navigate>
            {{ __('Log in') }}
        </flux:button>
    @endauth
</flux:header>
