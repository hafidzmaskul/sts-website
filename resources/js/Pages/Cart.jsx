import React from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Cart() {
    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Cart" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 flex flex-col items-center justify-center">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-4 text-[#232323] text-center">
                    Under Construction
                </h1>
                <p className="text-gray-700 text-center">
                    We are currently working on this page. Please check back later!
                </p>
            </main>

            <Footer />
        </div>
    );
}
