import React, { useEffect, useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import ServiceCard from '../components/ServiceCard';


export default function Service() {
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
    const cards = Array.from({ length: 18 }, (_item, index) => index + 1);

    const totalPages = Math.ceil(cards.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentCards = cards.slice(startIndex, startIndex + itemsPerPage);

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
        <div className="bg-[#302F2F]" data-aos="fade-in">
            {isLoading && (
                <div className="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
                    <div className="h-10 w-10 rounded-full border-4 border-white/40 border-t-white animate-spin" />
                </div>
            )}

            <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'Our Services'} color='black'  />
                    <p className='text-roboto text-xs md:text-base font-normal mb-10 md:mb-20 mt-10 max-w-md'>
                        Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu
                        aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl  malesuada lacinia integer nunc posuere.
                    </p>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-6 py-12 relative overflow-hidden" data-aos="fade-up">
                {/* Decorative partial background image */}
                <img
                    src="/assets/gradient-service1.png"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-20 left-30 w-3/5 max-w-xl opacity-60"
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />

                {/* Wrap the grid and the nav buttons in a flex container */}
                <div className="flex items-center justify-center w-full">
                    {/* Previous button, at the left of the grid */}
                    <button
                        type="button"
                        onClick={handlePrevious}
                        disabled={currentPage === 1}
                        className="px-2 py-2 rounded-full border border-white/40 text-white disabled:opacity-40 disabled:cursor-not-allowed mr-2"
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

                    {/* The grid of cards */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 flex-1">
                        {currentCards.map((i) => (
                            <ServiceCard uuid={i} key={i} index={i} isLoading={isLoading} />
                        ))}
                    </div>

                    {/* Next button, at the right of the grid */}
                    <button
                        type="button"
                        onClick={handleNext}
                        disabled={currentPage === totalPages}
                        className="px-2 py-2 rounded-full border border-white/40 text-white disabled:opacity-40 disabled:cursor-not-allowed ml-2"
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

                {/* Page indicator below the grid/buttons */}
                <div className="flex items-center justify-center mt-10">
                    <span className="text-white text-sm">
                        Page {currentPage} of {totalPages}
                    </span>
                </div>
            </main>
            <Footer />
        </div>
    );
}
