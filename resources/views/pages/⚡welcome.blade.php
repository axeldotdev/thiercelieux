<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.app')]
#[Title('Bienvenue au village de Thiercelieux')]
class extends Component {
    //
}; ?>

<div>
    <a href="{{ route('home') }}" class="inline-flex items-center justify-center" wire:navigate>
        <x-app-logo-icon class="size-28 object-contain drop-shadow-[0_0_22px_rgba(251,191,36,0.55)]" />
        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <h1 class="mt-8 font-serif-display text-4xl text-stone-50 leading-tight text-balance">
        Bienvenue au village de <em class="text-amber-200">Thiercelieux</em>
    </h1>
    <p class="mt-3 text-sm text-stone-300 text-pretty max-w-md mx-auto">
        Prends ta place autour du feu, que la lune te garde.
    </p>

    <flux:button :href="route('dashboard')" variant="primary" class="mt-8" wire:navigate>
        Participer au conseil
    </flux:button>
</div>
