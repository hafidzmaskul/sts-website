import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function ProductDetail({ product, products = [] }) {
    if (!product) {
        return null;
    }

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title={product.name ?? 'Product Detail'} />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 space-y-10">
                <section className="bg-white rounded-xl p-6 shadow-sm">
                    <p className="text-sm text-gray-500 mb-2">
                        Detail Product
                    </p>
                    <h1 className="font-bebas-neue text-4xl md:text-5xl mb-4 text-[#232323]">
                        {product.name}
                    </h1>
                    {product.description && (
                        <p className="text-gray-700 mb-4">
                            {product.description}
                        </p>
                    )}
                    {product.price && (
                        <p className="text-2xl font-semibold text-[#0079C2] mb-6">
                            {product.formatted_price ?? product.price}
                        </p>
                    )}
                    <div className="flex flex-wrap gap-3">
                        <button
                            type="button"
                            className="bg-[#0079C2] text-white px-6 py-2 rounded-lg hover:bg-[#005C92] transition"
                        >
                            Tambah ke Cart
                        </button>
                        <button
                            type="button"
                            className="border border-[#0079C2] text-[#0079C2] px-6 py-2 rounded-lg hover:bg-[#0079C2] hover:text-white transition"
                        >
                            Tambah ke Favorite
                        </button>
                    </div>
                </section>

                {products.length > 0 && (
                    <section>
                        <h2 className="text-xl font-semibold text-[#232323] mb-4">
                            Produk Lainnya
                        </h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {products.map((item) => (
                                <div
                                    key={item.id}
                                    className="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between"
                                >
                                    <div>
                                        <h3 className="text-base font-semibold text-[#232323] mb-1">
                                            {item.name}
                                        </h3>
                                        <p className="text-xs text-gray-500 mb-2">
                                            {item.slug}
                                        </p>
                                        {item.price && (
                                            <p className="text-sm text-[#0079C2] font-semibold">
                                                {item.formatted_price ?? item.price}
                                            </p>
                                        )}
                                    </div>
                                    <div className="mt-3">
                                        <Link
                                            href={route('products.detail', item.slug)}
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

