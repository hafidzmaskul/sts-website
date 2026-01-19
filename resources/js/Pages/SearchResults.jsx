import React, { useMemo } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import ProductListingCard from '../components/ProductListingCard';

const formatPrice = (amount) =>
    new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP',
        maximumFractionDigits: 0,
    }).format(amount);

const transformProduct = (product) => {
    const imagePath = product.images?.[0]?.image_path;
    const image = imagePath
        ? (imagePath.startsWith('/') ? imagePath : `/storage/${imagePath}`)
        : '/assets/logo.png';

    let basePrice = 0;
    if (product.base_price) {
        const cleanedPrice = String(product.base_price).replace(/[^\d.-]/g, '');
        const parsedPrice = parseFloat(cleanedPrice);
        if (!Number.isNaN(parsedPrice) && isFinite(parsedPrice)) {
            basePrice = parsedPrice > 10000 ? parsedPrice : parsedPrice * 1000;
        }
    }

    return {
        id: product.id,
        title: product.title || '',
        slug: product.slug || '',
        price: basePrice,
        image,
        brand_name: product.brand?.name || product.brand_name || 'STS',
        series: product.series || `Model ${product.slug || product.id}`,
        is_sign_up_for_pricing: product.is_sign_up_for_pricing,
    };
};

export default function SearchResults({ products = [], query = '', logged = false }) {
    const transformedProducts = useMemo(() => {
        if (!Array.isArray(products)) return [];
        return products.map(transformProduct);
    }, [products]);

    return (
        <div className="min-h-screen flex flex-col">
            <Head title={`Search Results for "${query}"`} />
            <Header />
            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-6 text-[#232323]">
                    Search Results for "{query}"
                </h1>

                {transformedProducts.length === 0 ? (
                    <div className="text-center py-20">
                        <h2 className="text-xl md:text-2xl text-gray-500 font-semibold">
                            Product not found
                        </h2>
                        <p className="text-gray-400 mt-2">
                            We couldn't find any products matching your search criteria.
                        </p>
                        <div className="mt-6">
                            <a href="/products" className="inline-block px-6 py-3 bg-[#0079C2] text-white rounded-lg font-semibold hover:bg-[#00609C] transition">
                                Browse All Products
                            </a>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {transformedProducts.map((product) => (
                            <ProductListingCard
                                key={product.id}
                                image={product.image}
                                name={product.title}
                                brand={product.brand_name}
                                series={product.series}
                                priceLabel={formatPrice(product.price)}
                                slug={product.slug}
                                showPricing={product.is_sign_up_for_pricing && !logged}
                            />
                        ))}
                    </div>
                )}
            </main>
            <Footer />
        </div>
    );
}
