<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

use function Laravel\Prompts\password;

#[Signature('users:create {name} {email}')]
#[Description('Create a user with an invitation token.')]
final class CreateUserCommand extends Command
{
    private const ADMIN_EMAIL = 'axelcharpentier0@icloud.com';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $email = (string) $this->argument('email');

        if ($email === self::ADMIN_EMAIL) {
            $plain = password(
                label: 'Mot de passe administrateur',
                required: true,
                validate: fn (string $value) => strlen($value) < 8
                    ? 'Au moins 8 caractères.'
                    : null,
            );

            $confirm = password(
                label: 'Confirmer le mot de passe',
                required: true,
            );

            if ($plain !== $confirm) {
                $this->error('Les mots de passe ne correspondent pas.');

                return self::FAILURE;
            }

            $hashed = Hash::make($plain);
        } else {
            $hashed = Hash::make(Str::random(40));
        }

        $token = Str::random(16);

        $user = new User;
        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => $hashed,
            'invitation_token' => $token,
        ])->save();

        $this->info("Utilisateur créé : {$user->name} <{$user->email}>");

        try {
            $invitationUrl = route('invitation.consume', ['token' => $token]);
            $this->line("URL d'invitation : {$invitationUrl}");
        } catch (\Throwable) {
            $this->line("Token d'invitation : {$token}");
        }

        if (Route::has('users.index')) {
            $this->line('Aperçu UI : '.route('users.index'));
        }

        return self::SUCCESS;
    }
}
