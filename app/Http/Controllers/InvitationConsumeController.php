<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class InvitationConsumeController
{
    public function __invoke(string $token): RedirectResponse
    {
        $user = User::where('invitation_token', $token)->firstOrFail();

        if (Auth::check() && Auth::id() === $user->id) {
            return redirect()->route('dashboard');
        }

        if (Auth::check()) {
            Auth::logout();
        }

        Auth::login($user, true);

        return redirect()->route('home');
    }
}
