<header class="w-full bg-white border-gray-100 text-[#232323] font-sans z-50 relative">
    <!-- Top Bar -->
    <div class="bg-[#0079C2] font-inter font-light text-[#fff]">
        <div class="container mx-auto flex justify-between items-center py-4 px-4 md:px-20 text-sm">
            <div class="flex">
                <span>Free shipping on all orders over $50</span>
            </div>
            <div class="flex space-x-4 items-center">
                <a href="/faq" class="hover:underline">FAQ</a>
                <a href="/help" class="hover:underline">Need help?</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="bg-[#F0F2F3]">
        <div class="container mx-auto flex items-center justify-between px-4 md:px-20 relative">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/assets/logo.png" alt="Logo" class="h-16 md:h-20 w-auto" />
            </a>

            <!-- Search -->
            <div class="flex-1 flex justify-center mx-2 md:mx-6">
                <div class="w-full max-w-lg relative hidden md:block">
                    <input
                        type="text"
                        placeholder="Search products..."
                        class="w-full border rounded-lg pl-4 pr-10 py-2 focus:outline-none border-gray-300 focus:border-yellow-400 transition"
                    />
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1012 19.5a7.5 7.5 0 004.65-2.85z"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Cart & User (match Header.jsx behavior) -->
            <div class="flex items-center space-x-2 md:space-x-5">
                @guest
                    <a
                        href="/login"
                        class="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition"
                    >
                        Login
                    </a>
                    <a
                        href="/sign-up"
                        class="bg-white border border-[#0079C2] text-[#0079C2] hover:bg-[#0079C2] hover:text-white px-4 py-2 rounded-xl font-semibold transition"
                    >
                        Sign Up
                    </a>
                @else
                    <a href="/cart" class="flex items-center bg-white rounded-xl p-2 md:p-3 relative">
                        <!-- Cart Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold mr-2 hidden md:inline">Cart</span>
                        <div class="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white">
                            3
                        </div>
                    </a>
                    <a class="bg-white rounded-xl p-2 md:p-3 hidden md:flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path>
                        </svg>
                    </a>
                    <a class="bg-white rounded-xl p-2 md:p-3 hidden md:flex" href="/dashboard">
                        <!-- User Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5">
                                <path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clip-rule="evenodd"></path>
                                <path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path>
                            </g>
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Bottom nav bar - only show on desktop -->
    <div class="container mx-auto justify-between items-center font-inter py-5 px-4 md:px-20 text-base font-semibold hidden md:flex">
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button class="flex items-center border px-3 py-3 rounded-xl text-gray-800 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    All Categories
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <a href="/" class="text-[#007580]">Home</a>
            <a href="/shop" class="text-[#636270]">Shop</a>
            <a href="/products" class="text-[#636270]">Product</a>
            <a href="/pages" class="text-[#636270]">Pages</a>
            <a href="/about-us" class="text-[#636270]">About</a>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-[#636270]">Contact:</span>
            <span class="text-black">(808) 555-0111</span>
        </div>
    </div>
</header>
