import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function ServiceDetail({ service, otherServices = [] }) {
    if (!service) {
        return null;
    }

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title={service.name ?? 'Service Detail'} />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 space-y-10">
                <section className="bg-white rounded-xl p-6 shadow-sm">
                    <p className="text-sm text-gray-500 mb-2">
                        Detail Service
                    </p>
                    <h1 className="font-bebas-neue text-4xl md:text-5xl mb-4 text-[#232323]">
                        {service.name}
                    </h1>
                    {service.description && (
                        <p className="text-gray-700">
                            {service.description}
                        </p>
                    )}
                </section>

                {Array.isArray(service.products) && service.products.length > 0 && (
                    <section>
                        <h2 className="text-xl font-semibold text-[#232323] mb-4">
                            Produk Terkait
                        </h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {service.products.map((product) => (
                                <div
                                    key={product.id}
                                    className="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between"
                                >
                                    <div>
                                        <h3 className="text-base font-semibold text-[#232323] mb-1">
                                            {product.name}
                                        </h3>
                                        {product.price && (
                                            <p className="text-sm text-[#0079C2] font-semibold">
                                                {product.formatted_price ?? product.price}
                                            </p>
                                        )}
                                    </div>
                                    <div className="mt-3">
                                        <Link
                                            href={route('products.detail', product.slug)}
                                            className="text-sm font-medium text-[#0079C2] hover:underline"
                                        >
                                            Lihat Produk
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                )}

                {otherServices.length > 0 && (
                    <section>
                        <h2 className="text-xl font-semibold text-[#232323] mb-4">
                            Layanan Lainnya
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {otherServices.map((item) => (
                                <div
                                    key={item.id}
                                    className="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between"
                                >
                                    <div>
                                        <h3 className="text-base font-semibold text-[#232323] mb-1">
                                            {item.name}
                                        </h3>
                                        {item.short_description && (
                                            <p className="text-sm text-gray-600">
                                                {item.short_description}
                                            </p>
                                        )}
                                    </div>
                                    <div className="mt-3">
                                        <Link
                                            href={route('services.detail', item.slug)}
                                            className="text-sm font-medium text-[#0079C2] hover:underline"
                                        >
                                            Lihat Detail
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                )}
            </main>

            <Footer />
        </div>
    );
}

