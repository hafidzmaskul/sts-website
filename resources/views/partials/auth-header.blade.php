@php
    $isLoggedIn = auth()->check();
    $user = auth()->user();
    $isGuest = $isLoggedIn && $user->hasRole('guest');
    $logged = $isLoggedIn && !$isGuest;
    $productCategories = \App\Models\ProductCategory::with('children')->whereNull('parent_id')->get();

    $cartCount = 0;
    if ($isLoggedIn) {
        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') ?: 0;
    }

    $pages = [
        ['href' => '/contact-us', 'label' => 'Contact Us'],
        ['href' => '/training', 'label' => 'Training'],
        ['href' => '/commisioning', 'label' => 'Commisioning'],
        ['href' => '/system-design', 'label' => 'System Design'],
        ['href' => '/news', 'label' => 'News'],
    ];

    $becomeCustomerMenu = [
        ['href' => '/sign-up-customer', 'label' => 'Sign up as Trade'],
        ['href' => '/sign-up-credit-facility', 'label' => 'Sign up as Credit facilitator'],
    ];
@endphp

<header class="w-full bg-white border-gray-100 text-[#232323] font-sans z-50 relative" x-data="{
    sideNavOpen: false,
    showCategories: false,
    showPages: false,
    showPagesMobile: false,
    showBecomeCustomer: false,
    showBecomeCustomerMobile: false,
    showCartDropdown: false,
    showLikedDropdown: false,
    expandedCategories: []
}">
    <!-- Top Bar -->
    <div class="bg-[#0079C2] font-inter font-light text-[#fff]">
        <div class="container mx-auto flex justify-between items-center py-4 px-4 md:px-20 text-sm">
            <div class="flex">
                <span>Free shipping on all orders over $50</span>
            </div>
            <div class="flex space-x-4 items-center">
                <a href="/contact-us" class="hover:underline">Need help?</a>
            </div>
        </div>
    </div>

    <!-- Responsive Navbar -->
    <div class="bg-[#F0F2F3]">
        <div class="container mx-auto flex items-center justify-between px-4 md:px-20 relative">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/assets/logo.png" alt="Logo" class="h-16 md:h-20 w-auto" />
            </a>

            <!-- Hamburger menu toggle only on <= md -->
            <button
                class="flex md:hidden items-center px-3 py-2 rounded text-[#007580] focus:outline-none focus:ring-2 focus:ring-blue-500"
                @click="sideNavOpen = true" aria-label="Open menu">
                <svg class="fill-current h-6 w-6" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4 5h16M4 12h16M4 19h16" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
            </button>

            <!-- Search -->
            <div class="flex-1 flex justify-center mx-2 md:mx-6">
                <div class="w-full max-w-lg relative hidden md:block">
                    <form action="/search" method="GET">
                        <input type="text" name="q" placeholder="Search products..."
                            class="w-full border rounded-lg pl-4 pr-10 py-2 focus:outline-none border-gray-300 focus:border-yellow-400 transition" />
                        <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1012 19.5a7.5 7.5 0 004.65-2.85z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart & User Icons (Desktop Matching) -->
            <div class="flex items-center space-x-2 md:space-x-5">
                @if (!$isLoggedIn || $isGuest)
                    <a href="/login"
                        class="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition">
                        Login
                    </a>
                @endif

                @if ($isLoggedIn || $isGuest)


                    <div class="relative" @mouseenter="showCartDropdown = true" @mouseleave="showCartDropdown = false">
                        <a href="/cart" class="flex items-center bg-white rounded-xl p-2 md:p-3 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold mr-2 hidden md:inline">Cart</span>
                            <div
                                class="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white">
                                {{ $cartCount }}
                            </div>
                        </a>
                        <div x-show="showCartDropdown"
                            class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 shadow-lg z-50 rounded overflow-y-auto max-h-80 min-h-12 min-w-[180px]"
                            x-transition x-cloak>
                            <div class="py-8 text-center text-gray-500">
                                Please view cart page for details
                            </div>
                            <div class="border-t px-4 py-2">
                                <a href="/cart"
                                    class="block w-full text-center text-[#007580] hover:underline font-semibold">View
                                    Cart</a>
                            </div>
                        </div>
                    </div>

                    @if ($logged)
                        <div class="relative hidden md:flex" @mouseenter="showLikedDropdown = true"
                            @mouseleave="showLikedDropdown = false">
                            <a href="/liked-products" class="bg-white rounded-xl p-2 md:p-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229">
                                    </path>
                                </svg>
                            </a>
                            <div x-show="showLikedDropdown"
                                class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 shadow-lg z-50 rounded overflow-y-auto max-h-80 min-h-12 min-w-[180px]"
                                x-transition x-cloak>
                                <div class="py-8 text-center text-gray-500">No liked products</div>
                                <div class="border-t px-4 py-2">
                                    <a href="/liked-products"
                                        class="block w-full text-center text-[#007580] hover:underline font-semibold">View
                                        Liked Products</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($logged)
                        <a class="bg-white rounded-xl p-2 md:p-3 hidden md:flex" href="/dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="1.5">
                                    <path
                                        d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669"
                                        clip-rule="evenodd"></path>
                                    <path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path>
                                </g>
                            </svg>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- SidenavOverlay -->
    <div x-show="sideNavOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
        @click="sideNavOpen = false" x-cloak></div>

    <!-- Sidenav -->
    <nav x-show="sideNavOpen" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white z-50 shadow-lg md:hidden overflow-y-auto" x-cloak>
        <div class="flex items-center justify-between px-4 py-4 border-b">
            <a href="/">
                <img src="/assets/logo.png" alt="Logo" class="h-12 w-auto" />
            </a>
            <button @click="sideNavOpen = false" class="text-gray-700 p-2"><svg class="h-6 w-6" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg></button>
        </div>
        <div class="px-4 py-3">
            <form action="/search" method="GET" class="relative">
                <input type="text" name="q" placeholder="Search products..."
                    class="w-full border rounded-lg pl-4 pr-10 py-2 focus:outline-none border-gray-300 focus:border-yellow-400 transition" />
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400"><svg
                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1012 19.5a7.5 7.5 0 004.65-2.85z" />
                    </svg></button>
            </form>
        </div>
        <ul class="flex flex-col px-4 space-y-2 mt-2 text-[#232323] font-semibold">
            <li>
                <div class="relative">
                    <button
                        class="flex items-center w-full py-2 px-4 rounded hover:bg-gray-100 text-[#636270] transition"
                        @click="showCategories = !showCategories">
                        All Categories
                        <svg class="ml-1 w-4 h-4 transition-transform" :class="showCategories ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="showCategories" class="mt-1 ml-4 space-y-1" x-cloak>
                        @foreach ($productCategories as $category)
                            <div>
                                <div class="flex items-center justify-between py-1 pr-4">
                                    <a href="/products?category={{ $category->id }}"
                                        class="text-sm font-medium text-gray-700">{{ $category->name }}</a>
                                    @if ($category->children->count() > 0)
                                        <button
                                            @click="expandedCategories.includes({{ $category->id }}) ? expandedCategories = expandedCategories.filter(i => i !== {{ $category->id }}) : expandedCategories.push({{ $category->id }})"
                                            class="p-1">
                                            <svg class="w-4 h-4 transition-transform"
                                                :class="expandedCategories.includes({{ $category->id }}) ? 'rotate-180' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @if ($category->children->count() > 0)
                                    <div x-show="expandedCategories.includes({{ $category->id }})"
                                        class="ml-4 border-l border-gray-100 pl-4 space-y-1 pb-2" x-cloak>
                                        @foreach ($category->children as $child)
                                            <a href="/products?subcategory={{ $child->id }}"
                                                class="block py-1 text-xs text-gray-500">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
            <li><a href="/" class="block py-2 px-4 rounded hover:bg-gray-100 text-[#007580]">Home</a></li>
            <li><a href="/products" class="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]">Products</a></li>
            <li class="relative">
                <button class="flex items-center w-full py-2 px-4 rounded hover:bg-gray-100 text-[#636270] transition"
                    @click="showPagesMobile = !showPagesMobile">
                    Pages
                    <svg class="ml-1 w-4 h-4 transition-transform" :class="showPagesMobile ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="showPagesMobile"
                    class="mt-1 ml-3 bg-white border border-gray-200 shadow-lg z-50 rounded w-40" x-cloak>
                    @foreach ($pages as $page)
                        <a href="{{ $page['href'] }}"
                            class="block px-4 py-2 text-[#636270] hover:bg-gray-100">{{ $page['label'] }}</a>
                    @endforeach
                </div>
            </li>
            <li><a href="/about-us" class="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]">About</a></li>
            @if ($logged)
                <li><a href="/quote-builder" class="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]">Quote
                        Builder</a></li>
            @endif
            <li class="relative">
                <button class="flex items-center w-full py-2 px-4 rounded hover:bg-gray-100 text-[#636270] transition"
                    @click="showBecomeCustomerMobile = !showBecomeCustomerMobile">
                    Become Customer
                    <svg class="ml-1 w-4 h-4 transition-transform"
                        :class="showBecomeCustomerMobile ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="showBecomeCustomerMobile"
                    class="mt-1 ml-3 bg-white border border-gray-200 shadow-lg z-50 rounded w-48" x-cloak>
                    @foreach ($becomeCustomerMenu as $link)
                        <a href="{{ $link['href'] }}"
                            class="block px-4 py-2 text-[#636270] hover:bg-gray-100">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </li>
        </ul>
        <div class="flex flex-col px-4 space-y-4 mt-6">
            @if (!$isLoggedIn || $isGuest)
                <a href="/login"
                    class="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition w-full text-center block">Login</a>
            @endif
            <div class="flex items-center space-x-5 px-2">
                <a href="/cart" class="flex items-center bg-white rounded-xl p-2 relative border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-2 font-semibold">Cart</span>
                    <div
                        class="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ml-2">
                        {{ $cartCount }}</div>
                </a>
                @if ($logged)
                    <a href="/liked-products" class="bg-white rounded-xl p-2 border border-gray-100"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5"
                                d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229">
                            </path>
                        </svg></a>
                @endif
                @if ($isLoggedIn)
                    <a class="bg-white rounded-xl p-2 border border-gray-100" href="/dashboard"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-miterlimit="10" stroke-width="1.5">
                                <path
                                    d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669"
                                    clip-rule="evenodd"></path>
                                <path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path>
                            </g>
                        </svg></a>
                @endif
            </div>
        </div>
        <div class="px-4 pb-6 mt-6 text-sm">
            <div class="flex items-center space-x-4">
                <a href="/contact-us" class="hover:underline text-[#232323]">Need help?</a>
            </div>
        </div>
    </nav>

    <!-- Bottom Nav Desktop -->
    <div
        class="container mx-auto justify-between items-center font-inter py-5 px-4 md:px-20 text-base font-semibold hidden md:flex">
        <div class="flex items-center space-x-4">
            <div class="relative" @click.away="showCategories = false">
                <button
                    class="flex items-center border px-3 py-3 rounded-xl text-gray-800 hover:bg-gray-100 transition"
                    @click="showCategories = !showCategories">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    All Categories
                    <svg class="ml-1 w-4 h-4 transition-transform" :class="showCategories ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="showCategories" x-transition x-cloak
                    class="absolute left-0 mt-2 w-64 bg-white border border-gray-200 shadow-xl z-50 rounded-xl py-2">
                    @foreach ($productCategories as $category)
                        <div class="group relative px-2">
                            <div
                                class="flex items-center justify-between w-full rounded-lg hover:bg-gray-100 transition-colors">
                                <a href="/products?category={{ $category->id }}"
                                    class="flex-1 px-3 py-2.5 text-left text-sm font-medium text-gray-700">
                                    {{ $category->name }}
                                </a>
                                @if ($category->children->count() > 0)
                                    <div class="pr-3 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            @if ($category->children->count() > 0)
                                <div
                                    class="invisible group-hover:visible opacity-0 group-hover:opacity-100 absolute left-full top-0 w-64 bg-white border border-gray-200 shadow-xl rounded-xl py-2 transition-all duration-200 z-[60]">
                                    @foreach ($category->children as $child)
                                        <div class="px-2">
                                            <a href="/products?subcategory={{ $child->id }}"
                                                class="block w-full px-3 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 hover:text-[#0079C2] rounded-lg transition-colors">
                                                {{ $child->name }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="/" class="text-[#007580]">Home</a>
            <a href="/products" class="text-[#636270]">Products</a>

            <div class="relative" @click.away="showPages = false">
                <button class="flex items-center text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition"
                    @click="showPages = !showPages">
                    Pages
                    <svg class="ml-1 w-4 h-4 transition-transform" :class="showPages ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="showPages" x-transition x-cloak
                    class="absolute left-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg z-50 rounded">
                    @foreach ($pages as $page)
                        <a href="{{ $page['href'] }}"
                            class="block px-4 py-2 hover:bg-gray-100">{{ $page['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <a href="/about-us" class="text-[#636270]">About</a>
            @if ($logged)
                <a href="/quote-builder"
                    class="text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition">Quote Builder</a>
            @endif

            <div class="relative" @click.away="showBecomeCustomer = false">
                <button class="flex items-center text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition"
                    @click="showBecomeCustomer = !showBecomeCustomer">
                    Become Customer
                    <svg class="ml-1 w-4 h-4 transition-transform" :class="showBecomeCustomer ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="showBecomeCustomer" x-transition x-cloak
                    class="absolute left-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg z-50 rounded">
                    @foreach ($becomeCustomerMenu as $link)
                        <a href="{{ $link['href'] }}"
                            class="block px-4 py-2 hover:bg-gray-100">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-[#636270]">Contact:</span>
            <span class="text-black font-semibold">(808) 555-0111</span>
        </div>
    </div>
</header>
