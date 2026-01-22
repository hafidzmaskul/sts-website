import React, { useState, useMemo } from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

export default function News({ news, categories }) {
    const [selectedCategory, setSelectedCategory] = useState('All');

    // Filter news based on selected category
    const filteredNews = useMemo(() => {
        if (selectedCategory === 'All') {
            return news;
        }
        return news.filter(item =>
            item.categories && item.categories.some(cat => cat.id === selectedCategory)
        );
    }, [news, selectedCategory]);

    const heroNews = news.slice(0, 5);

    // Helper to format date
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-GB', options).toUpperCase();
    };

    // Helper for image path
    const getImageUrl = (path) => {
        if (!path) return 'https://placehold.co/800x600?text=No+Image';
        if (path.startsWith('http')) return path;
        return `/storage/${path}`;
    };

    return (
        <div className="min-h-screen bg-white font-inter">
            <Head title="News - STS" />
            <Header />

            <main className="pb-20">
                {/* Hero Carousel Section */}
                <section className="container mx-auto px-2 sm:px-4 md:px-8 lg:px-20 pt-4 md:pt-10 pb-6 md:pb-8">
                    <div className="rounded-xl md:rounded-3xl overflow-hidden relative h-[220px] sm:h-[270px] md:h-[400px] lg:h-[500px]">
                        {heroNews.length > 0 ? (
                            <Swiper
                                modules={[Autoplay, Pagination]}
                                pagination={{
                                    clickable: true,
                                    el: '.custom-swiper-pagination',
                                    renderBullet: (index, className) => {
                                        return `<span class="${className} w-3 h-2 md:w-5 md:h-3 !bg-white/50 !opacity-100 aria-[current=true]:!bg-white"></span>`;
                                    }
                                }}
                                autoplay={{ delay: 5000, disableOnInteraction: false }}
                                loop={heroNews.length > 1}
                                className="h-full w-full"
                            >
                                {heroNews.map((item) => (
                                    <SwiperSlide key={item.id} className="relative">
                                        <div className="absolute inset-0 bg-black/40 z-10" />
                                        <img
                                            src={getImageUrl(item.image_path)}
                                            alt={item.title}
                                            className="w-full h-full object-cover object-center"
                                        />
                                        <div className="absolute bottom-0 left-0 w-full z-20 p-3 sm:p-5 md:p-12 lg:p-16">
                                            <div className="max-w-2xl md:max-w-4xl">
                                                <div className="custom-swiper-pagination flex gap-2 mb-3 !static !w-auto !transform-none !justify-start"></div>
                                                <div className="text-white/80 text-xs sm:text-sm md:text-base font-medium mb-1 sm:mb-2 uppercase tracking-wide">
                                                    {formatDate(item.created_at)}
                                                </div>
                                                <Link href={`/news/${item.slug}`} className="block group">
                                                    <h2 className="text-white text-lg sm:text-2xl md:text-4xl lg:text-5xl font-bold leading-tight group-hover:text-blue-200 transition line-clamp-2">
                                                        {item.title}
                                                    </h2>
                                                </Link>
                                                <div className="mt-2 md:mt-4 text-white/90 font-medium text-xs sm:text-sm">
                                                    By {item.author || 'STS Team'}
                                                </div>
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        ) : (
                            <>
                                <div className="custom-swiper-pagination flex gap-2 mb-3 !static !w-auto !transform-none !justify-start relative z-20" />
                                <div className="h-40 sm:h-64 flex items-center justify-center bg-gray-100 rounded-xl md:rounded-3xl">
                                    <p className="text-gray-500 text-sm">No news available.</p>
                                </div>
                            </>
                        )}
                    </div>
                </section>

                {/* Filter Section */}
                <section className="container mx-auto px-2 sm:px-4 md:px-8 lg:px-20 mb-6 md:mb-8">
                    <div className="flex overflow-x-auto no-scrollbar flex-nowrap gap-3 sm:gap-4 border-b border-gray-200 pb-2 sm:pb-4">
                        <button
                            onClick={() => setSelectedCategory('All')}
                            className={`pb-2 px-2 sm:px-3 text-xs sm:text-sm md:text-base font-medium transition-colors relative whitespace-nowrap ${selectedCategory === 'All'
                                ? 'text-[#0079C2]'
                                : 'text-gray-500 hover:text-gray-800'
                                }`}
                        >
                            All News
                            {selectedCategory === 'All' && (
                                <span className="absolute bottom-[-10px] sm:bottom-[-17px] left-0 w-full h-[2px] bg-[#0079C2]" />
                            )}
                        </button>
                        {categories.map((cat) => (
                            <button
                                key={cat.id}
                                onClick={() => setSelectedCategory(cat.id)}
                                className={`pb-2 px-2 sm:px-3 text-xs sm:text-sm md:text-base font-medium transition-colors relative whitespace-nowrap ${selectedCategory === cat.id
                                    ? 'text-[#0079C2]'
                                    : 'text-gray-500 hover:text-gray-800'
                                    }`}
                            >
                                {cat.name}
                                {selectedCategory === cat.id && (
                                    <span className="absolute bottom-[-10px] sm:bottom-[-17px] left-0 w-full h-[2px] bg-[#0079C2]" />
                                )}
                            </button>
                        ))}
                    </div>
                </section>

                {/* News Grid */}
                <section className="container mx-auto px-2 sm:px-4 md:px-8 lg:px-20">
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-7 md:gap-8">
                        {filteredNews.length > 0 ? (
                            filteredNews.map((item) => (
                                <Link
                                    href={`/news/${item.slug}`}
                                    key={item.id}
                                    className="group flex flex-col h-full bg-white rounded-lg md:rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-transparent hover:border-gray-100"
                                >
                                    <div className="relative h-40 sm:h-44 md:h-56 lg:h-60 overflow-hidden rounded-lg md:rounded-xl">
                                        <img
                                            src={getImageUrl(item.image_path)}
                                            alt={item.title}
                                            className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        />
                                    </div>
                                    <div className="flex flex-col flex-grow pt-3 sm:pt-4 md:pt-5 px-3 sm:px-4 md:px-0">
                                        <div className="text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 sm:mb-2">
                                            {formatDate(item.created_at)}
                                        </div>
                                        <h3 className="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-2 sm:mb-3 leading-snug group-hover:text-[#0079C2] transition-colors line-clamp-2">
                                            {item.title}
                                        </h3>
                                        <div className="text-[11px] sm:text-sm text-gray-500 font-medium mt-auto">
                                            by {item.author || 'STS Team'}
                                        </div>
                                    </div>
                                </Link>
                            ))
                        ) : (
                            <div className="col-span-full py-20 text-center text-gray-500 text-base sm:text-lg">
                                No news found in this category.
                            </div>
                        )}
                    </div>
                </section>
            </main>

            <Footer />
        </div>
    );
}
