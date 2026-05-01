<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.settings')]
#[Title('Paramètres du profil')]
class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: 'Profil mis à jour.');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full max-w-7xl mx-auto lg:px-8">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Profil</flux:heading>
        <flux:subheading size="lg" class="mb-6">Modifier votre nom et votre adresse email</flux:subheading>
    </div>

    <form wire:submit="updateProfileInformation" class="my-12 w-full max-w-lg space-y-6">
        <flux:input wire:model="name" label="Nom" type="text" required autofocus autocomplete="name" />

        <div>
            <flux:input wire:model="email" label="Email" type="email" required autocomplete="email" />

            @if ($this->hasUnverifiedEmail)
                <div>
                    <flux:text class="mt-4">
                        Votre adresse email n'est pas vérifiée.

                        <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            Cliquez ici pour renvoyer l'email de vérification.
                        </flux:link>
                    </flux:text>

                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <flux:button variant="primary" type="submit" data-test="update-profile-button">
                Enregistrer
            </flux:button>
        </div>
    </form>

    @if ($this->showDeleteUser)
        <livewire:pages::settings.delete-user-form />
    @endif
</section>
