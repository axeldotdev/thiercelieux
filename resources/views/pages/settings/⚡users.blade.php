<?php

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Users')] class extends Component {
    public string $name = '';

    public string $email = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', User::class);
    }

    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    public function createUser(): void
    {
        Gate::authorize('viewAny', User::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(40)),
            'invitation_token' => Str::random(16),
        ]);

        $this->reset(['name', 'email']);
        unset($this->users);

        Flux::toast(variant: 'success', text: __('User created.'));
    }

    public function regenerateToken(int $userId): void
    {
        Gate::authorize('viewAny', User::class);

        User::findOrFail($userId)->regenerateInvitationToken();

        unset($this->users);

        Flux::toast(variant: 'success', text: __('Invitation token regenerated.'));
    }

    public function deleteUser(int $userId): void
    {
        Gate::authorize('viewAny', User::class);

        User::findOrFail($userId)->delete();

        unset($this->users);

        Flux::toast(variant: 'success', text: __('User deleted.'));
    }
}; ?>

<section class="w-full max-w-7xl mx-auto px-6 lg:px-8">
    <div class="relative w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage players and invitation QR codes') }}</flux:subheading>
    </div>

    <form wire:submit="createUser" class="my-12 w-full max-w-lg space-y-4" data-test="create-user-form">
        <flux:input wire:model="name" :label="__('Name')" type="text" required autocomplete="off" />
        <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="off" />

        <flux:button variant="primary" type="submit" data-test="create-user-button">
            {{ __('Add user') }}
        </flux:button>
    </form>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Email') }}</flux:table.column>
            <flux:table.column>{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->users as $user)
                <flux:table.row :key="'user-'.$user->id">
                    <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-wrap gap-2">
                            <flux:button size="xs" :href="route('users.qr', ['user' => $user->id])" target="_blank" rel="noopener" data-test="show-qr-{{ $user->id }}">
                                {{ __('View QR') }}
                            </flux:button>
                            <flux:button size="xs" wire:click="regenerateToken({{ $user->id }})" data-test="regenerate-{{ $user->id }}">
                                {{ __('Regenerate') }}
                            </flux:button>
                            <flux:button size="xs" variant="danger" wire:click="deleteUser({{ $user->id }})" wire:confirm="{{ __('Delete this user?') }}" data-test="delete-{{ $user->id }}">
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

</section>
