<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('logs in guest and redirects home', function () {
    $user = User::factory()->create(['invitation_token' => 'token-guest']);

    $response = $this->get('/i/token-guest');

    $response->assertRedirect(route('home'));
    expect(Auth::id())->toBe($user->id);
});

it('redirects same authenticated user to dashboard without re-login', function () {
    $user = User::factory()->create(['invitation_token' => 'token-same']);

    $response = $this->actingAs($user)->get('/i/token-same');

    $response->assertRedirect(route('dashboard'));
    expect(Auth::id())->toBe($user->id);
});

it('logs out other user and logs in invitation owner', function () {
    $current = User::factory()->create();
    $target = User::factory()->create(['invitation_token' => 'token-switch']);

    $response = $this->actingAs($current)->get('/i/token-switch');

    $response->assertRedirect(route('home'));
    expect(Auth::id())->toBe($target->id);
});

it('returns 404 for invalid token', function () {
    $response = $this->get('/i/does-not-exist');

    $response->assertNotFound();
});
