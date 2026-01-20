import React, { useState, useRef, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';
import axios from 'axios';

export default function Header() {
    const [showLang, setShowLang] = useState(false);
    const [showCategories, setShowCategories] = useState(false);
    const [showPages, setShowPages] = useState(false);
    const [showPagesMobile, setShowPagesMobile] = useState(false);
    const [showBecomeCustomer, setShowBecomeCustomer] = useState(false); // <-- new for desktop
    const [showBecomeCustomerMobile, setShowBecomeCustomerMobile] = useState(false); // <-- new for mobile
    const [cartCount, setCartCount] = useState(0);
    const [sideNavOpen, setSideNavOpen] = useState(false);
    const [cartProducts, setCartProducts] = useState([]);
    const [showCartDropdown, setShowCartDropdown] = useState(false);
    const [likedProducts, setLikedProducts] = useState([]);
    const [showLikedDropdown, setShowLikedDropdown] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');

    const handleSearch = () => {
        if (!searchQuery.trim()) return;
        router.get('/search', { q: searchQuery });
        setSideNavOpen(false); // Close sidebar if open
    };



    // fetch cart count and list, and listen for cart changes
    useEffect(() => {
        const fetchCart = async () => {
            try {
                const res = await axios.get('/web/cart');
                let items = [];
                // The API returns { data: [ ... ] }
                if (Array.isArray(res.data.data)) {
                    items = res.data.data.map((item) => {
                        const product = item.product || {};
                        // Try to get product image from images array
                        let imageUrl = '';
                        if (Array.isArray(product.images) && product.images.length > 0) {
                            imageUrl = product.images[0]?.image_url || '';
                        }
                        return {
                            id: item.id,
                            quantity: item.quantity,
                            // Use product title, fall back to null if not present
                            title: product.title || 'Product',
                            image: imageUrl,
                        };
                    });
                } else {
                    items = [];
                }
                setCartProducts(items);

                const count = items.reduce((s, i) => s + (i.quantity || 0), 0);
                setCartCount(count);
            } catch (err) {
                setCartProducts([]);
                setCartCount(0);
            }
        };

        fetchCart();

        const handler = (e) => {
            if (e?.detail?.count !== undefined) {
                setCartCount(e.detail.count);
            }
            fetchCart();
        };

        window.addEventListener('cart:changed', handler);
        return () => window.removeEventListener('cart:changed', handler);
    }, []);

    // Get logged status and categories from shared Inertia props
    const {
        logged: isLoggedIn = false,
        is_guest: isGuest = false,
        productCategories = []
    } = usePage().props;

    // List categories for dropdown (removed hardcoded list)

    // List pages for the dropdown
    const pages = [
        { href: '/contact-us', label: 'Contact Us' },
        { href: '/training', label: 'Training' },
        { href: '/commisioning', label: 'Commisioning' },
        { href: '/system-design', label: 'System Design' },
        { href: '/news', label: 'News' }
    ];

    // Become Customer dropdown menu
    const becomeCustomerMenu = [
        { href: '/sign-up-customer', label: 'Sign up as Trade' },
        { href: '/sign-up-credit-facility', label: 'Sign up as Credit facilitator' }
    ];

    const categoriesRef = useRef(null);
    const pagesRef = useRef(null);
    const cartRef = useRef(null);
    const likedRef = useRef(null);
    const becomeCustomerRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (categoriesRef.current && !categoriesRef.current.contains(event.target)) {
                setShowCategories(false);
            }
            if (pagesRef.current && !pagesRef.current.contains(event.target)) {
                setShowPages(false);
            }
            if (cartRef.current && !cartRef.current.contains(event.target)) {
                setShowCartDropdown(false);
            }
            if (likedRef.current && !likedRef.current.contains(event.target)) {
                setShowLikedDropdown(false);
            }
            if (becomeCustomerRef.current && !becomeCustomerRef.current.contains(event.target)) {
                setShowBecomeCustomer(false);
            }
        };

        if (showCategories || showPages || showCartDropdown || showLikedDropdown || showBecomeCustomer) {
            document.addEventListener("mousedown", handleClickOutside);
        } else {
            document.removeEventListener("mousedown", handleClickOutside);
        }

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [showCategories, showPages, showCartDropdown, showLikedDropdown, showBecomeCustomer]);

    useEffect(() => {
        const fetchLiked = async () => {
            try {
                const res = await axios.get('/web/liked-products');
                const paginator = res.data?.data;
                const items = Array.isArray(paginator?.data) ? paginator.data : [];

                const mapped = items.map((product) => {
                    let imageUrl = '';
                    if (Array.isArray(product.images) && product.images.length > 0) {
                        const raw = product.images[0]?.image_path || product.images[0]?.image_url || '';
                        imageUrl = raw
                            ? (raw.startsWith('/') ? raw : `/storage/${raw}`)
                            : '';
                    }

                    return {
                        id: product.id,
                        title: product.title || 'Product',
                        image: imageUrl,
                        slug: product.slug,
                    };
                });

                setLikedProducts(mapped);
            } catch (error) {
                setLikedProducts([]);
            }
        };

        fetchLiked();

        const handleLikedChanged = () => {
            fetchLiked();
        };

        window.addEventListener('liked:changed', handleLikedChanged);

        return () => {
            window.removeEventListener('liked:changed', handleLikedChanged);
        };
    }, []);

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
                    <div className="absolute left-0 mt-2 w-64 bg-white border border-gray-200 shadow-xl z-50 rounded-xl overflow-visible py-2">
                        {Array.isArray(productCategories) && productCategories.length > 0 ? (
                            productCategories.map((category) => (
                                <div key={category.id} className="group relative px-2">
                                    <div className="flex items-center justify-between w-full rounded-lg hover:bg-gray-100 transition-colors">
                                        <button
                                            onClick={() => {
                                                router.get('/products', { category: category.id });
                                                setShowCategories(false);
                                            }}
                                            className="flex-1 px-3 py-2.5 text-left text-sm font-medium text-gray-700"
                                        >
                                            {category.name}
                                        </button>
                                        {category.children && category.children.length > 0 && (
                                            <div className="pr-3 text-gray-400">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        )}
                                    </div>

                                    {/* Children (subcategories) on hover */}
                                    {category.children && category.children.length > 0 && (
                                        <div className="invisible group-hover:visible opacity-0 group-hover:opacity-100 absolute left-full top-0 w-64 bg-white border border-gray-200 shadow-xl rounded-xl py-2 ml-0 transition-all duration-200 z-[60]">
                                            {category.children.map((child) => (
                                                <div key={child.id} className="px-2">
                                                    <button
                                                        onClick={() => {
                                                            router.get('/products', { subcategory: child.id });
                                                            setShowCategories(false);
                                                        }}
                                                        className="block w-full px-3 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 hover:text-[#0079C2] rounded-lg transition-colors"
                                                    >
                                                        {child.name}
                                                    </button>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            ))
                        ) : (
                            <div className="px-4 py-3 text-sm text-gray-500 italic">
                                No categories available
                            </div>
                        )}
                    </div>
                )}
            </div>
            <a href="/" className="text-[#007580]">Home</a>
            <a href="/products" className="text-[#636270]">Products</a>
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
            {isLoggedIn && !isGuest && (
                <a
                    href="/quote-builder"
                    className="text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition"
                >
                    Quote Builder
                </a>
            )}
            {/* Become Customer Dropdown Desktop */}
            <div className="relative" ref={becomeCustomerRef}>
                <button
                    className="flex items-center text-[#636270] hover:text-[#007580] px-3 py-2 rounded-xl transition"
                    onClick={() => setShowBecomeCustomer(s => !s)}
                    type="button"
                >
                    Become Customer
                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                {showBecomeCustomer && (
                    <div className="absolute left-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg z-50 rounded">
                        {becomeCustomerMenu.map(link => (
                            <a
                                key={link.href}
                                href={link.href}
                                className="block px-4 py-2 hover:bg-gray-100"
                                onClick={() => setShowBecomeCustomer(false)}
                            >
                                {link.label}
                            </a>
                        ))}
                    </div>
                )}
            </div>
            {/* End Become Customer Dropdown Desktop */}
        </>
    );

    return (
        <header className="w-full bg-white border-gray-100 text-[#232323] font-sans z-50 relative">
            {/* Top Bar */}
            <div className="bg-[#0079C2] font-inter font-light text-[#fff]">
                <div className="container mx-auto flex justify-between items-center py-4 px-4 md:px-20 text-sm">
                    <div className="flex">
                        <span>Free shipping on all orders over $50</span>
                    </div>
                    <div className="flex space-x-4 items-center">
                        <a href="/contact-us" className="hover:underline">Need help?</a>
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
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                            />
                            <button
                                className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400"
                                onClick={handleSearch}
                            >
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
                        {(!isLoggedIn || isGuest) && (
                            <>
                                <a
                                    href="/login"
                                    className="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition"
                                >
                                    Login
                                </a>

                            </>
                        )}
                        {(isLoggedIn || isGuest) && (
                            <>
                                {isLoggedIn && (
                                    <a href="/my-transactions" className="flex items-center bg-white rounded-xl p-2 md:p-3 text-[#636270] hover:text-[#007580]" title="My Transactions">
                                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z" /></svg>
                                        <span className="font-semibold ml-2 hidden md:inline">History</span>
                                    </a>
                                )}
                                <div
                                    className="relative"
                                    ref={cartRef}
                                    onMouseEnter={() => setShowCartDropdown(true)}
                                    onMouseLeave={() => setShowCartDropdown(false)}
                                >
                                    <a href="/cart" className="flex items-center bg-white rounded-xl p-2 md:p-3 relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clipRule="evenodd"></path></svg>
                                        <span className="font-semibold mr-2 hidden md:inline">Cart</span>
                                        <div className="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ">
                                            {cartCount}
                                        </div>
                                    </a>
                                    {showCartDropdown && (
                                        <div className="absolute right-0 mt-2 w-72 bg-white border border-gray-200 shadow-lg z-50 rounded overflow-y-auto max-h-80 min-h-12 min-w-[180px]">
                                            <div className="py-2">
                                                {cartProducts && cartProducts.length > 0 ? (
                                                    cartProducts.map((item, idx) => (
                                                        <div key={item.id || idx} className="flex items-center px-4 py-2 border-b last:border-b-0">
                                                            {item.image ? (
                                                                <img
                                                                    src={item.image}
                                                                    alt={item.title}
                                                                    className="w-10 h-10 object-cover rounded mr-3 flex-shrink-0"
                                                                />
                                                            ) : (
                                                                <div className="w-10 h-10 bg-gray-200 rounded mr-3 flex flex-shrink-0 items-center justify-center text-gray-400">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <rect width="24" height="24" rx="4" fill="#e5e7eb" />
                                                                        <path d="M16 17v-.5a2.5 2.5 0 0 0-2.5-2.5h-3A2.5 2.5 0 0 0 8 16.5V17" stroke="#9ca3af" strokeWidth="1.5" strokeLinecap="round" />
                                                                        <circle cx="12" cy="10" r="2" stroke="#9ca3af" strokeWidth="1.5" />
                                                                    </svg>
                                                                </div>
                                                            )}
                                                            <div className="flex flex-col">
                                                                <span className="font-medium text-sm truncate max-w-[140px]">{item.title}</span>
                                                                <span className="text-xs text-gray-500">Qty: {item.quantity}</span>
                                                            </div>
                                                        </div>
                                                    ))
                                                ) : (
                                                    <div className="text-center text-gray-500 py-8">
                                                        Cart is empty
                                                    </div>
                                                )}
                                            </div>
                                            <div className="border-t px-4 py-2">
                                                <a href="/cart" className="block w-full text-center text-[#007580] hover:underline font-semibold">View Cart</a>
                                            </div>
                                        </div>
                                    )}
                                </div>
                                {isLoggedIn && !isGuest && (
                                    <div
                                        className="relative hidden md:flex"
                                        ref={likedRef}
                                        onMouseEnter={() => setShowLikedDropdown(true)}
                                        onMouseLeave={() => setShowLikedDropdown(false)}
                                    >
                                        <a href="/liked-products" className="bg-white rounded-xl p-2 md:p-3 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path></svg>
                                        </a>
                                        {showLikedDropdown && (
                                            <div className="absolute right-0 mt-2 w-72 bg-white border border-gray-200 shadow-lg z-50 rounded overflow-y-auto max-h-80 min-h-12 min-w-[180px]">
                                                <div className="py-2">
                                                    {likedProducts && likedProducts.length > 0 ? (
                                                        likedProducts.map((item) => (
                                                            <a
                                                                key={item.id}
                                                                href={item.slug ? `/products/${item.slug}` : '#'}
                                                                className="flex items-center px-4 py-2 border-b last:border-b-0 hover:bg-gray-50"
                                                            >
                                                                {item.image ? (
                                                                    <img
                                                                        src={item.image}
                                                                        alt={item.title}
                                                                        className="w-10 h-10 object-cover rounded mr-3 flex-shrink-0"
                                                                    />
                                                                ) : (
                                                                    <div className="w-10 h-10 bg-gray-200 rounded mr-3 flex flex-shrink-0 items-center justify-center text-gray-400">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect width="24" height="24" rx="4" fill="#e5e7eb" />
                                                                            <path d="M16 17v-.5a2.5 2.5 0 0 0-2.5-2.5h-3A2.5 2.5 0 0 0 8 16.5V17" stroke="#9ca3af" strokeWidth="1.5" strokeLinecap="round" />
                                                                            <circle cx="12" cy="10" r="2" stroke="#9ca3af" strokeWidth="1.5" />
                                                                        </svg>
                                                                    </div>
                                                                )}
                                                                <div className="flex-1">
                                                                    <span className="font-medium text-sm truncate max-w-[160px]">{item.title}</span>
                                                                </div>
                                                            </a>
                                                        ))
                                                    ) : (
                                                        <div className="text-center text-gray-500 py-8">
                                                            No liked products
                                                        </div>
                                                    )}
                                                </div>
                                                <div className="border-t px-4 py-2">
                                                    <a href="/liked-products" className="block w-full text-center text-[#007580] hover:underline font-semibold">
                                                        View Liked Products
                                                    </a>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                )} {/* like */}

                                {isLoggedIn && (
                                    <a className="bg-white rounded-xl p-2 md:p-3 hidden md:flex" href='/dashboard'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeMiterlimit={10} strokeWidth={1.5}><path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clipRule="evenodd"></path><path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path></g></svg>
                                    </a>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>

            {/* Sidenav Overlay */}
            <div
                className={`fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300 ${sideNavOpen ? "block" : "hidden"}`}
                onClick={() => setSideNavOpen(false)}
                aria-hidden="true"
            ></div>

            {/* Sidenav */}
            <nav
                className={`fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white z-50 shadow-lg transform transition-transform duration-300 ease-in-out ${sideNavOpen ? "translate-x-0" : "-translate-x-full"
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
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                        />
                        <button
                            className="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-yellow-400"
                            onClick={handleSearch}
                        >
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
                                <div className="mt-2 space-y-1 bg-gray-50 rounded-xl p-2 border border-gray-100">
                                    {Array.isArray(productCategories) && productCategories.length > 0 ? (
                                        productCategories.map((category) => (
                                            <div key={category.id} className="space-y-1">
                                                <div className="flex items-center justify-between">
                                                    <button
                                                        onClick={() => {
                                                            router.get('/products', { category: category.id });
                                                            setShowCategories(false);
                                                            setSideNavOpen(false);
                                                        }}
                                                        className="flex-1 px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-white hover:shadow-sm rounded-lg transition-all"
                                                    >
                                                        {category.name}
                                                    </button>
                                                </div>
                                                {category.children && category.children.length > 0 && (
                                                    <div className="ml-4 pl-2 border-l border-gray-200 space-y-1">
                                                        {category.children.map((child) => (
                                                            <button
                                                                key={child.id}
                                                                onClick={() => {
                                                                    router.get('/products', { subcategory: child.id });
                                                                    setShowCategories(false);
                                                                    setSideNavOpen(false);
                                                                }}
                                                                className="block w-full px-4 py-1.5 text-left text-xs text-gray-500 hover:text-[#0079C2]"
                                                            >
                                                                {child.name}
                                                            </button>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        ))
                                    ) : (
                                        <div className="px-4 py-2 text-xs text-gray-400 italic">No categories</div>
                                    )}
                                </div>
                            )}
                        </div>
                    </li>
                    <li>
                        <a href="/" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#007580]" onClick={() => setSideNavOpen(false)}>Home</a>
                    </li>

                    <li>
                        <a href="/products" className="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]" onClick={() => setSideNavOpen(false)}>Products</a>
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
                    {isLoggedIn && !isGuest && (
                        <li>
                            <a
                                href="/quote-builder"
                                className="block py-2 px-4 rounded hover:bg-gray-100 text-[#636270]"
                                onClick={() => setSideNavOpen(false)}
                            >
                                Quote Builder
                            </a>
                        </li>
                    )}
                    {/* Become Customer Dropdown Mobile */}
                    <li className="relative">
                        <button
                            className="flex items-center w-full py-2 px-4 rounded hover:bg-gray-100 text-[#636270] transition"
                            onClick={() => setShowBecomeCustomerMobile(s => !s)}
                        >
                            Become Customer
                            <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        {showBecomeCustomerMobile && (
                            <div className="mt-1 ml-3 bg-white border border-gray-200 shadow-lg z-50 rounded w-48 absolute left-0">
                                {becomeCustomerMenu.map(link => (
                                    <a
                                        key={link.href}
                                        href={link.href}
                                        className="block px-4 py-2 text-[#636270] hover:bg-gray-100"
                                        onClick={() => {
                                            setShowBecomeCustomerMobile(false);
                                            setSideNavOpen(false);
                                        }}
                                    >
                                        {link.label}
                                    </a>
                                ))}
                            </div>
                        )}
                    </li>
                    {/* End Become Customer Dropdown Mobile */}
                </ul>
                {/* Cart user icons */}
                <div className="flex items-center px-4 space-x-5 mt-6 mb-2">
                    {(!isLoggedIn || isGuest) && (
                        <>
                            <a
                                href="/login"
                                className="bg-[#0079C2] hover:bg-[#00609C] text-white px-4 py-2 rounded-xl font-semibold transition w-full text-center"
                                style={{ display: 'block' }}
                            >
                                Login
                            </a>

                        </>
                    )}
                    {(isLoggedIn || isGuest) && (
                        <>
                            <a href="/cart" className="flex items-center bg-white rounded-xl p-2  relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clipRule="evenodd"></path></svg>
                                <span className="font-semibold mr-2">Cart</span>
                                <div className="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ">
                                    {cartCount}
                                </div>
                            </a>
                            {isLoggedIn && !isGuest && (
                                <a className="bg-white rounded-xl p-2" href="/liked-products">
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path></svg>
                                </a>
                            )}
                            {isLoggedIn && !isGuest && (
                                <a className="bg-white rounded-xl p-2" href='/dashboard'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeMiterlimit={10} strokeWidth={1.5}><path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clipRule="evenodd"></path><path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path></g></svg>
                                </a>
                            )}
                        </>
                    )}
                </div>
                <div className="px-4 pb-6 text-sm">
                    <div className="flex items-center space-x-4">
                        {/* Language Dropdown on sidenav */}
                        {/* <div className="relative">
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
                        </div> */}
                        {/* <a href="/faq" className="hover:underline text-[#232323]">FAQ</a> */}
                        <a href="/contact-us" className="hover:underline text-[#232323]">Need help?</a>
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
