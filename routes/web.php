<?php

declare(strict_types=1);

use App\Http\Controllers\InvitationConsumeController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome')->name('home');

Route::get('/i/{token}', InvitationConsumeController::class)
    ->middleware('throttle:10,1')
    ->name('invitation.consume');

Route::middleware(['auth'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
