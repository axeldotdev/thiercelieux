<?php

declare(strict_types=1);

use App\Actions\Games\StartGame;
use App\Actions\Games\StopGame;
use App\Enums\Role;
use App\Enums\RolePack;
use App\Models\Game;
use App\Models\User;
use Illuminate\Validation\ValidationException;

it('starts a game and assigns shuffled roles', function () {
    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);
    $users = User::factory()->count(8)->create();
    $game->users()->attach($users->pluck('id')->all());

    (new StartGame)($game->fresh());

    $game->refresh();
    expect($game->started_at)->not->toBeNull();

    $assigned = $game->users()->get()->pluck('pivot.role');
    expect($assigned)->toHaveCount(8);
    expect($assigned->filter()->count())->toBe(8);

    $wolves = $assigned->filter(fn ($r) => $r === Role::LoupGarou->value)->count();
    expect($wolves)->toBe(2);
});

it('assigns at least 1 wolf with minimum players', function () {
    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);
    $users = User::factory()->count(4)->create();
    $game->users()->attach($users->pluck('id')->all());

    (new StartGame)($game->fresh());

    $assigned = $game->users()->get()->pluck('pivot.role');
    $wolves = $assigned->filter(fn ($r) => $r === Role::LoupGarou->value)->count();
    expect($wolves)->toBe(1);
});

it('blocks starting a game with fewer than the minimum players', function () {
    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);
    $users = User::factory()->count(3)->create();
    $game->users()->attach($users->pluck('id')->all());

    expect(fn () => (new StartGame)($game->fresh()))
        ->toThrow(ValidationException::class);
});

it('blocks starting a game when another is in progress', function () {
    Game::factory()->inProgress()->create(['roles' => [RolePack::Classic]]);

    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);
    $users = User::factory()->count(5)->create();
    $game->users()->attach($users->pluck('id')->all());

    expect(fn () => (new StartGame)($game->fresh()))
        ->toThrow(ValidationException::class);
});

it('blocks starting a game already started', function () {
    $game = Game::factory()->inProgress()->create(['roles' => [RolePack::Classic]]);

    expect(fn () => (new StartGame)($game))
        ->toThrow(ValidationException::class);
});

it('stops a game in progress', function () {
    $game = Game::factory()->inProgress()->create(['roles' => [RolePack::Classic]]);

    (new StopGame)($game);

    $game->refresh();
    expect($game->ended_at)->not->toBeNull();
});

it('blocks stopping a draft game', function () {
    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);

    expect(fn () => (new StopGame)($game))
        ->toThrow(ValidationException::class);
});

it('assigns unique special roles plus villagers and wolves', function () {
    $game = Game::factory()->create(['roles' => [RolePack::Classic]]);
    $users = User::factory()->count(12)->create();
    $game->users()->attach($users->pluck('id')->all());

    (new StartGame)($game->fresh());

    $assigned = $game->users()->get()->pluck('pivot.role')->all();
    $specials = collect($assigned)
        ->reject(fn ($r) => in_array($r, [Role::Villageois->value, Role::LoupGarou->value], true));

    expect($specials->duplicates())->toBeEmpty();
});

it('shows games settings page to admin', function () {
    $admin = User::factory()->create(['email' => 'axelcharpentier0@icloud.com']);

    $this->actingAs($admin)
        ->get(route('games.index'))
        ->assertOk();
});

it('forbids non-admin from settings games page', function () {
    $user = User::factory()->create(['email' => 'someone@example.com']);

    $this->actingAs($user)
        ->get(route('games.index'))
        ->assertForbidden();
});
