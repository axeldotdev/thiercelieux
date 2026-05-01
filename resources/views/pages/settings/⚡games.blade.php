<?php

use App\Actions\Games\StartGame;
use App\Actions\Games\StopGame;
use App\Enums\RolePack;
use App\Models\Game;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.settings')]
#[Title('Parties')]
class extends Component {
    /** @var array<int, string> */
    public array $selectedPacks = ['classic'];

    /** @var array<int, int> */
    public array $selectedUsers = [];

    public function mount(): void
    {
        Gate::authorize('viewAny', Game::class);

        $this->selectedUsers = User::query()->pluck('id')->all();
    }

    #[Computed]
    public function games()
    {
        return Game::query()
            ->with('users')
            ->orderByDesc('id')
            ->get();
    }

    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    #[Computed]
    public function packs(): array
    {
        return RolePack::cases();
    }

    #[Computed]
    public function hasInProgress(): bool
    {
        return Game::query()
            ->whereNotNull('started_at')
            ->whereNull('ended_at')
            ->exists();
    }

    public function createGame(): void
    {
        Gate::authorize('viewAny', Game::class);

        $packValues = collect(RolePack::cases())->map(fn (RolePack $p) => $p->value)->all();

        $validated = $this->validate([
            'selectedPacks' => ['required', 'array', 'min:1'],
            'selectedPacks.*' => ['string', Rule::in($packValues)],
            'selectedUsers' => ['required', 'array', 'min:'.StartGame::MIN_PLAYERS],
            'selectedUsers.*' => ['integer', Rule::exists('users', 'id')],
        ]);

        $packs = collect($validated['selectedPacks'])
            ->push(RolePack::Classic->value)
            ->unique()
            ->values()
            ->all();

        $game = Game::create(['roles' => $packs]);
        $game->users()->attach($validated['selectedUsers']);

        $this->selectedUsers = User::query()->pluck('id')->all();
        $this->selectedPacks = ['classic'];

        unset($this->games);

        Flux::toast(variant: 'success', text: 'Partie créée.');
    }

    public function startGame(int $gameId, StartGame $action): void
    {
        Gate::authorize('viewAny', Game::class);

        $action(Game::findOrFail($gameId));

        unset($this->games, $this->hasInProgress);

        Flux::toast(variant: 'success', text: 'Partie commencée.');
    }

    public function stopGame(int $gameId, StopGame $action): void
    {
        Gate::authorize('viewAny', Game::class);

        $action(Game::findOrFail($gameId));

        unset($this->games, $this->hasInProgress);

        Flux::toast(variant: 'success', text: 'Partie arrêtée.');
    }

    public function deleteGame(int $gameId): void
    {
        Gate::authorize('viewAny', Game::class);

        Game::findOrFail($gameId)->delete();

        unset($this->games, $this->hasInProgress);

        Flux::toast(variant: 'success', text: 'Partie supprimée.');
    }
}; ?>

<section class="w-full max-w-7xl mx-auto lg:px-8">
    <div class="relative w-full">
        <flux:heading size="xl" level="1">Parties</flux:heading>
        <flux:subheading size="lg" class="mb-6">Créer, démarrer et arrêter les parties de Loup-Garou</flux:subheading>
    </div>

    <form wire:submit="createGame" class="my-12 w-full max-w-2xl space-y-6" data-test="create-game-form">
        <div class="space-y-3">
            <flux:heading size="md">Catégories de rôles</flux:heading>
            <div class="space-y-2">
                @foreach ($this->packs as $pack)
                    <flux:checkbox
                        wire:model="selectedPacks"
                        :value="$pack->value"
                        :label="$pack->label()"
                        :disabled="$pack === \App\Enums\RolePack::Classic"
                        :checked="$pack === \App\Enums\RolePack::Classic ? true : null"
                        :data-test="'pack-'.$pack->value"
                    />
                @endforeach
            </div>
        </div>

        <flux:checkbox.group
            label="Joueurs"
            description="Au moins {{ \App\Actions\Games\StartGame::MIN_PLAYERS }} joueurs requis."
            error:name="selectedUsers"
        >
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach ($this->users as $user)
                    <flux:field variant="inline">
                        <flux:checkbox
                            wire:model="selectedUsers"
                            :value="$user->id"
                            :data-test="'user-'.$user->id"
                        />
                        <flux:label class="leading-none!">{{ $user->name }}</flux:label>
                    </flux:field>
                @endforeach
            </div>
        </flux:checkbox.group>

        <flux:button variant="primary" type="submit" data-test="create-game-button">
            Créer la partie
        </flux:button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" data-test="games-list">
        @foreach ($this->games as $game)
            <flux:card :key="'game-'.$game->id" class="flex flex-col gap-4" data-test="game-card-{{ $game->id }}">
                <div class="flex items-center justify-between gap-4">
                    <flux:heading class="truncate">Partie {{ $game->id }}</flux:heading>
                    @if ($game->isEnded())
                        <flux:badge size="sm" color="green">Terminée</flux:badge>
                    @elseif ($game->isInProgress())
                        <flux:badge size="sm" color="blue">En cours</flux:badge>
                    @else
                        <flux:badge size="sm" color="zinc">Brouillon</flux:badge>
                    @endif
                </div>

                <div class="space-y-1">
                    <flux:text size="sm">{{ count($game->roles) }} extension(s)</flux:text>
                    @if (count($game->roles) > 0)
                        <flux:text size="sm" class="truncate">{{ collect($game->roles)->map(fn ($p) => $p->label())->join(', ') }}</flux:text>
                    @endif
                </div>

                <div class="space-y-1">
                    <flux:text size="sm">{{ $game->users->count() }} joueur(s)</flux:text>
                    @if ($game->users->isNotEmpty())
                        <flux:text size="sm" class="truncate">{{ $game->users->pluck('name')->join(', ') }}</flux:text>
                    @endif
                </div>

                <div class="flex flex-row flex-wrap gap-2">
                    @if ($game->isDraft())
                        <flux:button
                            size="sm"
                            variant="primary"
                            wire:click="startGame({{ $game->id }})"
                            :disabled="$this->hasInProgress"
                            data-test="start-{{ $game->id }}"
                        >
                            Commencer
                        </flux:button>
                    @elseif ($game->isInProgress())
                        <flux:button size="sm" wire:click="stopGame({{ $game->id }})" data-test="stop-{{ $game->id }}">
                            Arrêter
                        </flux:button>
                    @endif

                    <flux:button size="sm" variant="danger" wire:click="deleteGame({{ $game->id }})" data-test="delete-{{ $game->id }}">
                        Supprimer
                    </flux:button>
                </div>
            </flux:card>
        @endforeach
    </div>
</section>
