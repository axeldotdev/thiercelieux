<?php

declare(strict_types=1);
use App\Models\Game;
use App\Models\User;

arch()->preset()->php();

arch()->preset()->laravel();

arch()->preset()->security()->ignoring('md5');

arch()->preset()->strict()->ignoring([User::class, Game::class]);
