<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');



        Route::middleware(['role:admin'])->group(function () {
            Route::get('/admin/roles',  \App\Livewire\Admin\Roles\Index::class)->name('admin.roles.index');
            Route::get('/admin/users',  \App\Livewire\Admin\Users\Index::class)->name('admin.users.index');
            Route::get('/admin/settings', \App\Livewire\Admin\Settings\Index::class)->name('admin.settings.index')->middleware('can:settings.view');

            // --- ADD THIS LINE ---
            Route::get('/admin/testimonials', \App\Livewire\Admin\Testimonials\Index::class)->name('admin.testimonials.index')->middleware('can:testimonials.view');
            // --- END OF NEW LINE ---
        });
});

require __DIR__.'/auth.php';