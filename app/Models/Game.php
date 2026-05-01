<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use App\Enums\RolePack;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['roles', 'started_at', 'ended_at'])]
final class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'roles' => AsEnumCollection::of(RolePack::class),
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isDraft(): bool
    {
        return $this->started_at === null;
    }

    public function isInProgress(): bool
    {
        return $this->started_at !== null && $this->ended_at === null;
    }

    public function isEnded(): bool
    {
        return $this->ended_at !== null;
    }

    public function pivotRole(int $userId): ?Role
    {
        $value = $this->users->firstWhere('id', $userId)?->pivot->role;

        return $value !== null ? Role::from($value) : null;
    }
}
