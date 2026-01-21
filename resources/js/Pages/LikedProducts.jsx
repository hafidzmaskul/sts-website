import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function LikedProducts() {
    const [likedItems, setLikedItems] = useState([]);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        const fetchLiked = async () => {
            setIsLoading(true);
            try {
                const res = await axios.get('/web/liked-products');
                const paginator = res.data?.data;
                const items = Array.isArray(paginator?.data) ? paginator.data : [];
                setLikedItems(items);
            } catch (error) {
                setLikedItems([]);
            } finally {
                setIsLoading(false);
            }
        };

        fetchLiked();
    }, []);

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Liked Products" />
            <Header />
            <main className="flex-1 w-full px-2 xs:px-4 sm:px-6 md:px-10 lg:px-20 py-5 md:py-10">
                <div className="max-w-4xl w-full mx-auto bg-white p-3 xs:p-4 sm:p-6 rounded-lg shadow-sm">
                    <h1 className="text-lg xs:text-xl md:text-2xl font-semibold mb-3 xs:mb-4 text-[#232323]">Liked Products</h1>
                    {isLoading ? (
                        <div className="text-center py-10">Loading...</div>
                    ) : likedItems.length === 0 ? (
                        <div className="text-center py-8 xs:py-10">
                            <p className="mb-4 text-sm md:text-base">You have no liked products.</p>
                            <Link
                                href="/products"
                                className="text-[#0079C2] hover:underline text-sm md:text-base"
                            >
                                Browse products
                            </Link>
                        </div>
                    ) : (
                        <div className="flex flex-col divide-y divide-gray-200">
                            {likedItems.map((product) => (
                                <div
                                    key={product.id}
                                    className="flex flex-col xs:flex-row items-start xs:items-center gap-3 xs:gap-4 py-3 xs:py-4"
                                >
                                    {/* Action pindah ke depan */}
                                    <div className="order-2 xs:order-1 flex flex-col justify-between xs:h-20 xs:min-w-[110px] mr-0 xs:mr-4 mb-2 xs:mb-0">
                                        {product.slug && (
                                            <Link
                                                href={`/products/${product.slug}`}
                                                className="text-xs md:text-sm text-[#0079C2] hover:underline font-medium"
                                            >
                                                View product
                                            </Link>
                                        )}
                                    </div>
                                    <img
                                        src={
                                            product.images?.[0]?.image_path
                                                ? (product.images[0].image_path.startsWith('/')
                                                    ? product.images[0].image_path
                                                    : `/storage/${product.images[0].image_path}`)
                                                : '/assets/notfound.png'
                                        }
                                        alt={product.title}
                                        className="w-16 h-16 xs:w-20 xs:h-20 object-cover rounded flex-shrink-0 border order-1 xs:order-2"
                                    />
                                    <div className="flex-1 order-3">
                                        <div className="font-medium text-sm md:text-base text-[#232323] line-clamp-2 mb-1">
                                            {product.title}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </main>
            <Footer />
        </div>
    );
}
