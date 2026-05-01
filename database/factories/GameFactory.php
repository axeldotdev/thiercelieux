<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RolePack;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
final class GameFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'roles' => [RolePack::Classic],
            'started_at' => null,
            'ended_at' => null,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn () => ['started_at' => now()]);
    }

    public function ended(): static
    {
        return $this->state(fn () => [
            'started_at' => now()->subHour(),
            'ended_at' => now(),
        ]);
    }
}
