import React, { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Landing({
    banners = [],
    landingPageData = {},
}) {
    const [currentBannerIndex, setCurrentBannerIndex] = useState(0);

    const heroDescription = landingPageData.hero_description ?? 'Discover personalised HR and employment law support crafted for ambitious teams and growing organisations.';

    useEffect(() => {
        if (banners.length <= 1) {
            return;
        }

        const interval = setInterval(() => {
            setCurrentBannerIndex((prev) => (prev + 1) % banners.length);
        }, 6000);

        return () => clearInterval(interval);
    }, [banners.length]);

    useEffect(() => {
        if (currentBannerIndex > banners.length - 1) {
            setCurrentBannerIndex(0);
        }
    }, [banners.length, currentBannerIndex]);

    const activeBanner = banners[currentBannerIndex] ?? null;
    const heroImage = activeBanner?.file_path
        ? `/storage/${activeBanner.file_path}`
        : 'https://placehold.co/900x700/302F2F/FFFFFF?text=No+Banner';
    const ctaHref = activeBanner?.cta_url ?? '/products';

    const goToSlide = (index) => {
        if (banners.length === 0) {
            return;
        }
        const safeIndex = (index + banners.length) % banners.length;
        setCurrentBannerIndex(safeIndex);
    };

    const goNext = () => goToSlide(currentBannerIndex + 1);
    const goPrevious = () => goToSlide(currentBannerIndex - 1);

    return (
        <div className="min-h-screen  ">
            <Head title="Home - Absolutely Human Resources" />
            <Header />

            <section
                className="relative"
                style={{
                    backgroundImage: "url('/assets/bg-carousel.jpg')",
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                }}
            >
                <div className="container mx-auto px-6 md:px-10 lg:px-20 relative z-10">
                    <div className=" flex flex-col md:flex-row items-center gap-10">
                        <div className="w-full md:w-1/2 space-y-6">
                        <p className='text-white text-2xl font-light'>Welcome to STS</p>
                            <h1 className="font-bebas-neue text-white text-5xl md:text-6xl leading-[1.1]">
                                {activeBanner?.name ?? 'Banner Coming Soon'}
                            </h1>
                            <div className="pt-4 mt-50">
                                <a
                                    href={ctaHref}
                                    className="inline-flex items-center gap-3 rounded-xl bg-[#0079C2] px-8 py-3 font-semibold text-[#fff] transition hover:-translate-y-0.5 "
                                >
                                    Shop Now
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                                </a>
                            </div>
                        </div>

                        <div className="w-full md:w-1/2 flex justify-end">
                            <img
                                src={heroImage}
                                alt={activeBanner?.name ?? 'Banner image'}
                                className="h-full w-full z-10 object-cover"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </div>
                {banners.length > 1 && (
                    <div className="absolute z-20 bottom-10 flex w-full pointer-events-none">
                        <div className="flex w-full justify-between">
                            <button
                                type="button"
                                onClick={goPrevious}
                                className="pointer-events-auto inline-flex items-end gap-2  border-none  bg-white px-10 py-2 font-semibold text-[#0079C2] transition hover:bg-[#0079C2] hover:text-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 8l-4 4l4 4m-4-4h20"/></svg>
                            </button>
                            <button
                                type="button"
                                onClick={goNext}
                                className="pointer-events-auto inline-flex items-start gap-2  border-none  bg-white px-10 py-2 font-semibold text-[#0079C2] transition hover:bg-[#0079C2] hover:text-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                            </button>
                        </div>
                    </div>
                )}
                {/* Carousel Indicators pinned at absolute bottom, below text and image */}
                {banners.length > 1 && (
                    <div
                        className="absolute -z-10 left-0 right-0 bottom-4 flex justify-center gap-4 z-0"
                    >
                        {banners.map((banner, index) => (
                            <button
                                key={banner.id ?? `banner-${index}`}
                                type="button"
                                onClick={() => goToSlide(index)}
                                className={`h-3 w-3 rounded-full border border-[#0079C2]/70 transition ${currentBannerIndex === index
                                        ? 'bg-black'
                                        : 'bg-transparent hover:bg-[#0079C2]/40'
                                    }`}
                                aria-label={`Go to banner ${index + 1}`}
                            />
                        ))}
                    </div>
                )}

            </section>



            <Footer landingPageData={landingPageData} />
        </div>
    );
}
