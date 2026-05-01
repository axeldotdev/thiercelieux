<?php

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.settings')]
#[Title('Users')]
class extends Component {
    use PasswordValidationRules;

    public string $name = '';

    public string $email = '';

    public ?int $userToDelete = null;

    public string $password = '';

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

    public function confirmDelete(int $userId): void
    {
        Gate::authorize('viewAny', User::class);

        if ($userId === Auth::id()) {
            return;
        }

        $this->userToDelete = $userId;
        $this->password = '';

        Flux::modal('confirm-user-deletion')->show();
    }

    public function deleteUser(): void
    {
        Gate::authorize('viewAny', User::class);

        if ($this->userToDelete === null || $this->userToDelete === Auth::id()) {
            return;
        }

        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        User::findOrFail($this->userToDelete)->delete();

        $this->reset(['userToDelete', 'password']);
        unset($this->users);

        Flux::modal('confirm-user-deletion')->close();
        Flux::toast(variant: 'success', text: __('User deleted.'));
    }
}; ?>

<section class="w-full max-w-7xl mx-auto lg:px-8">
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" data-test="users-list">
        @foreach ($this->users as $user)
            <flux:card :key="'user-'.$user->id" class="flex flex-col gap-4" data-test="user-card-{{ $user->id }}">
                <div class="flex items-center gap-4">
                    <flux:avatar :name="$user->name" :initials="$user->initials()" color="violet" />

                    <div class="min-w-0 flex-1">
                        <flux:heading class="truncate">{{ $user->name }}</flux:heading>
                        <flux:text class="truncate">{{ $user->email }}</flux:text>
                    </div>
                </div>

                <div class="flex flex-row justify-normal gap-2">
                    <flux:button size="sm" :href="route('users.qr', ['user' => $user->id])" target="_blank" rel="noopener" data-test="show-qr-{{ $user->id }}">
                        {{ __('View QR') }}
                    </flux:button>
                    <flux:button size="sm" wire:click="regenerateToken({{ $user->id }})" data-test="regenerate-{{ $user->id }}">
                        {{ __('Regenerate') }}
                    </flux:button>
                    @if ($user->id !== auth()->id())
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $user->id }})" data-test="delete-{{ $user->id }}">
                            {{ __('Delete') }}
                        </flux:button>
                    @endif
                </div>
            </flux:card>
        @endforeach
    </div>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete this user?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once this user is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this user.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" viewable />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                    {{ __('Delete user') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

</section>
