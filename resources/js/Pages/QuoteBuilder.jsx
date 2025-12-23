import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Cart() {
    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Cart" />
            <Header />

            <main className="flex-1 container    mx-auto px-6 md:px-10 lg:px-20 py-10    ">
                <nav className="text-xs md:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='/products' className="hover:text-[#0079C2]">Products</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">title</li>
                    </ol>
                </nav>
                <div className="flex md:flex-row flex-col">
                    {/* Breadcrumb */}


                    {/* Kiri: Gambar */}
                    <div className="md:w-2/3 flex items-center justify-center p-8">
                        <img
                            src="/assets/dummmy/fecd358a1b56bef6f0106d5df4cf057608b437f0.png"
                            alt="Product"
                            className="w-full h-full object-contain"
                        />
                    </div>
                    {/* Kanan: Dropdowns & Summary */}
                    <div className="md:w-1/3 w-full flex flex-col justify-between p-8">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            {[...Array(10)].map((_, idx) => (
                                <div key={idx} className="flex flex-col">
                                    <label className="font-semibold text-gray-700 mb-1" htmlFor={`dropdown${idx}`}>
                                        Pilihan #{idx + 1}
                                    </label>
                                    <select
                                        id={`dropdown${idx}`}
                                        className="border rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    >
                                        <option value="">Pilih opsi...</option>
                                        <option value="opsi1">Opsi 1</option>
                                        <option value="opsi2">Opsi 2</option>
                                    </select>
                                </div>
                            ))}
                        </div>
                        {/* Summary & Button */}
                        <div className="border-t pt-6 mt-auto">
                            <div className="flex justify-between items-center mb-4">
                                <span className="text-lg font-bold text-gray-800">Summary</span>
                                <span className="text-xl font-extrabold text-[#0079C2]">Rp 0</span>
                            </div>
                            <button
                                className="w-full bg-[#0079C2] hover:bg-blue-700 text-white font-bold py-3 rounded-md transition duration-200"
                                type="button"
                            >
                                Add Quote
                            </button>
                        </div>
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}
