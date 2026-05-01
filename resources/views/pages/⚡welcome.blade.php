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
    <x-firelit-hero>
        <x-slot:title>
            Bienvenue au village de <em class="text-amber-200">Thiercelieux</em>
        </x-slot>
        <x-slot:subtitle>
            Prends ta place autour du feu, le conseil va commencer.
        </x-slot>

        <flux:button :href="route('dashboard')" variant="primary" class="mt-8" wire:navigate>
            Participer au conseil
        </flux:button>
    </x-firelit-hero>
</div>
