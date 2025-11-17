<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Create route names and controller-based routes for main and right navigation items

Route::get('/', [App\Http\Controllers\PageController::class, 'landing'])->name('home');
Route::get('/services', [App\Http\Controllers\PageController::class, 'services'])->name('services');
Route::get('/service/{uuid}', [App\Http\Controllers\PageController::class, 'serviceDetail'])->name('service_detail');
Route::get('/products', [App\Http\Controllers\PageController::class, 'products'])->name('products');
Route::get('/news', [App\Http\Controllers\PageController::class, 'news'])->name('news');

Route::get('/about-us', [App\Http\Controllers\PageController::class, 'about'])->name('about-us');
Route::get('/contact-us', [App\Http\Controllers\PageController::class, 'contact'])->name('contact-us');

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
        Route::get('/admin/roles', \App\Livewire\Admin\Roles\Index::class)->name('admin.roles.index');
        Route::get('/admin/users', \App\Livewire\Admin\Users\Index::class)->name('admin.users.index');
        Route::get('/admin/settings', \App\Livewire\Admin\Settings\Index::class)->name('admin.settings.index')->middleware('can:settings.view');
        Route::get('/admin/testimonials', \App\Livewire\Admin\Testimonials\Index::class)->name('admin.testimonials.index')->middleware('can:testimonials.view');
        Route::get('/admin/newsletter-subscriptions', \App\Livewire\Admin\NewsletterSubscriptions\Index::class)->name('admin.newsletter-subscriptions.index')->middleware('can:newsletter-subscriptions.view');
        Route::get('/admin/our-team', \App\Livewire\Admin\OurTeam\Index::class)->name('admin.our-team.index')->middleware('can:our-team.view');

        Route::get('/admin/contact-submissions', \App\Livewire\Admin\ContactSubmissions\Index::class)->name('admin.contact-submissions.index')->middleware('can:contact-submissions.view');
    });
});

require __DIR__.'/auth.php';
