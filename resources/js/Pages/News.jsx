import React, { useEffect, useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import NewsCard from '../components/NewsCard';

export default function News() {
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);

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
    const articles = Array.from({ length: 18 }, (_item, index) => index + 1);

    const totalPages = Math.ceil(articles.length / itemsPerPage);
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
    <div className="">
            {isLoading && (
                <div className="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
                    <div className="h-10 w-10 rounded-full border-4 border-white/40 border-t-white animate-spin" />
                </div>
            )}
            <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'News & Updates'} color='black' />
                    <p className='text-roboto text-xs md:text-base font-normal mb-10 md:mb-20 mt-10 max-w-md'>
                        Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu
                        aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl  malesuada lacinia integer nunc posuere.
                    </p>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-6 py-12 text-white">
                <div className="flex items-center justify-between">
                    <button
                        type="button"
                        onClick={handlePrevious}
                        disabled={currentPage === 1}
                        className="px-2 py-2 rounded-full border border-black/40 disabled:opacity-40 disabled:cursor-not-allowed text-black"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                    </button>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 flex-1 mx-4">
                        {currentArticles.map((article) => (
                            <NewsCard key={article} index={article} isLoading={isLoading} />
                        ))}
                    </div>

                    <button
                        type="button"
                        onClick={handleNext}
                        disabled={currentPage === totalPages}
                        className="px-2 py-2 rounded-full border border-black/40 disabled:opacity-40 disabled:cursor-not-allowed text-black"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>

                <div className="flex items-center justify-center mt-10 text-black">
                    <span className="text-sm">
                        Page {currentPage} of {totalPages}
                    </span>
                </div>
            </main>
            <Footer />
        </div>
    );
}
