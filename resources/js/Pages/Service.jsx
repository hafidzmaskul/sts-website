import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Service({ services = [] }) {
    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Services" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-6 text-[#232323]">
                    Services
                </h1>

                {services.length === 0 ? (
                    <p className="text-gray-600">
                        Belum ada layanan yang tersedia.
                    </p>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {services.map((service) => (
                            <div
                                key={service.id}
                                className="bg-white rounded-xl shadow-sm p-5 flex flex-col justify-between"
                            >
                                <div>
                                    <h2 className="text-lg font-semibold text-[#232323] mb-1">
                                        {service.name}
                                    </h2>
                                    {service.short_description && (
                                        <p className="text-sm text-gray-600 mb-2">
                                            {service.short_description}
                                        </p>
                                    )}
                                </div>
                                <div className="mt-4 flex items-center justify-between">
                                    <Link
                                        href={route('services.detail', service.slug)}
                                        className="text-sm font-medium text-[#0079C2] hover:underline"
                                    >
                                        Lihat Detail
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </main>

            <Footer />
        </div>
    );
}

