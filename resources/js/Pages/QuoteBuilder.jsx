import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Cart() {
    return (
        <div className="min-h-screen flex flex-col bg-white">
            <Head title="Cart" />
            <Header />

            <main className="flex-1 container    mx-auto px-6 md:px-10 lg:px-20 py-10    ">
                <nav className="text-xs md:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='/products' className="hover:text-[#0079C2]">Products</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">Quote Builder</li>
                    </ol>
                </nav>

                {/* Page Header */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
                    <h1 className="text-3xl font-bold text-gray-800">Quote Builder</h1>
                    <div className="flex flex-col gap-2 w-full md:w-auto">
                        <button className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition">
                            ADD TO CART
                        </button>
                        <button className="bg-[#FF3333] hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition">
                            DELETE
                        </button>
                    </div>
                </div>

                {/* Quote Card */}
                <div className="bg-[#F5F5F5] rounded-xl p-6 shadow-sm">
                    {/* Card Header */}
                    <div className="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                        <div className="flex items-center gap-3">
                            <input type="checkbox" className="w-5 h-5 text-[#0079C2] rounded focus:ring-[#0079C2]" />
                            <span className="font-semibold text-lg text-gray-700">Quote #1</span>
                        </div>
                        <button className="text-gray-400 hover:text-red-500 transition">
                            {/* Trash Icon */}
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <div className="flex flex-col lg:flex-row gap-6">
                        {/* Scrollable Product List */}
                        <div className="flex-1 overflow-x-auto">
                            <div className="flex gap-4 pb-4 min-w-min">
                                {/* Dummy Products */}
                                {[...Array(5)].map((_, i) => (
                                    <div key={i} className="min-w-[220px] bg-white rounded-lg border border-gray-200 p-4 relative shrink-0">
                                        <div className="absolute top-3 left-3">
                                            <input type="checkbox" className="w-4 h-4 text-[#0079C2] rounded focus:ring-[#0079C2]" />
                                        </div>
                                        <button className="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <div className="h-40 flex items-center justify-center mt-4">
                                            <img
                                                src="/assets/dummmy/fecd358a1b56bef6f0106d5df4cf057608b437f0.png"
                                                alt="Product"
                                                className="max-h-full max-w-full object-contain"
                                            />
                                        </div>
                                        <div className="mt-2 text-center">
                                            <p className="text-sm font-medium text-gray-600">Product Name {i + 1}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="w-full lg:w-64 flex flex-col gap-3 shrink-0 justify-center">
                            <button className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition">
                                PROCEED TO CHECKOUT
                            </button>
                            <button className="bg-[#0079C2] hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition">
                                MODIFY QUOTE
                            </button>
                        </div>
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}
