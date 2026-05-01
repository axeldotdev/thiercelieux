<?php

declare(strict_types=1);

namespace App\Enums;

enum RolePack: string
{
    case Classic = 'classic';
    case NewMoon = 'new_moon';
    case Characters = 'characters';

    /**
     * @return array<int, Role>
     */
    public function roles(): array
    {
        return match ($this) {
            self::Classic => Role::classic(),
            self::NewMoon => Role::newMoon(),
            self::Characters => Role::characters(),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Classic => 'Classique',
            self::NewMoon => 'Nouvelle Lune',
            self::Characters => 'Personnages',
        };
    }
}
