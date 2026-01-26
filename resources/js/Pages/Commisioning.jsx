import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import BenefitCard from '../components/BenefitCard';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import QuoteForm from '../components/QuoteForm';

const aboutbenefits = [
    {
        image: '/assets/cost.svg',
        text: 'Cost Effective',
    },
    {
        image: '/assets/risk.svg',
        text: 'Reduces Risk',
    },
    {
        image: '/assets/saves.svg',
        text: 'Saves Time',
    },
    {
        image: '/assets/improve.svg',
        text: 'Improved Buying',
    },
    {
        image: '/assets/increase.svg',
        text: 'Increased Efficiency',
    },
    {
        image: '/assets/labor.svg',
        text: 'Reduces Labor',
    },
];

const whyChooseItems = [
    { image: '/assets/dummmy/d8e28c8e8bb0aa44e6cf5c0d1c450b1de67a8b91.png', text: 'Secure' }, // Placeholder images
    { image: '/assets/dummmy/d8e28c8e8bb0aa44e6cf5c0d1c450b1de67a8b91.png', text: 'Reliable' },
    { image: '/assets/dummmy/d8e28c8e8bb0aa44e6cf5c0d1c450b1de67a8b91.png', text: 'Efficient' },
    { image: '/assets/dummmy/d8e28c8e8bb0aa44e6cf5c0d1c450b1de67a8b91.png', text: 'Innovative' },
    { image: '/assets/dummmy/d8e28c8e8bb0aa44e6cf5c0d1c450b1de67a8b91.png', text: 'Trusted' },
];

export default function Commisioning({ services = [] }) {
    return (
        <div className="flex flex-col min-h-screen">
            <Head title="Services" />
            <Header />
            <div className="container px-4 md:px-10">
                <nav className="text-xs md:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='#' className="hover:text-[#0079C2]">Service</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">Commisioning</li>
                    </ol>
                </nav>
            </div>

            {/* Hero Section - Responsive Improvement */}


            <HeroSection
                bgUrl="/assets/bg-commisioning.png"
                title="Commissioning"
                text="Commissioning your security system with us ensures expert setup, optimal performance, and peace of mind. Our seasoned team provides thorough testing, configuration, and personalized training that guarantees maximum reliability from day one."
                textColor="text-white"
                textAlign="end"
                textSize="text-3xl md:text-5xl"
            />

            <main className="flex-1">

                {/* Commissioning service section - improved responsive */}
                <section className="flex flex-col md:flex-row w-full rounded-lg overflow-hidden mb-16 py-10 md:py-20">
                    <div
                        className="w-full md:w-2/5 p-6 md:p-8 flex flex-col justify-center"
                        style={{ backgroundColor: '#0079C2' }}
                    >
                        <h2 className="text-white font-bold text-lg md:text-2xl mb-4">Commissioning services include:</h2>
                        <ul className="list-disc list-inside text-white space-y-2 ml-3 text-sm md:text-base font-medium flex flex-col">
                            <li>Checking the installation prior to commissioning</li>
                            <li>Configuration of IP settings on all network devices</li>
                            <li>Installation and configuration of software on all equipment</li>
                            <li>Carrying out necessary firmware or software upgrades</li>
                            <li>Testing installed equipment</li>
                            <li>Troubleshooting tasks</li>
                            <li>Training end-users on the newly installed system</li>
                        </ul>
                    </div>
                    <div className="w-full md:w-3/5 bg-white flex items-center justify-center">
                        <img
                            src="/assets/commissioning.png"
                            alt="Commissioning"
                            className="object-cover w-full h-56 md:h-full"
                        />
                    </div>
                </section>

                {/* Why choose section - improved responsive */}
                <section className="w-full bg-[#0079C2] py-8 md:py-12 overflow-hidden">
                    <div className="container mx-auto px-4">
                        <div className="flex flex-col md:flex-row justify-between gap-4 md:gap-10 mb-8">
                            {/* Left: Title */}
                            <div className="flex mb-4 md:mb-0">
                                <h2 className="text-white font-bold text-lg md:text-2xl text-left">Why Choose STS</h2>
                            </div>
                            {/* Right: Bulleted List */}
                            <div className="w-full md:flex-1 flex justify-start md:justify-end">
                                <ul className="list-disc list-inside text-white text-sm md:text-base font-medium space-y-1 text-left pl-4 md:pl-0">
                                    <li>
                                        We have the widest range of security products in the UK: 250 brands, 25,000 products.
                                    </li>
                                    <li>
                                        We have an experienced, fully accredited Technical Support team.
                                    </li>
                                    <li>
                                        A dedicated project manager will look after your project from inception to completion.
                                    </li>
                                    <li>
                                        We provide inventory management so stock can be called off, just when required.
                                    </li>
                                    <li>
                                        Operating in 28 locations across the UK, we’re never too far from where you do business.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div className="carousel-why-choose text-white">
                            <style>
                                {`
                                .swiper-pagination-bullet {
                                    background: #FFFFFF;
                                    opacity: 0.5;
                                }
                                .swiper-pagination-bullet-active {
                                    background: #272343;
                                    opacity: 1;
                                }
                                `}
                            </style>
                            <Swiper
                                modules={[Pagination, Autoplay]}
                                spaceBetween={16}
                                slidesPerView={1}
                                centeredSlides={true}
                                loop={true}
                                autoplay={{ delay: 3000, disableOnInteraction: false }}
                                pagination={{ clickable: true }}
                                breakpoints={{
                                    640: {
                                        slidesPerView: 2,
                                    },
                                    900: {
                                        slidesPerView: 3,
                                    }
                                }}
                                className="w-full pb-8 md:pb-12"
                            >
                                {whyChooseItems.map((item, index) => (
                                    <SwiperSlide key={index}>
                                        {({ isActive }) => (
                                            <div className={`relative pb-10 md:pb-20 flex flex-col items-center transition-all duration-300 ${isActive ? 'z-10' : 'opacity-80'}`}>
                                                <div className="w-full aspect-[4/3] relative overflow-hidden bg-gray-200">
                                                    <img
                                                        src={item.image}
                                                        alt={item.text}
                                                        className="w-full h-full object-cover"
                                                    />
                                                    {/* Overlay for non-active slides */}
                                                    {!isActive && (
                                                        <div className="absolute inset-0 bg-[#0079C2E0] transition-colors duration-300"></div>
                                                    )}
                                                </div>
                                                <div className="w-full bg-white py-4 flex items-center justify-center">
                                                    <p className="text-[#0079C2] font-bold text-base md:text-lg tracking-wide">
                                                        {item.text}
                                                    </p>
                                                </div>
                                            </div>
                                        )}
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        </div>
                    </div>
                </section>

                {/* Benefits section - grid responsive */}
                <section className='bg-[#F0F2F3] py-14 md:py-20'>
                    <h1 className='font-inter font-bold text-2xl md:text-3xl text-center mt-4 mb-8 md:mt-10 md:mb-15 text-[#002856]'>Benefits of pre-build and configuration</h1>
                    <div className="container mx-auto px-4">
                        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 justify-center benefit">
                            {aboutbenefits.slice(0, 6).map((benefit, idx) => (
                                <BenefitCard key={idx} image={benefit.image} text={benefit.text} />
                            ))}
                        </div>
                    </div>
                </section>

                {/* Resource Hub section - improved responsive */}
                <section className="container mx-auto px-4 flex flex-col lg:flex-row my-8 md:my-10 gap-6 md:gap-8">
                    {/* Left column */}
                    <div className="w-full lg:w-5/6 flex flex-col justify-center h-auto">
                        <div
                            className="p-5 md:p-8 h-full text-white rounded-t-lg lg:rounded-l-lg lg:rounded-tr-none"
                            style={{
                                background: "linear-gradient(302.66deg, #0079C2 0%, #0069A9 100%)"
                            }}
                        >
                            <h2 className="text-xl md:text-2xl font-bold font-inter mb-2 md:mb-4">Resource Hub</h2>
                            <p className='font-inter font-medium text-sm md:text-base'>Discover STS's latest articles, buying guides and tools tailored to installers and integrators on industry topics</p>
                        </div>
                    </div>
                    {/* Right column */}
                    <div className="w-full lg:w-1/6 flex justify-center items-center">
                        <img src="/assets/resource-cofig.png" className='h-40 md:h-full w-auto' alt="" />
                    </div>
                </section>

                {/* How it works + QuoteForm section - improved responsive */}
                <section
                    id="form-quote"
                    className="container mx-auto flex flex-col lg:flex-row gap-10 md:gap-12 my-8 md:my-12 px-2 md:px-8"
                >
                    {/* How it works */}
                    <div className="w-full lg:w-2/3 flex flex-col justify-center">
                        <div className="flex flex-col items-center text-center px-2 md:px-6">
                            <h2 className="text-2xl md:text-4xl font-bold font-inter mt-10 mb-8 md:mt-16 md:mb-12 text-[#002856]">
                                How it works
                            </h2>
                            <ol className="flex flex-col gap-10 md:gap-12 w-full">
                                {/* Step 1 */}
                                <li className="flex flex-col items-center w-full">
                                    <div className="bg-white p-3 mb-3 md:mb-4">
                                        <img
                                            src="/assets/step1.svg"
                                            alt="Step 1"
                                            className="w-14 h-14 md:w-20 md:h-20"
                                        />
                                    </div>
                                    <h3 className="text-lg md:text-2xl font-semibold mb-1 md:mb-2 text-[#0079C2] uppercase tracking-wide">
                                        Step 1
                                    </h3>
                                    <p className="text-sm md:text-lg max-w-xs md:max-w-md text-[#002856] opacity-90 font-inter">
                                        Before you purchase your cameras, panels or other programmable product, contact us using the form for a free pre-configuration service quote.
                                    </p>
                                </li>
                                {/* Step 2 */}
                                <li className="flex flex-col items-center w-full">
                                    <div className="bg-white p-3 mb-3 md:mb-4">
                                        <img
                                            src="/assets/step2.png"
                                            alt="Step 2"
                                            className="w-14 h-14 md:w-20 md:h-20"
                                        />
                                    </div>
                                    <h3 className="text-lg md:text-2xl font-semibold mb-1 md:mb-2 text-[#0079C2] uppercase tracking-wide">
                                        Step 2
                                    </h3>
                                    <p className="text-sm md:text-lg max-w-xs md:max-w-md text-[#002856] opacity-90 font-inter">
                                        We’ll connect with you to gather more information about your programming needs and project requirements. Our pre-configuration services are flexible to fit your workflow.
                                    </p>
                                </li>
                                {/* Step 3 */}
                                <li className="flex flex-col items-center w-full">
                                    <div className="bg-white p-3 mb-3 md:mb-4">
                                        <img
                                            src="/assets/step3.png"
                                            alt="Step 3"
                                            className="w-14 h-14 md:w-20 md:h-20"
                                        />
                                    </div>
                                    <h3 className="text-lg md:text-2xl font-semibold mb-1 md:mb-2 text-[#0079C2] uppercase tracking-wide">
                                        Step 3
                                    </h3>
                                    <p className="text-sm md:text-lg max-w-xs md:max-w-md text-[#002856] opacity-90 font-inter">
                                        Our technical team reviews your requirements and creates a tailored quote, whether you need a standardized (bronze, silver, gold) or custom solution. Expect fast, expert feedback from your account manager.
                                    </p>
                                </li>
                            </ol>
                        </div>
                    </div>
                    {/* QuoteForm: on mobile and tab it displays below, on desktop as a row */}
                    <div className="w-full lg:w-1/3 flex items-center">
                        <div className="w-full bg-[#F0F2F3] rounded-xl shadow-md p-4 md:p-8">
                            <QuoteForm
                                title="Get a Custom Quote"
                                submitEndpoint="/api/quotes"
                                successMessage="Thank you! Your quote request has been submitted."
                                showTitle={true}
                                className="w-full"
                                formId="quote"
                            />
                        </div>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
