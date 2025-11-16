<?php

use App\Http\Controllers\Landing\BookController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'sliderImage' => asset('294-1200x800.jpg'),
    ]);
})->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');

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
    });
});

require __DIR__.'/auth.php';

// Marketing / public pages
Route::get('/services', [PageController::class, 'services'])->name('services.index');
Route::get('/services/{slug}', [PageController::class, 'serviceDetail'])->name('services.show');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::get('/news', [PageController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [PageController::class, 'newsDetail'])->name('news.show');
Route::get('/company-handbook', [PageController::class, 'companyHandbook'])->name('company.handbook');
Route::get('/products', [PageController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [PageController::class, 'productDetail'])->name('products.show');
Route::get('/payment', [PageController::class, 'payment'])->name('payment');
