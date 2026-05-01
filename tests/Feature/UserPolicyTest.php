<?php

declare(strict_types=1);

use App\Models\User;
use App\Policies\UserPolicy;

test('viewAny returns true for admin email', function () {
    $admin = User::factory()->make(['email' => 'axelcharpentier0@icloud.com']);

    expect((new UserPolicy)->viewAny($admin))->toBeTrue();
});

test('viewAny returns false for non-admin email', function () {
    $user = User::factory()->make(['email' => 'someone-else@example.com']);

    expect((new UserPolicy)->viewAny($user))->toBeFalse();
});

test('viewAny via Gate returns true for admin', function () {
    $admin = User::factory()->make(['email' => 'axelcharpentier0@icloud.com']);

    expect($admin->can('viewAny', User::class))->toBeTrue();
});

test('viewAny via Gate returns false for other user', function () {
    $user = User::factory()->make(['email' => 'invitee@example.com']);

    expect($user->can('viewAny', User::class))->toBeFalse();
});
