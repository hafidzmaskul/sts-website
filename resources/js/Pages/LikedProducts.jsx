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

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <div className="max-w-4xl w-full mx-auto bg-white p-6 rounded-lg">
                    <h1 className="text-2xl font-semibold mb-4">Liked Products</h1>

                    {isLoading ? (
                        <div className="text-center py-10">Loading...</div>
                    ) : likedItems.length === 0 ? (
                        <div className="text-center py-10">
                            <p className="mb-4">You have no liked products.</p>
                            <Link href="/products" className="text-[#0079C2] hover:underline">
                                Browse products
                            </Link>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {likedItems.map((product) => (
                                <div key={product.id} className="flex items-center gap-4 py-4 border-b">
                                    <img
                                        src={
                                            product.images?.[0]?.image_path
                                                ? (product.images[0].image_path.startsWith('/')
                                                    ? product.images[0].image_path
                                                    : `/storage/${product.images[0].image_path}`)
                                                : '/assets/notfound.png'
                                        }
                                        alt={product.title}
                                        className="w-20 h-20 object-cover rounded"
                                    />
                                    <div className="flex-1">
                                        <div className="font-medium text-[#232323]">{product.title}</div>
                                        {product.slug && (
                                            <Link
                                                href={`/products/${product.slug}`}
                                                className="text-sm text-[#0079C2] hover:underline"
                                            >
                                                View product
                                            </Link>
                                        )}
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

