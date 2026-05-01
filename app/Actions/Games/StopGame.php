<?php

declare(strict_types=1);

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Validation\ValidationException;

final class StopGame
{
    public function __invoke(Game $game): void
    {
        if (! $game->isInProgress()) {
            throw ValidationException::withMessages([
                'game' => 'Cette partie n\'est pas en cours.',
            ]);
        }

        $game->forceFill(['ended_at' => now()])->save();
    }
}
