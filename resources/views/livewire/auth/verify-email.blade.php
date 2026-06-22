<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    /**
     * Handle the component's rendering hook.
     */
    public function rendering(View $view): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }
    }
}; ?>

<div class="flex flex-col gap-6 bg-white p-10 rounded-2xl">
    <x-auth-header :title="__('Verify email')" :description="__('Please verify your email address by clicking on the link we just emailed to you.')" />

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="text-center" :status="__('A new verification link has been sent to the email address you provided during registration.')" />
    @endif

    <div class="flex flex-col gap-6">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Resend verification email') }}
        </flux:button>
    </div>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Or,') }}</span>
        <flux:link class="cursor-pointer" wire:click="logout" data-test="logout-button">{{ __('log out') }}</flux:link>
    </div>
</div>
