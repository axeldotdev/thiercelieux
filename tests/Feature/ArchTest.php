<?php

declare(strict_types=1);

arch()->preset()->php();

arch()->preset()->laravel();

arch()->preset()->security()->ignoring('md5');

arch()->preset()->strict()->ignoring(\App\Models\User::class);
