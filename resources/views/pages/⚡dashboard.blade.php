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
    <x-firelit-hero>
        <x-slot:title>
            Le feu murmure ton <em class="text-amber-200">nom</em>
        </x-slot>
        <x-slot:subtitle>
            Approche, écoute &mdash; la nuit a quelque chose à te confier.
        </x-slot>

        <flux:button variant="primary" class="mt-8">
            Découvrir son secret
        </flux:button>

        @can('viewAny', App\Models\User::class)
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <flux:link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="text-stone-400 text-xs">
                    Se déconnecter
                </flux:link>
            </form>
        @endcan
    </x-firelit-hero>
</div>
