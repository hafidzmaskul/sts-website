import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function NewsDetail({ news, otherNews }) {
    if (!news) {
        return (
            <div className="min-h-screen flex flex-col bg-white font-inter">
                <Header />
                <div className="flex-1 flex items-center justify-center">
                    <div className="text-center">
                        <h1 className="text-2xl font-bold text-gray-800">News Not Found</h1>
                        <Link href="/news" className="text-[#0079C2] hover:underline mt-4 block">
                            Back to News
                        </Link>
                    </div>
                </div>
                <Footer />
            </div>
        );
    }

    // Helper to format date
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-GB', options);
    };

    // Helper for image path
    const getImageUrl = (path) => {
        if (!path) return 'https://placehold.co/800x600?text=No+Image';
        if (path.startsWith('http')) return path;
        return `/storage/${path}`;
    };

    return (
        <div className="min-h-screen flex flex-col bg-white font-inter">
            <Head title={`${news.title} - STS News`} />
            <Header />

            <main className="flex-grow pt-24 pb-20"> {/* PT to clear fixed header if needed, loosely based on design */}
                <div className="container mx-auto px-4 md:px-10 lg:px-20">

                    {/* Breadcrumb */}
                    <nav className="flex mb-8 text-sm text-gray-500">
                        <Link href="/" className="hover:text-[#0079C2]">Home</Link>
                        <span className="mx-2">/</span>
                        <Link href="/news" className="hover:text-[#0079C2]">News</Link>
                        <span className="mx-2">/</span>
                        <span className="text-gray-900 truncate max-w-[200px]">{news.title}</span>
                    </nav>

                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
                        {/* Main Content Column */}
                        <div className="lg:col-span-8">
                            {/* Article Header */}
                            <div className="mb-8">
                                <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-[#232323] leading-tight mb-4">
                                    {news.title}
                                </h1>
                                <div className="flex items-center text-gray-500 text-sm md:text-base space-x-4">
                                    <span className="flex items-center">
                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {formatDate(news.created_at)}
                                    </span>
                                    {news.categories && news.categories.length > 0 && (
                                        <span className="flex items-center">
                                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {news.categories.map(c => c.name).join(', ')}
                                        </span>
                                    )}
                                </div>
                            </div>

                            {/* Featured Image */}
                            <div className="mb-10 rounded-2xl overflow-hidden shadow-sm">
                                <img
                                    src={getImageUrl(news.image_path || news.image)}
                                    alt={news.title}
                                    className="w-full h-auto object-cover max-h-[500px]"
                                />
                            </div>

                            {/* Article Content */}
                            <div
                                className="prose prose-lg max-w-none text-gray-700 leading-relaxed"
                                dangerouslySetInnerHTML={{ __html: news.content }}
                            />

                            {/* Tags or Additional Footer Info could go here */}
                        </div>

                        {/* Sidebar Column */}
                        <div className="lg:col-span-4 space-y-10">
                            {/* Latest News Widget */}
                            <div className="bg-[#F9FAFB] rounded-xl p-6 border border-gray-100">
                                <h3 className="text-xl font-bold text-[#232323] mb-6 border-b pb-2">Latest News</h3>
                                <div className="space-y-6">
                                    {otherNews && otherNews.length > 0 ? (
                                        otherNews.map((item) => (
                                            <Link href={`/news/${item.slug}`} key={item.id} className="group flex gap-4 items-start">
                                                <div className="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200">
                                                    <img
                                                        src={getImageUrl(item.image_path || item.thumbnail)}
                                                        alt={item.title}
                                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    />
                                                </div>
                                                <div>
                                                    <h4 className="font-semibold text-gray-900 group-hover:text-[#0079C2] transition-colors line-clamp-2 text-sm leading-snug mb-1">
                                                        {item.title}
                                                    </h4>
                                                    <span className="text-xs text-gray-500">{formatDate(item.created_at)}</span>
                                                </div>
                                            </Link>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 text-sm">No other news available.</p>
                                    )}
                                </div>
                            </div>

                            

                        </div>
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}
