import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import NewsCard from '../components/NewsCard';
import { getMaxCharacters } from '../helpers/text';

export default function News({ news = [] }) {
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    console.log(news)
    useEffect(() => {
        setIsLoading(true);

        const timeoutId = setTimeout(() => {
            setIsLoading(false);
        }, 800);

        return () => {
            clearTimeout(timeoutId);
        };
    }, [currentPage]);

    const itemsPerPage = 6;
    const articles = news;

    const totalPages = Math.max(1, Math.ceil(articles.length / itemsPerPage));
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentArticles = articles.slice(startIndex, startIndex + itemsPerPage);

    const handleNext = () => {
        if (currentPage < totalPages) {
            setCurrentPage((prevPage) => prevPage + 1);
        }
    };

    const handlePrevious = () => {
        if (currentPage > 1) {
            setCurrentPage((prevPage) => prevPage - 1);
        }
    };

  return (
    <>
    <Head title="News - Absolutely Human Resources" />
    <div className="" data-aos="fade-in">
            {isLoading && (
                <div className="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
                    <div className="h-10 w-10 rounded-full border-4 border-white/40 border-t-white animate-spin" />
                </div>
            )}
            <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'News'} color='black' />
                    <p className='text-roboto text-xs md:text-base font-normal mb-10 md:mb-20 mt-10 max-w-md'>
                    Welcome to our news section, where we share helpful HR tips, updates and stories from the world of people management. Check back regularly for insights that support your business and your team.
                    </p>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-6 py-12 text-white" data-aos="fade-up">
                {articles.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-12 text-center">
                        <p className="text-sm md:text-base text-white/80">
                            Belum ada berita yang tersedia saat ini.
                        </p>
                    </div>
                ) : (
                <div className="flex items-center justify-between">
                    <button
                        type="button"
                        onClick={handlePrevious}
                        disabled={currentPage === 1}
                        className="px-2 py-2 rounded-full border border-white/40 disabled:opacity-40 disabled:cursor-not-allowed text-white"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            className="lucide lucide-chevron-left-icon lucide-chevron-left"
                        >
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 flex-1 mx-4">
                        {currentArticles.map((article, index) => (
                            <NewsCard
                                key={article.id ?? index}
                                index={index + 1}
                                title={article.title}
                                shortDescription={getMaxCharacters(article.short_description ?? article.content, 100)}
                                slug={article.slug}
                                image={article.image_url}
                                isLoading={isLoading}
                            />
                        ))}
                    </div>

                    <button
                        type="button"
                        onClick={handleNext}
                        disabled={currentPage === totalPages}
                        className="px-2 py-2 rounded-full border border-white/40 disabled:opacity-40 disabled:cursor-not-allowed text-white"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            className="lucide lucide-chevron-right-icon lucide-chevron-right"
                        >
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
                )}

                {articles.length > 0 && (
                    <div className="flex items-center justify-center mt-10 text-white">
                        <span className="text-sm">
                            Page {currentPage} of {totalPages}
                        </span>
                    </div>
                )}
            </main>


            <img
                    src="/assets/gradient-news.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 bottom-100 left-0 w-1/4 max-w-lg "
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />
            <Footer />
        </div>
        </>
    );
}
