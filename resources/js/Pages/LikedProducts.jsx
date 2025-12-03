import React from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function LikedProducts() {
    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Liked Products" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-4 text-[#232323]">
                    Liked Products
                </h1>
                <p className="text-gray-700">
                    Ini adalah tampilan dasar daftar produk favorit. Anda dapat
                    menambahkan daftar produk yang disukai dan aksi untuk mengelola
                    wishlist di sini.
                </p>
            </main>

            <Footer />
        </div>
    );
}

