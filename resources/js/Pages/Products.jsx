import React, { useEffect, useState } from 'react';
import ScaffoldBase from './_ScaffoldBase';
import ProductCard from '../components/ProductCard';
import H1 from '../components/H1';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Products() {
    const itemsPerPage = 6;

    const [visibleCount, setVisibleCount] = useState(itemsPerPage);
    const [isLoading, setIsLoading] = useState(true);

    const allProducts = Array.from({ length: 30 }, (_item, index) => index + 1);

    const hasMore = visibleCount < allProducts.length;
    const visibleProducts = allProducts.slice(0, visibleCount);

    const loadMore = () => {
        if (!hasMore) {
            return;
        }

        setIsLoading(true);

        const timeoutId = setTimeout(() => {
            setVisibleCount((current) => {
                const next = current + itemsPerPage;

                return next > allProducts.length ? allProducts.length : next;
            });

            setIsLoading(false);
        }, 800);

        return timeoutId;
    };

    useEffect(() => {
        setIsLoading(false);
    }, []);

    return (
        <div className="bg-[#302F2F]" data-aos="fade-in">
            <Header />
            <div className="w-full flex flex-col items-center text-center mt-20">
                <H1 text={'ALL PRODUCTS'} color='white' />

            </div>
            <main className="flex-1 container mx-auto px-6 py-12 " data-aos="fade-up">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-8">
                    {visibleProducts.map((product) => (
                        <ProductCard key={product} index={product} slug={1} isLoading={false} />
                    ))}
                </div>

                {hasMore && (
                    <div className="mt-10 flex justify-center">
                        <button
                            type="button"
                            className="px-6 py-3 rounded-full bg-white text-black text-sm font-semibold hover:bg-gray-200 disabled:opacity-60 disabled:cursor-not-allowed transition"
                            onClick={loadMore}
                            disabled={isLoading}
                        >
                            {isLoading ? 'Loading...' : 'Load more'}
                        </button>
                    </div>
                )}
            </main>
            <Footer />
        </div>
    );
}
