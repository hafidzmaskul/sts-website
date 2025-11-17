import React, { useEffect, useState } from 'react';
import ScaffoldBase from './_ScaffoldBase';
import ProductCard from '../components/ProductCard';
import H1 from '../components/H1';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Products() {
    const [visibleCount, setVisibleCount] = useState(0);
    const [isLoading, setIsLoading] = useState(true);

    const itemsPerPage = 6;
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
        const timeoutId = loadMore();

        const handleScroll = () => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.body.offsetHeight - 200;

            if (scrollPosition >= threshold && !isLoading && hasMore) {
                loadMore();
            }
        };

        window.addEventListener('scroll', handleScroll);

        return () => {
            if (timeoutId) {
                clearTimeout(timeoutId);
            }

            window.removeEventListener('scroll', handleScroll);
        };
    }, [hasMore, isLoading]);

    const skeletonCount = hasMore ? itemsPerPage : 0;

    return (
        <div className="bg-[#302F2F]" data-aos="fade-in">
            <Header />
            <div className="w-full flex flex-col items-center text-center mt-20">
                <H1 text={'ALL PRODUCTS'} color='white' />

            </div>
            <main className="flex-1 container mx-auto px-6 py-12 " data-aos="fade-up">
                {isLoading && (
                    <div className="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
                        <div className="h-10 w-10 rounded-full border-4 border-white/40 border-t-white animate-spin" />
                    </div>
                )}

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {visibleProducts.map((product) => (
                        <ProductCard key={product} index={product} isLoading={false} />
                    ))}

                    {isLoading && skeletonCount > 0 && (
                        Array.from({ length: skeletonCount }).map((_item, index) => (
                            <ProductCard key={`skeleton-${index}`} index={index + 1} isLoading />
                        ))
                    )}
                </div>

                {isLoading && (
                    <div className="mt-6 flex justify-center">
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                            <div className="h-4 w-4 rounded-full border-2 border-gray-400 border-t-gray-700 animate-spin" />
                            <span>Loading products...</span>
                        </div>
                    </div>
                )}
            </main>
            <Footer />
        </div>
    );
}
