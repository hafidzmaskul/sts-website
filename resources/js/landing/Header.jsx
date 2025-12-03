import React, { useState } from 'react';

export default function Header() {
    const [showLang, setShowLang] = useState(false);
    const [showCategories, setShowCategories] = useState(false);
    const [cartCount] = useState(3); // static count, replace with real logic if needed

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

    return (
        <header className="w-full bg-white border-b border-gray-100 text-[#232323] font-sans">
            {/* Baris 1 */}
            <div className="bg-[#0079C2] font-inter font-light text-[#fff]">
            <div className="container mx-auto flex justify-between items-center py-4 px-10 md:px-20 text-sm ">
                <div className="flex">
                    <span className=" ">Free shipping on all orders over $50</span>
                </div>
                <div className="flex space-x-4 items-center">
                    {/* Language Dropdown */}
                    <div className="relative">
                        <button
                            className="flex items-center  hover:text-black transition"
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
                    <a href="/faq" className="hover:underline ">FAQ</a>
                    <a href="/help" className="hover:underline ">Need help?</a>
                </div>
            </div>
            </div>
            <div className="bg-[#F0F2F3]">
            {/* Baris 2 */}
            <div className="container mx-auto flex items-center justify-between px-10 md:px-20 ">
                {/* Logo */}
                <a href="/" className="flex-shrink-0">
                    <img src="/assets/logo.png" alt="Logo" className="h-20 w-auto" />
                </a>

                {/* Search */}
                <div className="flex-1 flex justify-center mx-6">
                    <div className="w-full max-w-lg relative">
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
                <div className="flex items-center space-x-5">
                    <a href="/cart" className="flex items-center bg-white rounded-xl p-3  relative">
                        {/* Cart Icon */}
                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M4 3.75a.75.75 0 0 0 0 1.5h1.374l1.888 10.384A.75.75 0 0 0 8 16.25h10a.75.75 0 0 0 .728-.568l2-8A.75.75 0 0 0 20 6.75H7.171l-.433-2.384A.75.75 0 0 0 6 3.75zm4.626 11l-1.182-6.5H19.04l-1.625 6.5zm2.514-4a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5zm-1.39 6.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m5 1.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" clipRule="evenodd"></path></svg>
                        <span className="font-semibold mr-2">Cart</span>
                        <div className="inline-flex items-center justify-center h-5 w-5 text-xs font-bold rounded-full bg-[#007580] text-white ">
                            {cartCount}
                        </div>
                    </a>
                    <a className="bg-white rounded-xl p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 7.23c-1.733-3.924-5.764-4.273-7.641-2.562c-1.529 1.373-2.263 4.665-.867 7.695C5.9 17.573 12 20.309 12 20.309s6.101-2.736 8.508-7.946c1.396-3.03.662-6.322-.867-7.695C17.764 2.957 13.733 3.306 12 7.229"></path></svg>
                    </a>
                    <a  className="bg-white rounded-xl p-3">
                        {/* User Icon */}
                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeMiterlimit={10} strokeWidth={1.5}><path d="M5.4 21h13.2c.636 0 1.247-.24 1.697-.67c.45-.428.703-1.01.703-1.616a5.58 5.58 0 0 0-1.757-4.04A6.16 6.16 0 0 0 15 13H9a6.16 6.16 0 0 0-4.243 1.674A5.58 5.58 0 0 0 3 18.714c0 .607.253 1.188.703 1.617c.45.428 1.06.669 1.697.669" clipRule="evenodd"></path><path d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0"></path></g></svg>
                    </a>
                </div>
            </div>
            </div>
            {/* Baris 3 */}
            <div className="container mx-auto flex justify-between items-center font-inter py-5 px-10 md:px-20 border-t border-gray-200 text-base font-semibold">
                <div className="flex items-center space-x-4">
                    {/* Categories Dropdown */}
                    <div className="relative">
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
                                    d="M4 6h16M4 12h16M4 18h16"/>
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
                    <a href="/" className=" text-[#007580]">Home</a>
                    <a href="/shop" className=" text-[#636270]">Shop</a>
                    <a href="/products" className=" text-[#636270]">Product</a>
                    <a href="/pages" className=" text-[#636270]">Pages</a>
                    <a href="/about-us" className=" text-[#636270]">About</a>
                </div>
                <div className="flex items-center space-x-2">
                    <span className="text-[#636270]">Contact:</span>
                    <span className="text-black">(808) 555-0111</span>
                </div>
            </div>
        </header>
    );
}
