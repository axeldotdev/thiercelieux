<div>
    <a href="{{ route('home') }}" class="inline-flex items-center justify-center" wire:navigate>
        <x-app-logo-icon class="size-28 object-contain drop-shadow-[0_0_22px_rgba(251,191,36,0.55)]" />
        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
    </a>

    @isset($title)
        <h1 class="mt-8 font-serif-display text-4xl text-stone-50 leading-tight text-balance">
            {{ $title }}
        </h1>
    @endisset

    @isset($subtitle)
        <p class="mt-3 text-sm text-stone-300 text-pretty max-w-md mx-auto">
            {{ $subtitle }}
        </p>
    @endisset

    {{ $slot }}
</div>
