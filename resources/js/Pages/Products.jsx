import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import ScaffoldBase from './_ScaffoldBase';
import ProductCard from '../components/ProductCard';
import H1 from '../components/H1';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import { getMaxCharacters } from '../helpers/text';

export default function Products({ products = [] }) {
    const itemsPerPage = 6;

    const [visibleCount, setVisibleCount] = useState(itemsPerPage);
    const [isLoading, setIsLoading] = useState(true);

    const allProducts = products;

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
    console.log(products)

    useEffect(() => {
        setIsLoading(false);
    }, []);

    return (
        <>
            <Head title="Products - AbsolutelyHR" />
            <div className="bg-[#302F2F]" data-aos="fade-in">
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'ALL PRODUCTS'} color='white' />

                </div>

                <img
                    src="/assets/gradient-product.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10  left-0 w-1/4 max-w-l "
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />

                <img
                    src="/assets/gradient-product2.svg"
                    alt=""
                    className="pointer-events-none select-none absolute bottom-200 -z-10 right-0 w-1/4 max-w-l"

                    aria-hidden="true"
                />
                <main className="flex-1 container mx-auto px-10 md:px-20 py-12 " data-aos="fade-up">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-8">
                        {visibleProducts.map((product, index) => (
                            <div
                                key={product.id ?? index}
                                data-aos="fade-up"
                                data-aos-delay={index * 100}
                            >
                                <ProductCard
                                    index={index + 1}
                                    slug={product.slug}
                                    name={product.name}
                                    shortDescription={getMaxCharacters(product.short_description ?? product.content, 100)}
                                    image={product.image_url}
                                    price={product.price}
                                    isLoading={false}
                                />
                            </div>
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
        </>
    );
}
