<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scheme-only-dark">
    <head>
        @include('partials.head')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
        <style>
            .font-serif-display { font-family: 'Instrument Serif', ui-serif, Georgia, serif; }
            .font-mono-eyebrow { font-family: 'JetBrains Mono', ui-monospace, monospace; }

            @keyframes ember-rise {
                0%   { transform: translateY(0) translateX(0); opacity: 0; }
                10%  { opacity: 0.9; }
                90%  { opacity: 0.6; }
                100% { transform: translateY(-110vh) translateX(var(--drift, 20px)); opacity: 0; }
            }
            .ember {
                animation: ember-rise var(--dur, 9s) linear infinite;
                animation-delay: var(--delay, 0s);
            }

            @keyframes flame-flicker {
                0%, 100% { opacity: 0.85; transform: scale(1); }
                25%      { opacity: 1;    transform: scale(1.05); }
                50%      { opacity: 0.65; transform: scale(0.96); }
                75%      { opacity: 0.95; transform: scale(1.03); }
            }
            .flame-flicker { animation: flame-flicker 2.6s ease-in-out infinite; transform-origin: 50% 100%; }

            @keyframes moon-breathe {
                0%, 100% { opacity: 0.95; }
                50%      { opacity: 1; }
            }
            .moon-breathe { animation: moon-breathe 6s ease-in-out infinite; }

            @keyframes star-twinkle {
                0%, 100% { opacity: 0.2; }
                50%      { opacity: 1; }
            }
            .star { animation: star-twinkle var(--tw-dur, 4s) ease-in-out infinite; animation-delay: var(--tw-delay, 0s); }
        </style>
    </head>
    <body class="min-h-dvh antialiased font-sans text-stone-100 bg-stone-950">

        <div class="relative isolate min-h-dvh overflow-hidden bg-stone-950 flex flex-col">

            {{-- Sky gradient + stars --}}
            <div class="absolute inset-x-0 top-0 h-[55vh] bg-linear-to-b from-stone-950 via-stone-900 to-emerald-950/30"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-[55vh]">
                @for ($i = 0; $i < 24; $i++)
                    <span class="star absolute size-px rounded-full bg-stone-200"
                          style="left:{{ rand(2, 98) }}%; top:{{ rand(2, 45) }}%; --tw-dur:{{ rand(3, 7) }}s; --tw-delay:-{{ rand(0, 5) }}s;"></span>
                @endfor
            </div>

            {{-- Moon --}}
            <svg viewBox="0 0 200 200" class="absolute right-6 top-10 size-40 moon-breathe pointer-events-none" aria-hidden="true">
                <defs>
                    <radialGradient id="moonAcore" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#fef3c7"/>
                        <stop offset="70%" stop-color="#fde68a"/>
                        <stop offset="100%" stop-color="#fbbf24" stop-opacity="0.4"/>
                    </radialGradient>
                    <radialGradient id="moonAhalo" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#fde68a" stop-opacity="0.55"/>
                        <stop offset="100%" stop-color="#fde68a" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <circle cx="100" cy="100" r="95" fill="url(#moonAhalo)"/>
                <circle cx="100" cy="100" r="58" fill="url(#moonAcore)"/>
                <circle cx="118" cy="92" r="6" fill="#d97706" opacity="0.25"/>
                <circle cx="86" cy="115" r="4" fill="#d97706" opacity="0.22"/>
                <circle cx="102" cy="120" r="3" fill="#d97706" opacity="0.2"/>
            </svg>

            {{-- Pine forest mid silhouette --}}
            <svg viewBox="0 0 400 180" preserveAspectRatio="none" class="absolute inset-x-0 top-[34vh] h-44 w-full text-stone-950 pointer-events-none" aria-hidden="true">
                <path fill="currentColor" opacity="0.85" d="M0,180 L0,120 L20,80 L35,110 L55,60 L75,105 L95,55 L115,110 L140,75 L165,115 L190,65 L215,110 L240,80 L265,115 L290,70 L315,110 L340,85 L365,115 L390,75 L400,100 L400,180 Z"/>
            </svg>
            <svg viewBox="0 0 400 200" preserveAspectRatio="none" class="absolute inset-x-0 top-[40vh] h-52 w-full text-black pointer-events-none" aria-hidden="true">
                <path fill="currentColor" d="M0,200 L0,140 L25,100 L50,135 L80,75 L110,130 L140,90 L170,140 L200,80 L230,135 L260,95 L290,140 L320,85 L350,130 L380,100 L400,135 L400,200 Z"/>
            </svg>

            {{-- Cercle de pierres : foyer concentré au sol --}}
            <div class="pointer-events-none absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/3 size-80 rounded-full bg-orange-500/55 blur-3xl flame-flicker"></div>
            <div class="pointer-events-none absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/4 size-44 rounded-full bg-amber-300/55 blur-2xl flame-flicker [animation-delay:-1s]"></div>
            <div class="pointer-events-none absolute bottom-2 left-1/2 -translate-x-1/2 size-24 rounded-full bg-yellow-200/70 blur-xl flame-flicker [animation-delay:-0.5s]"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-48 bg-linear-to-t from-amber-700/25 via-transparent to-transparent"></div>

            {{-- Embers --}}
            <div class="pointer-events-none absolute inset-0">
                @for ($i = 0; $i < 22; $i++)
                    <span class="ember absolute size-1 rounded-full bg-amber-400 shadow-[0_0_6px_2px_rgba(251,191,36,0.7)]"
                        style="left:{{ rand(20, 80) }}%; bottom:-10px; --dur:{{ rand(7, 13) }}s; --delay:-{{ rand(0, 12) }}s; --drift:{{ rand(-40, 40) }}px;"></span>
                @endfor
            </div>

            <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-7">
                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
