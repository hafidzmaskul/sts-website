import React, { useState, useRef, useEffect } from 'react';

export default function Header() {
    const [showLang, setShowLang] = useState(false);
    const [showCategories, setShowCategories] = useState(false);
    const [showPages, setShowPages] = useState(false); // for desktop nav
    const [showPagesMobile, setShowPagesMobile] = useState(false); // for sidenav/mobile
    const [cartCount] = useState(3); // static count, replace with real logic if needed
    const [sideNavOpen, setSideNavOpen] = useState(false);

    // Simulasi status login (set ke false untuk mengikuti instruksi)
    const isLoggedIn = false;

    // List languages for dropdown (expand if needed)
    const languages = [
        { code: 'en', label: 'English' },
        { code: 'id', label: 'Bahasa' }
    ];

    // List categories for dropdown (expand as needed)
    const categories = [
        'Electronics',
        'Fashion',
        'Food',
        'Books'
    ];

    // List pages for the dropdown
    const pages = [
        { href: '/contact-us', label: 'Contact Us' },
        { href: '/training', label: 'Training' },
        { href: '/commisioning', label: 'Commisioning' },
        { href: '/system-design', label: 'System Design' }
    ];

    // Ref for closing dropdown if click outside
    const categoriesRef = useRef(null);
    const pagesRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (categoriesRef.current && !categoriesRef.current.contains(event.target)) {
                setShowCategories(false);
            }
            if (pagesRef.current && !pagesRef.current.contains(event.target)) {
                setShowPages(false);
            }
        };

        if (showCategories || showPages) {
            document.addEventListener("mousedown", handleClickOutside);
        } else {
            document.removeEventListener("mousedown", handleClickOutside);
        }

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [showCategories, showPages]);

    // All nav items (desktop)
    const navLinks = (
        <>
            <div className="relative" ref={categoriesRef}>
                <button
                    className="flex items-center border px-3 py-3 rounded-xl text-gray-800 hover:bg-gray-100 transition"
                    onClick={() => setShowCategories(s => !s)}
                >
                    <svg
                        className="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    All Categories
                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                {showCategories && (
                    <div className="absolute left-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg z-50 rounded">
                        {categories.map(cat => (
                            <a
                                key={cat}
                                href={`/category/${cat.toLowerCase()}`}
                                className="block px-4 py-2 hover:bg-gray-100"
                                onClick={() => setShowCategories(false)}
                            >
                                {cat}
                            </a>
                        ))}
                    </div>
                )}
            </div>
            <a href="/" className="text-[#007580]">Home</a>
            <a href="/shop" className="text-[#636270]">Shop</a>
            <a href="/products" className="text-[#636270]">Product</a>
            {/* --- Pages Dropdown Desktop --- */}
            <div className="relative" ref={pagesRef}>
                <button
                    className="flex items-center text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition"
                    onClick={() => setShowPages(s => !s)}
                    type="button"
                >
                    Pages
                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                {showPages && (
                    <div className="absolute left-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg z-50 rounded">
                        {pages.map(page => (
                            <a
                                key={page.href}
                                href={page.href}
                                className="block px-4 py-2 hover:bg-gray-100"
                                onClick={() => setShowPages(false)}
                            >
                                {page.label}
                            </a>
                        ))}
                    </div>
                )}
            </div>
            {/* --- End Pages Dropdown Desktop --- */}
            <a href="/about-us" className="text-[#636270]">About</a>
        </>
    );

    // Baris atas dan bawah (informasi, search, cart, language)
    return (
        <header className="w-full bg-white border-gray-100 text-[#232323] font-sans z-50 relative">
            {/* Top Bar */}
            <div className="bg-[#0079C2] font-inter font-light text-[#fff]">
                <div className="container mx-auto flex justify-between items-center py-4 px-4 md:px-20 text-sm">
                    <div className="flex">
                        <span>Free shipping on all orders over $50</span>
                    </div>
                    <div className="flex space-x-4 items-center">
                        {/* Language Dropdown */}
                        <div className="relative">
                            <button
                                className="flex items-center hover:text-black transition"
                                onClick={() => setShowLang(s => !s)}
                            >
                                Eng
                                <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {showLang && (
                                <div className="absolute right-0 mt-2 w-28 bg-white border border-gray-200 shadow-lg z-50 rounded">
                                    {languages.map(lang => (
                                        <button
                                            key={lang.code}
                                            className="block w-full px-3 py-2 text-left hover:bg-gray-100"
                                            onClick={() => setShowLang(false)}
                                        >
                                            {lang.label}
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>
                        <a href="/faq" className="hover:underline">FAQ</a>
                        <a href="/help" className="hover:underline">Need help?</a>
                    </div>
                </div>
            </div>

            {/* Responsive Navbar - Sidenav toggle (only on md and below) */}
            <div className="bg-[#F0F2F3]">
                <div className="container mx-auto flex items-center justify-between px-4 md:px-20 relative">
                    {/* Logo */}
                    <a href="/" className="flex-shrink-0">
                        <img src="/assets/logo.png" alt="Logo" className="h-16 md:h-20 w-auto" />
                    </a>

                    {/* Hamburger menu toggle only on <= md */}
                    <button
                        className="flex md:hidden items-center px-3 py-2 rounded text-[#007580] focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onClick={() => setSideNavOpen(true)}
                        aria-label="Open menu"
                    >
                        <svg className="fill-current h-6 w-6" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4 5h16M4 12h16M4 19h16" stroke="currentColor" strokeWidth={2} strokeLinecap="round" />
                        </svg>
                    </button>

                    {/* Search */}
                    <div className="flex-1 flex justify-center mx-2 md:mx-6">
                        <div className="w-full max-w-lg relative hidden md:block">
                            <input
                                type="text"
                                placeholder="Search products..."
                                className="w-full border rounded-lg pl-4 pr-10 py-2 focus:outline-none border-gray-300 focus:border-yellow-400 transition"
                            />
                            <button className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    className="w-5 h-5"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1012 19.5a7.5 7.5 0 004.65-2.85z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {/* Cart & User */}
                    <div className="flex items-center space-x-2 md:space-x-5">
                        {!isLoggedIn ? (
                            <>
                                <a
                                    href="/login"
                                    className="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition"
                                >
                                    Login
                                </a>
                                <a
                                    href="/sign-up"
                                    className="bg-white border border-[#0079C2] text-[#0079C2] hover:bg-[#0079C2] hover:text-white px-4 py-2 rounded-xl font-semibold transition"
                                >
                                    Sign Up
                                </a>
                            </>
                        ) : (
                            <>
                                <a href="/cart" className="flex items-center bg-white rounded-xl p-2 md:p-3 relative">
                                    {/* Cart Icon */}
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clipRule="evenodd"></path></svg>
                                    <span className="font-semibold mr-2 hidden md:inline">Cart</span>
                                    <div className="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ">
                                        {cartCount}
                                    </div>
                                </a>
                                <a className="bg-white rounded-xl p-2 md:p-3 hidden md:flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path></svg>
                                </a>
                                <a className="bg-white rounded-xl p-2 md:p-3 hidden md:flex">
                                    {/* User Icon */}
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeMiterlimit={10} strokeWidth={1.5}><path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clipRule="evenodd"></path><path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path></g></svg>
                                </a>
                            </>
                        )}
                    </div>
                </div>
            </div>

            {/* Sidenav Overlay */}
            {/* Overlay background */}
            <div
                className={`fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300 ${sideNavOpen ? "block" : "hidden"}`}
                onClick={() => setSideNavOpen(false)}
                aria-hidden="true"
            ></div>

            {/* Sidenav */}
            <nav
                className={`fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white z-50 shadow-lg transform transition-transform duration-300 ease-in-out ${
                    sideNavOpen ? "translate-x-0" : "-translate-x-full"
                } md:hidden`}
            >
                {/* Side nav header */}
                <div className="flex items-center justify-between px-4 py-4 border-b border-gray-200">
                    <a href="/" className="flex-shrink-0">
                        <img src="/assets/logo.png" alt="Logo" className="h-12 w-auto" />
                    </a>
                    <button
                        onClick={() => setSideNavOpen(false)}
                        className="text-gray-700 p-2 focus:outline-none"
                        aria-label="Close menu"
                    >
                        <svg className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                {/* Side nav Search */}
                <div className="px-4 py-3">
                    <div className="relative">
                        <input
                            type="text"
                            placeholder="Search products..."
                            className="w-full border rounded-lg pl-4 pr-10 py-2 focus:outline-none border-gray-300 focus:border-yellow-400 transition"
                        />
                        <button className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24"
                                stroke="currentColor"
                                className="w-5 h-5"
                            >
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1012 19.5a7.5 7.5 0 004.65-2.85z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                {/* Nav links */}
                <ul className="flex flex-col px-4 space-y-2 mt-2 text-[#232323] font-semibold">
                    <li>
                        <div className="relative">
                            <button
                                className="flex items-center w-full border px-3 py-3 rounded-xl text-gray-800 hover:bg-gray-100 transition"
                                onClick={() => setShowCategories(s => !s)}
                            >
                                <svg
                                    className="w-5 h-5 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                All Categories
                                <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {showCategories && (
                                <div className="absolute left-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg z-50 rounded">
                                    {categories.map(cat => (
                                        <a
                                            key={cat}
                                            href={`/category/${cat.toLowerCase()}`}
                                            className="block px-4 py-2 hover:bg-gray-100"
                                            onClick={() => {
                                                setShowCategories(false);
                                                setSideNavOpen(false);
                                            }}
                                        >
                                            {cat}
                                        </a>
                                    ))}
                                </div>
                            )}
                        </div>
                    </li>
                    <li>
                        <a href="/" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#007580]" onClick={() => setSideNavOpen(false)}>Home</a>
                    </li>
                    <li>
                        <a href="/shop" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]" onClick={() => setSideNavOpen(false)}>Shop</a>
                    </li>
                    <li>
                        <a href="/products" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]" onClick={() => setSideNavOpen(false)}>Product</a>
                    </li>
                    {/* --- Pages Dropdown Mobile --- */}
                    <li className="relative">
                        <button
                            className="flex items-center w-full py-2 px-4 rounded hover:bg-gray-100 text-[#636270] transition"
                            onClick={() => setShowPagesMobile(s => !s)}
                        >
                            Pages
                            <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        {showPagesMobile && (
                            <div className="mt-1 ml-3 bg-white border border-gray-200 shadow-lg z-50 rounded w-40 absolute left-0">
                                {pages.map(page => (
                                    <a
                                        key={page.href}
                                        href={page.href}
                                        className="block px-4 py-2 text-[#636270] hover:bg-gray-100"
                                        onClick={() => {
                                            setShowPagesMobile(false);
                                            setSideNavOpen(false);
                                        }}
                                    >
                                        {page.label}
                                    </a>
                                ))}
                            </div>
                        )}
                    </li>
                    {/* --- End Pages Dropdown Mobile --- */}
                    <li>
                        <a href="/about-us" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]" onClick={() => setSideNavOpen(false)}>About</a>
                    </li>
                </ul>
                {/* Cart user icons */}
                <div className="flex items-center px-4 space-x-5 mt-6 mb-2">
                    {!isLoggedIn ? (
                        <>
                            <a
                                href="/login"
                                className="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition w-full text-center"
                                style={{ display: 'block' }}
                            >
                                Login
                            </a>
                            <a
                                href="/sign-up"
                                className="bg-white border border-[#0079C2] text-[#0079C2] hover:bg-[#0079C2] hover:text-white px-4 py-2 rounded-xl font-semibold transition w-full text-center"
                                style={{ display: 'block' }}
                            >
                                Sign Up
                            </a>
                        </>
                    ) : (
                        <>
                            <a href="/cart" className="flex items-center bg-white rounded-xl p-2  relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clipRule="evenodd"></path></svg>
                                <span className="font-semibold mr-2">Cart</span>
                                <div className="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ">
                                    {cartCount}
                                </div>
                            </a>
                            <a className="bg-white rounded-xl p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path></svg>
                            </a>
                            <a className="bg-white rounded-xl p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeMiterlimit={10} strokeWidth={1.5}><path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clipRule="evenodd"></path><path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path></g></svg>
                            </a>
                        </>
                    )}
                </div>
                <div className="px-4 pb-6 text-sm">
                    <div className="flex items-center space-x-4">
                        {/* Language Dropdown on sidenav */}
                        <div className="relative">
                            <button
                                className="flex items-center hover:text-black text-[#232323] transition"
                                onClick={() => setShowLang(s => !s)}
                            >
                                Eng
                                <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {showLang && (
                                <div className="absolute left-0 mt-2 w-28 bg-white border border-gray-200 shadow-lg z-50 rounded">
                                    {languages.map(lang => (
                                        <button
                                            key={lang.code}
                                            className="block w-full px-3 py-2 text-left hover:bg-gray-100"
                                            onClick={() => setShowLang(false)}
                                        >
                                            {lang.label}
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>
                        <a href="/faq" className="hover:underline text-[#232323]">FAQ</a>
                        <a href="/help" className="hover:underline text-[#232323]">Need help?</a>
                    </div>
                </div>
            </nav>

            {/* Bottom nav bar - only show on desktop */}
            <div className="container mx-auto justify-between items-center font-inter py-5 px-4 md:px-20 text-base font-semibold hidden md:flex">
                <div className="flex items-center space-x-4">{navLinks}</div>
                <div className="flex items-center space-x-2">
                    <span className="text-[#636270]">Contact:</span>
                    <span className="text-black">(808) 555-0111</span>
                </div>
            </div>
        </header>
    );
}
