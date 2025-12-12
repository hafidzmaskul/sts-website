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

export default function SystemDesign({ services = [] }) {
    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Services" />
            <Header />
            <HeroSection
                bgUrl="/assets/bg-sistem-design.png"
                title="System Design"
                text="Lorem ipsum dolor sit amet consectetur. Feugiat suspendisse diam mauris nec odio sed feugiat sollicitudin rutrum aliquam."
                textColor="text-white"
                textSize='text-5xl'
            />
            <main className="flex-1 ">
                <section className="flex py-20 flex-col md:flex-row w-full rounded-lg overflow-hidden  mb-16">
                    <div
                        className="w-full md:w-2/5 p-8 flex flex-col  justify-center "
                        style={{ backgroundColor: '#0079C2' }}
                    >
                        <h2 className="text-white font-bold text-2xl mb-4">Commissioning services include:</h2>
                        <ul className="list-disc list-inside text-white space-y-2 ml-2 text-base font-medium flex flex-col ">
                            <li>Checking the installation prior to commissioning</li>
                            <li>Configuration of IP settings on all network devices</li>
                            <li>Installation and configuration of software on all equipment</li>
                            <li>Carrying out necessary firmware or software upgrades</li>
                            <li>Testing installed equipment</li>
                            <li>Troubleshooting tasks</li>
                            <li>Training end-users on the newly installed system</li>
                        </ul>
                    </div>
                    <div className="w-full md:w-3/5 bg-white flex items-stretch">
                        <img
                            src="/assets/commissioning.png"
                            alt="Commissioning"
                            className="object-cover w-full h-full"
                        />
                    </div>

                </section>



                <section className="w-full bg-[#0079C2] py-8 overflow-hidden ">
                    <div className="container mx-auto">
                    <div className="px-4 flex flex-row justify-between gap-2 mb-8">
                        {/* Left: Title */}
                        <div className="flex-1 flex items-start">
                            <h2 className="text-white font-bold text-xl md:text-2xl text-left whitespace-nowrap">
                                Why Choose STS
                            </h2>
                        </div>
                        {/* Right: Bulleted List */}
                        <div className="flex-1 flex justify-end">
                            <ul className="list-disc list-inside text-white text-xs font-medium space-y-0 text-left">
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
;
                                    opacity: 0.5;
                                }
                                .swiper-pagination-bullet-active {
                                    background: #272343;
;
                                    opacity: 1;
                                }
                            `}
                        </style>
                        <Swiper
                            modules={[Pagination, Autoplay]}
                            spaceBetween={30}
                            slidesPerView={1}
                            centeredSlides={true}
                            loop={true}
                            autoplay={{ delay: 3000, disableOnInteraction: false }}
                            pagination={{ clickable: true }}
                            breakpoints={{
                                640: {
                                    slidesPerView: 3,
                                }
                            }}
                            className="w-full pb-12"
                        >
                            {whyChooseItems.map((item, index) => (
                                <SwiperSlide key={index}>
                                    {({ isActive }) => (
                                        <div className={`relative pb-20 flex flex-col items-center transition-all duration-300 ${isActive ? ' z-10' : ' opacity-80'}`}>
                                            <div className="w-full aspect-[4/3] relative  overflow-hidden bg-gray-200">
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
                                            <div className="w-full bg-white py-4  flex items-center justify-center">
                                                <p className="text-[#0079C2] font-bold text-lg tracking-wide">
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
                 <section className='bg-[#F0F2F3] py-20'>
                    <h1 className='font-inter font-bold text-3xl text-center mt-10 mb-15 text-[#002856]'>Benefits of pre-build and configuration</h1>
                    <div className="container mx-auto">
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 justify-center benefit">
                            {aboutbenefits.slice(0, 6).map((benefit, idx) => (
                                <BenefitCard key={idx} image={benefit.image} text={benefit.text} />
                            ))}
                        </div>
                    </div>
                </section>

                <section className="container mx-auto flex flex-row  my-10">
                    {/* Kolom pertama: lebar 10/12 */}
                    <div className="w-6/7 flex flex-col justify-center h-auto">
                        {/* Konten untuk kolom pertama */}
                        <div
                            className="p-8 h-full text-white"
                            style={{
                                background: "linear-gradient(302.66deg, #0079C2 0%, #0069A9 100%)"
                            }}
                        >
                            <h2 className="text-2xl font-bold font-inter mb-4">Resource Hub</h2>
                            <p className='font-inter font-medium text-base'>Discover ADI's latest articles, buying guides and tools tailored to installers and integrators on industry topics</p>
                        </div>
                    </div>
                    {/* Kolom kedua: lebar 2/12 */}
                    <div className="w-2/7">
                        <img src="/assets/resource-cofig.png" className='h-full' alt="" />
                    </div>
                </section>
                <section className="container mx-auto flex flex-row  my-10">
                    {/* Kolom pertama: lebar 10/12 */}
                    <div className="w-full md:w-1/2 flex flex-col  h-auto">
                        <div className="flex flex-col items-center text-center px-4">
                            <h2 className="text-2xl md:text-3xl font-bold mt-20 mb-10">How it works</h2>
                            <div className="flex flex-col gap-10 w-full">
                                {/* Step 1 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step1.svg" alt="Step 1" className="w-20 h-20 mb-4" />
                                    <h3 className="text-lg md:text-2xl font-semibold mb-2 text-[#002856]">Step 1</h3>
                                    <p className="text-base md:text-lg max-w-md">
                                        Before you purchase your cameras, panels or other programmable product, contact us using the form for a free pre-configuration service quote.
                                    </p>
                                </div>
                                {/* Step 2 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step2.png" alt="Step 2" className="w-20 h-20 mb-4" />
                                    <h3 className="text-lg md:text-2xl font-semibold mb-2 text-[#002856]">Step 2</h3>
                                    <p className="text-base md:text-lg max-w-md">
                                        A representative will gather more information about your programming requirements and the scope of your project. Our pre-configuration services are flexible to meet your needs.
                                    </p>
                                </div>
                                {/* Step 3 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step3.png" alt="Step 3" className="w-20 h-20 mb-4" />
                                    <h3 className="text-lg md:text-2xl font-semibold mb-2 text-[#002856]">Step 3</h3>
                                    <p className="text-base md:text-lg max-w-md">
                                        The STS technical team will review your programming needs and design a price based on the level of service you prefer (bronze, silver or gold) or based on your customized project. You'll receive a tailored quote from your account manager.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/* Kolom kedua: lebar 2/12 */}
                    <div className="w-full md:w-1/2 bg-[#F0F2F3]" >
                        <h1 className='text-2xl md:text-3xl font-bold mt-20 mb-10 text-center'>Get Quote</h1>
                        <div className=" p-10">
                            <form className="px-8 pt-6 pb-8 mb-4 bg-white">
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        First Name<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your first name"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Surname<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your surname"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Company Name<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your company name"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Email Address<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your email address"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Phone Number<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your phone number"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Country<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your country"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Postal Code<span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                        placeholder="Enter your postal code"
                                    />
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2">
                                        Details of your Project<span className="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none resize-none"
                                        rows={3}
                                        placeholder="Enter project details"
                                    />
                                </div>
                                <div className="flex items-center mb-6">
                                    <input
                                        type="checkbox"
                                        id="opt_in"
                                        className="mr-2"
                                    />
                                    <label htmlFor="opt_in" className="text-gray-700 text-sm">
                                        Opt-in to STS Marketing Emails
                                    </label>
                                </div>
                                <div className="flex items-center justify-start mt-8">
                                    <button
                                        type="submit"
                                        className="bg-[#0069A9] hover:bg-[#005885] text-white font-normal py-2 px-20 rounded focus:outline-none focus:shadow-outline"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </main>

            <Footer />
        </div>
    );
}
