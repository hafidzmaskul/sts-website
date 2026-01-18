<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public bool $loading = false;
    public string $error = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->loading = true;
        $this->error = '';

        try {
            $this->validate();

            $this->ensureIsNotRateLimited();

            $user = $this->validateCredentials();

            // Prevent non-admin users from logging into the admin area
            if ($user->hasAnyRole(['customer', 'trade account', 'credit facilities account'])) {
                // If somehow already authenticated, ensure logout
                try {
                    Auth::logout();
                } catch (\Throwable $_) {
                }

                // Dispatch a browser toast message (matches other Livewire components)
                $this->dispatch('notify', type: 'error', message: 'You are not admin.');

                // Set an error message for the form as well
                $this->error = 'You are not admin.';

                // Stop further processing
                $this->loading = false;
                return;
            }

            if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
                Session::put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $this->remember,
                ]);

                $this->redirect(route('two-factor.login'), navigate: true);
                $this->loading = false;
                return;
            }

            Auth::login($user, $this->remember);

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();

            if ($user->hasAnyRole(['customer', 'trade account', 'credit facilities account'])) {
                $this->redirect(route('home'), navigate: true);
            } else {
                $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            }
        } catch (ValidationException $e) {
            $this->error = $e->validator->errors()->first();
            // Optional: handle showing error
        } finally {
            $this->loading = false;
        }
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        if (!$user || !Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>


<div class=" ">
    <form wire:submit.prevent="login" class=" p-10 w-full bg-white   mx-auto">
        <h1 class="font-inter capitalize text-center text-black font-bold text-2xl mb-10">Welcome to admin area</h1>
        @if (!empty($error))
            <div class="text-red-600 text-sm mb-4 text-center">{{ $error }}</div>
        @endif
        <div class="w-full">
            <div class="relative ">
                <input type="email" id="email" name="email" wire:model="email" placeholder=" " style="height: 52px"
                    class="w-full px-3 pt-6 pb-2 border outline-none transition-colors duration-200 focus:border-[#212121] border-[#E0E0E0] peer rounded"
                    required autofocus autocomplete="email" @disabled($loading) />
                <label for="email"
                    class="absolute left-3 top-3 text-gray-500 text-sm transition-all duration-200 peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs pointer-events-none bg-white px-1">
                    Email Address
                </label>
                @error('email')
                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="w-full mb-5">
            <div class="relative" x-data="{ showPassword: false }">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" wire:model="password"
                    placeholder=" " style="height: 52px"
                    class="w-full px-3 pt-6 pb-2 pr-12 border outline-none transition-colors duration-200 focus:border-[#212121] border-[#E0E0E0] peer rounded"
                    required autocomplete="current-password" @disabled($loading) />
                <label for="password"
                    class="absolute left-3 top-3 text-gray-500 text-sm transition-all duration-200 peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs pointer-events-none bg-white px-1">
                    Password
                </label>
                <button type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#212121] transition-colors flex items-center"
                    tabindex="-1" @click="showPassword = !showPassword" style="background: transparent;">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7" />
                    </svg>
                </button>
                @error('password')
                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-between items-center mb-8">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" wire:model="remember" class="form-checkbox  mr-2" id="remember" name="remember"
                    @disabled($loading) />
                <span class="text-sm text-gray-700 select-none">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-[#1976D2] hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="relative">
            <button type="submit"
                class="bg-[#039BE5] text-white w-full py-3 rounded-lg font-semibold text-lg disabled:opacity-50 shadow"
                @disabled($loading)>
                {{ $loading ? __('Signing in...') : __('Sign in') }}
            </button>
        </div>
    </form>
</div>
<!-- Alpine.js for toggle password (if not loaded globally, load via CDN) -->
<!-- /end Alpine.js block -->
