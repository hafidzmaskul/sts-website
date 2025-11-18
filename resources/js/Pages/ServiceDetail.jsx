import React, { useEffect, useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import ServiceCard from '../components/ServiceCard';
import ExploreButton from '../components/ExploreButton';

export default function ServiceDetail() {
    const [currentPage, setCurrentPage] = useState(1);
    const [isLoading, setIsLoading] = useState(true);

    const itemsPerPage = 3;
    const services = Array.from({ length: 9 }, (_item, index) => index + 1);

    const totalPages = Math.ceil(services.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentServices = services.slice(startIndex, startIndex + itemsPerPage);

    useEffect(() => {
        setIsLoading(true);

        const timeoutId = setTimeout(() => {
            setIsLoading(false);
        }, 800);

        return () => {
            clearTimeout(timeoutId);
        };
    }, [currentPage]);

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
            <div
                className="py-10 rounded-b-xl md:rounded-b-[200px] overflow-hidden"
                style={{
                    background: 'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)'

                }}
            >
                <Header />
                <div className="container px-10 md:px-10 mx-auto justify-center mb-20 mt-12">
                    <div className="w-full overflow-hidden mx-auto"
                        style={{
                            maxWidth: '100%',
                            maxHeight: '400px',
                            borderRadius: '2rem', // Tailwind rounded-4xl equivalent

                        }}>
                        <img
                            src="/assets/detail-service.png"
                            alt=""
                            className="w-full h-full object-cover"
                            style={{
                                display: 'block',
                                maxHeight: '400px',
                                height: '100%',
                                width: '100%',
                                objectFit: 'cover',
                                borderRadius: 'inherit'
                            }}
                        />
                    </div>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-10 md:px-10 text-center py-20" data-aos="fade-up">
                <section className=' mb-30'>

                    <h1 className='font-montserrat font-bold text-3xl md:text-5xl text-white mb-2'>We are not a call centre.
                        We are a small business with a devoted team.</h1>
                    <p className='font-roboto font-normal md:text-xs text-base text-white'>We are a HR Support team that is open 24/7. When you call Absolutely Human Resources in Edinburgh, you will always be answered by someone who can help at the other end of a phone, answering in the UK. Our staff will always be able to listen and provide an immediate response, whatever the scale of your Human Resources enquiry. We are, in effect, your company’s outsourced HR supervisors. We know that every business needs good Human Resources and Employment Law advice. As an SME you may not have the time or money to invest in an HR Manager or department. If you find that your looking for HR support in Edinburgh, our HR Consultancy at Absolute HR can help you, avoiding all the overhead costs of an internal manager or department.</p>
                </section>

                <section id="otherService" className="mt-16">
                    <H1 text={'Other Services'} color='white' className='uppercase mb-10' />

                    <div className="flex items-center justify-center w-full">
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
                                className="lucide lucide-chevron-left-icon"
                            >
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                        </button>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 flex-1">
                            {currentServices.map((service) => (
                                <ServiceCard key={service} index={service} isLoading={isLoading} />
                            ))}
                        </div>

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
                                className="lucide lucide-chevron-right-icon"
                            >
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>

                    <div className="flex items-center justify-center mt-8">
                        <span className="text-white text-sm">
                            Page {currentPage} of {totalPages}
                        </span>
                    </div>
                    <div className="inline-block mt-10">
                        <ExploreButton href="/services">
                            EXPLORE ALL SERVICES
                        </ExploreButton>
                    </div>
                </section>

            </main>



            <Footer />
        </div>
    );
}
