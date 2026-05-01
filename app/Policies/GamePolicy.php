<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->email === 'axelcharpentier0@icloud.com';
    }
}
