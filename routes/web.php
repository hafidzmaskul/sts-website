<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Create route names and controller-based routes for main and right navigation items
// Public Routes
Route::controller(\App\Http\Controllers\PageController::class)->group(function () {
    Route::get('/', 'landing')->name('home');
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{slug}', 'serviceDetail')->name('services.detail');
    Route::get('/products', 'products')->name('products');
    Route::get('/products/{slug}', 'productDetail')->name('products.detail');
    Route::get('/news', 'news')->name('news');
    Route::get('/news/{slug}', 'newsDetail')->name('news.detail');
    Route::get('/careers', 'career')->name('careers');
    Route::get('/careers/{slug}', 'careerDetail')->name('careers.detail');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/company-handbook', 'companyHandbook')->name('company-handbook');
    Route::get('/payment/{slug}', 'payment')->name('payment');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::view('dashboard', 'dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

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
        Route::get('/admin/banners', \App\Livewire\Admin\Banners\Index::class)->name('admin.banners.index');
        Route::get('/admin/news-categories', \App\Livewire\Admin\NewsCategories\Index::class)->name('admin.news-categories.index');
        Route::get('/admin/news', \App\Livewire\Admin\News\Index::class)->name('admin.news.index');

    });
});

require __DIR__.'/auth.php';
