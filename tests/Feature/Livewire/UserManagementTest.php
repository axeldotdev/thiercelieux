<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeUser(array $overrides = []): User
{
    return User::create(array_merge([
        'name' => 'Some User',
        'email' => 'user-'.Str::random(8).'@example.com',
        'password' => Hash::make('password'),
    ], $overrides));
}

test('non-admin cannot access users page', function () {
    $user = makeUser(['email' => 'invitee@example.com']);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('admin can render users page', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com']);

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertOk();
});

test('admin can create user with invitation token', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com']);

    $this->actingAs($admin);

    Livewire::test('pages::settings.users')
        ->set('name', 'Alice')
        ->set('email', 'alice@example.com')
        ->call('createUser')
        ->assertHasNoErrors();

    $created = User::where('email', 'alice@example.com')->first();

    expect($created)->not->toBeNull()
        ->and($created->name)->toEqual('Alice')
        ->and($created->invitation_token)->toBeString()
        ->and(strlen($created->invitation_token))->toBe(16);
});

test('admin can regenerate invitation token', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com']);
    $invitee = makeUser(['invitation_token' => 'oldtoken12345678']);

    $this->actingAs($admin);

    Livewire::test('pages::settings.users')
        ->call('regenerateToken', $invitee->id)
        ->assertHasNoErrors();

    expect($invitee->fresh()->invitation_token)->not->toEqual('oldtoken12345678');
});

test('admin can delete user with password confirmation', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com', 'password' => Hash::make('password')]);
    $invitee = makeUser();

    $this->actingAs($admin);

    Livewire::test('pages::settings.users')
        ->call('confirmDelete', $invitee->id)
        ->set('password', 'password')
        ->call('deleteUser')
        ->assertHasNoErrors();

    expect(User::find($invitee->id))->toBeNull();
});

test('delete fails with wrong password', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com', 'password' => Hash::make('password')]);
    $invitee = makeUser();

    $this->actingAs($admin);

    Livewire::test('pages::settings.users')
        ->call('confirmDelete', $invitee->id)
        ->set('password', 'wrong')
        ->call('deleteUser')
        ->assertHasErrors(['password']);

    expect(User::find($invitee->id))->not->toBeNull();
});

test('admin cannot delete themselves from users page', function () {
    $admin = makeUser(['email' => 'axelcharpentier0@icloud.com', 'password' => Hash::make('password')]);

    $this->actingAs($admin);

    Livewire::test('pages::settings.users')
        ->call('confirmDelete', $admin->id)
        ->assertSet('userToDelete', null);

    expect(User::find($admin->id))->not->toBeNull();
});
