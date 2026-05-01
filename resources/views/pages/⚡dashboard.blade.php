<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.app')]
#[Title('Le feu murmure ton nom')]
class extends Component {
    //
}; ?>

<div>
    <a href="{{ route('home') }}" class="inline-flex items-center justify-center" wire:navigate>
        <x-app-logo-icon class="size-28 object-contain drop-shadow-[0_0_22px_rgba(251,191,36,0.55)]" />
        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <h1 class="mt-8 font-serif-display text-4xl text-stone-50 leading-tight text-balance">
        Le feu murmure ton <em class="text-amber-200">nom</em>
    </h1>
    <p class="mt-3 text-sm text-stone-300 text-pretty max-w-md mx-auto">
        Approche, écoute &mdash; la nuit a quelque chose à te confier.
    </p>

    <flux:button variant="primary" class="mt-8">
        Découvrir son secret
    </flux:button>
</div>
