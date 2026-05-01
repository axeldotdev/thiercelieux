<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');

    Route::livewire('settings/users', 'pages::settings.users')
        ->name('users.index')
        ->can('viewAny', User::class);

    Route::get('/settings/users/{user}/qr.svg', function (User $user) {
        Gate::authorize('viewAny', User::class);

        return response($user->invitationQrSvg(), 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store',
        ]);
    })->name('users.qr');
});
