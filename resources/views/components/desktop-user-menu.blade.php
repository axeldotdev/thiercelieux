<flux:dropdown position="bottom" align="end">
    <flux:sidebar.profile
        :chevron="false"
        avatar:color="violet"
        :initials="auth()->user()->initials()"
        data-test="sidebar-menu-button"
    />

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
            />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                Profil
            </flux:menu.item>
            @can('viewAny', App\Models\User::class)
                <flux:menu.item :href="route('users.index')" icon="users" wire:navigate>
                    Utilisateurs
                </flux:menu.item>
            @endcan
            @can('viewAny', App\Models\Game::class)
                <flux:menu.item :href="route('games.index')" icon="puzzle-piece" wire:navigate>
                    Parties
                </flux:menu.item>
            @endcan
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    Déconnexion
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
