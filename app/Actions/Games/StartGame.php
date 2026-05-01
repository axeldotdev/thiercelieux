<?php

declare(strict_types=1);

namespace App\Actions\Games;

use App\Enums\Role;
use App\Models\Game;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StartGame
{
    public const int MIN_PLAYERS = 4;

    public function __invoke(Game $game): void
    {
        if (! $game->isDraft()) {
            throw ValidationException::withMessages([
                'game' => 'Cette partie a déjà été démarrée.',
            ]);
        }

        if (Game::query()->whereNotNull('started_at')->whereNull('ended_at')->exists()) {
            throw ValidationException::withMessages([
                'game' => 'Une autre partie est déjà en cours.',
            ]);
        }

        $users = $game->users()->get();

        if ($users->count() < self::MIN_PLAYERS) {
            throw ValidationException::withMessages([
                'users' => 'Il faut au moins '.self::MIN_PLAYERS.' joueurs pour lancer une partie.',
            ]);
        }

        $assignments = Arr::shuffle($this->buildAssignments($game, $users->count()));

        DB::transaction(function () use ($game, $users, $assignments): void {
            foreach ($users as $index => $user) {
                $game->users()->updateExistingPivot($user->id, [
                    'role' => $assignments[$index]->value,
                ]);
            }

            $game->forceFill(['started_at' => now()])->save();
        });
    }

    /**
     * @return array<int, Role>
     */
    private function buildAssignments(Game $game, int $count): array
    {
        $packRoles = collect($game->roles)
            ->flatMap(fn ($pack) => $pack->roles())
            ->unique()
            ->values();

        $specials = $packRoles
            ->reject(fn (Role $role) => in_array($role, [Role::Villageois, Role::LoupGarou], true))
            ->values();

        $nbWolves = max(1, intdiv($count, 4));
        $nbSpecialsToPick = min($specials->count(), max(0, $count - $nbWolves));

        $picked = $specials->shuffle()->take($nbSpecialsToPick)->values();
        $nbVillagers = $count - $nbWolves - $picked->count();

        $assignments = [];
        for ($i = 0; $i < $nbWolves; $i++) {
            $assignments[] = Role::LoupGarou;
        }
        foreach ($picked as $role) {
            $assignments[] = $role;
        }
        for ($i = 0; $i < $nbVillagers; $i++) {
            $assignments[] = Role::Villageois;
        }

        return $assignments;
    }
}
