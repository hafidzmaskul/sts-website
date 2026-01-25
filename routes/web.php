<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Create route names and controller-based routes for main and right navigation items
// Public Routes
Route::controller(\App\Http\Controllers\PageController::class)->group(function () {
    Route::get('/', 'landing')->name('home');
    Route::get('/services', 'services')->name('services');
    Route::get('/configuration', 'configuration')->name('configuration');
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
    Route::get('/cart', 'cart')->name('cart');
    Route::get('/liked-products', 'likedProducts')->name('liked-products');
    Route::get('/payment/{slug}', 'payment')->name('payment');
    Route::get('become-customer', 'becomeCustomer')->name('become-customer');
    Route::get('sign-up-customer', 'signUpCustomer')->name('sign-up');
    Route::get('sign-up-credit-facility', 'signUpFacility')->name('sign-up');
    Route::get('contact-us', 'contactUs')->name('contact-us');
    Route::get('training', 'training')->name('training');
    Route::get('commisioning', 'commisioning')->name('commisioning');
    Route::get('system-design', 'systemDesign')->name('system-design');
    Route::get('login-page', 'login')->name('login-page');
    Route::get('invoice', 'invoice')->name('invoice');
    Route::get('checkout', 'checkout')->name('checkout');
    Route::get('/search', 'search')->name('search');
    Route::get('/search', 'search')->name('search');
});

// Masquerade Route (Signed, bypasses auth)
Route::get('/admin/masquerade/{userId}', [\App\Http\Controllers\Admin\ImpersonationController::class, 'masquerade'])
    ->name('admin.users.masquerade')
    ->middleware('signed');

Route::middleware(['auth'])->group(function () {

    Route::controller(\App\Http\Controllers\Api\QuoteBuilderController::class)
        ->prefix('web/quote-builder')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            Route::post('/{id}/products', 'addProduct');
            Route::delete('/{id}/products/{productId}', 'removeProduct');
            Route::get('/{id}/related-products', 'getRelatedProducts');
        });

    Route::get('web/my-transactions', [\App\Http\Controllers\Api\TransactionController::class, 'index']);

    Route::get('/my-transactions', function () {
        return \Inertia\Inertia::render('TransactionHistory');
    })->name('my-transactions');

    Route::get('web/liked-products', [\App\Http\Controllers\Api\ProductLikeController::class, 'index']);
    Route::post('web/products/like', [\App\Http\Controllers\Api\ProductLikeController::class, 'like']);
    Route::post('web/products/unlike', [\App\Http\Controllers\Api\ProductLikeController::class, 'unlike']);
    Route::get('web/coupons', [\App\Http\Controllers\Api\CouponController::class, 'index']);
    Route::get('web/coupons/{code}', [\App\Http\Controllers\Api\CouponController::class, 'show']);
});

Route::middleware([\App\Http\Middleware\EnsureGuestUser::class])->group(function () {
    Route::controller(\App\Http\Controllers\Api\CartController::class)
        ->prefix('web/cart')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/decrease', 'decrease');
            Route::delete('/{productId}', 'destroy');
        });

    Route::post('web/transactions', [\App\Http\Controllers\Api\TransactionController::class, 'store']);
    Route::get('web/my-transactions/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'show']);

    Route::get('/my-transactions/{id}', function ($id) {
        return \Inertia\Inertia::render('TransactionDetail', ['id' => $id]);
    })->name('my-transactions.show');

    Route::post('/web/product-requests', [\App\Http\Controllers\Api\ProductRequestController::class, 'store']);


    Route::get('/quote-checkout', function (\Illuminate\Http\Request $request) {
        $quoteIds = $request->input('quote_ids');
        // Ensure quoteIds is always an array or null
        if ($quoteIds && !is_array($quoteIds)) {
            $quoteIds = [$quoteIds];
        }
        $auth = Auth::user()->load(['customer.company', 'roles']);
        return \Inertia\Inertia::render('QuoteCheckout', [
            'quoteIds' => $quoteIds,
            'auth' => $auth
        ]);
    })->name('quote-checkout');


    Route::get('quote-builder', [\App\Http\Controllers\PageController::class, 'quoteBuilder'])->name('quote-builder');

    Route::redirect('settings', 'settings/profile');

    Route::get('/dashboard', \App\Livewire\Dashboard::class)
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
        Route::get('/admin/newsletter', \App\Livewire\Admin\Newsletter\Index::class)->name('admin.newsletter.index');
        Route::get('/admin/product-categories', \App\Livewire\Admin\ProductCategories\Index::class)->name('admin.product-categories.index');
        Route::get('/admin/products', \App\Livewire\Admin\Products\Index::class)->name('admin.products.index');
        Route::get('/admin/products/create', \App\Livewire\Admin\Products\Create::class)->name('admin.products.create');
        Route::get('/admin/products/{product}/edit', \App\Livewire\Admin\Products\Edit::class)->name('admin.products.edit');
        Route::get('/admin/products/{product}', \App\Livewire\Admin\Products\Show::class)->name('admin.products.show');

        // Customers
        Route::get('/admin/customers', \App\Livewire\Admin\Customers\Index::class)->name('admin.customers.index');
        Route::get('/admin/customers/{customer}', \App\Livewire\Admin\Customers\Show::class)->name('admin.customers.show');
        Route::get('/admin/customers/{customer}/edit', \App\Livewire\Admin\Customers\Edit::class)->name('admin.customers.edit');

        // Companies
        Route::get('/admin/companies', \App\Livewire\Admin\Companies\Index::class)->name('admin.companies.index');
        Route::get('/admin/companies/{company}', \App\Livewire\Admin\Companies\Show::class)->name('admin.companies.show');

        // Contact Submissions
        Route::get('/admin/contact-submissions', App\Livewire\Admin\ContactSubmissions\Index::class)->name('admin.contact-submissions.index');

        // Product Requests
        Route::get('/admin/product-requests', \App\Livewire\Admin\ProductRequests\Index::class)->name('admin.product-requests.index');
        Route::get('/admin/product-requests/{productRequest}', \App\Livewire\Admin\ProductRequests\Show::class)->name('admin.product-requests.show');

        // Brands
        Route::get('/admin/brands', App\Livewire\Admin\Brands\Index::class)->name('admin.brands.index');
        Route::get('/admin/brands/create', App\Livewire\Admin\Brands\Create::class)->name('admin.brands.create');
        Route::get('/admin/brands/{brand}/edit', App\Livewire\Admin\Brands\Edit::class)->name('admin.brands.edit');

        // Quotes
        Route::get('/admin/quotes', \App\Livewire\Admin\Quotes\Index::class)->name('admin.quotes.index');
        Route::get('/admin/quotes/{quote}', \App\Livewire\Admin\Quotes\Show::class)->name('admin.quotes.show');

        // Transactions
        Route::get('/admin/transactions', \App\Livewire\Admin\Transactions\Index::class)->name('admin.transactions.index');
        Route::get('/admin/transactions/{transaction}', \App\Livewire\Admin\Transactions\Show::class)->name('admin.transactions.show');

        // RMA Requests
        Route::get('/admin/rma-requests', \App\Livewire\Admin\RmaRequests\Index::class)->name('admin.rma-requests.index');

        // Coupons
        Route::get('/admin/coupons', App\Livewire\Admin\Coupons\Index::class)->name('admin.coupons.index');
        Route::get('/admin/coupons/create', App\Livewire\Admin\Coupons\Create::class)->name('admin.coupons.create');
        Route::get('/admin/coupons/{coupon}', App\Livewire\Admin\Coupons\Show::class)->name('admin.coupons.show');
        Route::get('/admin/coupons/{coupon}/edit', App\Livewire\Admin\Coupons\Edit::class)->name('admin.coupons.edit');

        // Pricing Formulas
        Route::get('/admin/pricing-formulas', \App\Livewire\Admin\PricingFormulas\Index::class)->name('admin.pricing-formulas.index');
        Route::get('/admin/pricing-formulas/create', \App\Livewire\Admin\PricingFormulas\Create::class)->name('admin.pricing-formulas.create');
        Route::get('/admin/pricing-formulas/{pricingFormula}/edit', \App\Livewire\Admin\PricingFormulas\Edit::class)->name('admin.pricing-formulas.edit');

        // Settings
        Route::get('/admin/settings/general', \App\Livewire\Admin\Settings\General::class)->name('admin.settings.general');

    });

    // Parent User Management
    Route::get('/dashboard/users', \App\Livewire\FixedRole\ChildUsers\Index::class)->name('dashboard.users.index');

    // Transaction Management for Fixed Roles
    Route::get('/dashboard/transactions', \App\Livewire\FixedRole\Transactions\Index::class)->name('dashboard.transactions.index');
    Route::get('/dashboard/transactions/{transaction}', \App\Livewire\FixedRole\Transactions\Show::class)->name('dashboard.transactions.show');

    // Credit Limit Management for Credit Facilities
    Route::get('/dashboard/credit-limits', \App\Livewire\FixedRole\CreditLimits\Index::class)->name('dashboard.credit-limits.index');

    // Company Management
    Route::get('/dashboard/company', \App\Livewire\FixedRole\Company\Show::class)->name('dashboard.company.show');
});

require __DIR__ . '/auth.php';
