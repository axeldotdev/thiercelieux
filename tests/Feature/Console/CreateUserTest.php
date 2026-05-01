<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin email prompts for password and stores hashed value', function () {
    $this->artisan('users:create', [
        'name' => 'Axel',
        'email' => 'axelcharpentier0@icloud.com',
    ])
        ->expectsQuestion('Mot de passe administrateur', 's3cretPass!')
        ->expectsQuestion('Confirmer le mot de passe', 's3cretPass!')
        ->assertSuccessful();

    $user = User::where('email', 'axelcharpentier0@icloud.com')->firstOrFail();

    expect($user->name)->toBe('Axel');
    expect(Hash::check('s3cretPass!', $user->password))->toBeTrue();
});

test('admin email mismatch fails', function () {
    $this->artisan('users:create', [
        'name' => 'Axel',
        'email' => 'axelcharpentier0@icloud.com',
    ])
        ->expectsQuestion('Mot de passe administrateur', 's3cretPass!')
        ->expectsQuestion('Confirmer le mot de passe', 'different!')
        ->assertFailed();

    expect(User::where('email', 'axelcharpentier0@icloud.com')->exists())->toBeFalse();
});

test('guest email gets random password without prompt', function () {
    $this->artisan('users:create', [
        'name' => 'Alice',
        'email' => 'alice@example.com',
    ])->assertSuccessful();

    $user = User::where('email', 'alice@example.com')->firstOrFail();

    expect($user->password)->not->toBeEmpty();
    expect(Hash::check('', $user->password))->toBeFalse();
});

test('invitation_token is 16 chars and unique', function () {
    $this->artisan('users:create', [
        'name' => 'Bob',
        'email' => 'bob@example.com',
    ])->assertSuccessful();

    $this->artisan('users:create', [
        'name' => 'Carol',
        'email' => 'carol@example.com',
    ])->assertSuccessful();

    $bob = User::where('email', 'bob@example.com')->firstOrFail();
    $carol = User::where('email', 'carol@example.com')->firstOrFail();

    expect(strlen($bob->invitation_token))->toBe(16);
    expect(strlen($carol->invitation_token))->toBe(16);
    expect($bob->invitation_token)->not->toBe($carol->invitation_token);
});
