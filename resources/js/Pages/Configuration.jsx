import React, { useState, useRef } from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import BenefitCard from '../components/BenefitCard';
import QuoteForm from '../components/QuoteForm'; // Import the new component

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

export default function Configuration({ services = [] }) {
    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Services" />
            <Header />
            <HeroSection
                bgUrl="/assets/configuration-hero.jpg"
                title="Pre-build and Configuration"
                text="Lorem ipsum dolor sit amet consectetur. Feugiat suspendisse diam mauris nec odio sed feugiat sollicitudin rutrum aliquam."
                textColor="text-black"
            />
            <main className="flex-1 ">
                {/* HERO SECTION IMAGE & TEXT */}
                <section>
                    <div className="container px-4 md:px-10 lg:px-20 mx-auto py-10 md:py-20 flex flex-col md:flex-row flex-wrap">
                        <div className="w-full md:w-4/6 flex justify-center items-center">
                            <img
                                src="/assets/img-condiguration.jpg"
                                className="w-full h-auto max-h-[350px] object-cover rounded-lg"
                                alt=""
                            />
                        </div>
                        <div className="w-full md:w-2/6 px-0 md:px-4 flex mt-10 md:mt-20 flex-row justify-between h-full">
                            <div>
                                <h1 className="font-inter text-[#0079C2] font-bold text-xl md:text-2xl mb-2">
                                    Have your order pre-built and configured prior to delivery
                                </h1>
                                <span className="font-poppins text-sm md:text-base">
                                    From our central support centre your order can be pre-built and configured prior to delivery, eliminating risk with out-of-box failures and saving time and money on site. <br /><br />
                                    Built and configured in a safe and isolated environment, our team can <br />
                                    conduct tests to identify faults and help eliminate costly risks of failures. We are also able to make customizations, so your equipment is ready for installation upon delivery – meaning less downtime trying to arrange replacements and less time on site. To get a quote for a project or place an order for pre-configuration services,
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
                {/* SERVICES INCLUDE SECTION */}
                <section className="container px-4 md:px-10 lg:px-20 mx-auto">
                    <div
                        style={{
                            background: 'linear-gradient(90deg, #2A5180 0%, #1A324E 100%)',
                        }}
                        className="w-full p-4 md:pl-5 md:py-5 rounded-lg"
                    >
                        <h1 className="font-nunito-sans font-bold text-white text-xl md:text-3xl mb-6">Services include:</h1>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-4 justify-center h-full text-white">
                            <div className="flex items-start">
                                <ul className="list-disc ml-5 md:ml-6 space-y-2 font-medium text-sm md:text-base">
                                    <li>Configuration of IP settings on all network devices</li>
                                    <li>Installation and configuration of software on all equipment</li>
                                    <li>Configuration of recording and viewing resolutions</li>
                                    <li>Configuration of remote viewing PCs</li>
                                    <li>Check that firmware is updated to the latest version</li>
                                </ul>
                            </div>
                            <div className="flex items-start">
                                <ul className="list-disc ml-5 md:ml-6 space-y-2 font-medium text-sm md:text-base">
                                    <li>Create and install custom firmware</li>
                                    <li>Testing for out-of-box failures</li>
                                    <li>Hardware optimisation and guaranteed storage for qualifying enterprise solutions</li>
                                    <li>Custom label equipment for easy location whilst on site</li>
                                    <li>Provide full system documentation</li>
                                </ul>
                            </div>
                            <div className="flex items-center justify-center">
                                <img
                                    src="/assets/service-include-img.png"
                                    alt=""
                                    className="w-2/3 md:w-full max-w-xs h-auto"
                                />
                            </div>
                        </div>
                    </div>
                </section>
                {/* BENEFITS SECTION */}
                <section className="bg-[#F0F2F3] my-10 md:my-20 py-10 md:py-20">
                    <h1 className="font-inter font-bold text-2xl md:text-3xl text-center mt-8 md:mt-10 mb-10 text-[#002856]">
                        Benefits of pre-build and configuration
                    </h1>
                    <div className="container mx-auto px-4 md:px-10 lg:px-20">
                        <div className="grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 justify-center benefit">
                            {aboutbenefits.slice(0, 6).map((benefit, idx) => (
                                <BenefitCard key={idx} image={benefit.image} text={benefit.text} />
                            ))}
                        </div>
                    </div>
                </section>
                {/* PRE-CONFIGURATION SERVICES TABLE */}
                <section className="container px-4 md:px-10 lg:px-20 mx-auto">
                    <h1 className="font-inter font-bold text-2xl md:text-3xl text-center mt-10 mb-6 md:mb-15 text-[#002856]">
                        Pre-configuration services
                    </h1>
                    <p className="text-center font-poppins font-normal text-sm md:text-base mb-6">
                        Whether you are working on a small project, a complex integrated solution or require a bespoke install, ADI's pre-build and configuration service can save you time and money. See below for our levels of pre-configuration services:
                    </p>
                    <div className="mt-6 md:mt-12 flex flex-col md:flex-row justify-center pt-6 md:pt-10 pb-10 md:pb-20 overflow-x-auto">
                        <div className="w-full">
                            <table className="min-w-max w-full font-nunito-sans text-left border-collapse text-xs md:text-base">
                                <thead>
                                    <tr>
                                        <th className="w-1/4"></th>
                                        <th className="py-2 md:py-3 text-center px-2 md:px-6 border border-[#9F9F9F] text-white" style={{ background: '#CD7F32' }}>
                                            BRONZE
                                        </th>
                                        <th className="py-2 md:py-3 text-center px-2 md:px-6 border border-[#9F9F9F] text-white" style={{ background: '#9F9F9F' }}>
                                            SILVER
                                        </th>
                                        <th className="py-2 md:py-3 text-center px-2 md:px-6 border border-[#9F9F9F] text-white" style={{ background: '#E0CB07' }}>
                                            GOLD
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white">
                                    {[
                                        "Program IP address",
                                        "Program subnet mask",
                                        "Program gateway",
                                        "Update latest firmware (camera and recorder)",
                                        "Add camera to recorder",
                                        "Install and configure HDD (recorder)",
                                        "Camera name (recorder)",
                                        "Set camera resolution (recorder)",
                                        "Set camera frame rate (recorder)",
                                        "Set recording schedule (recorder)",
                                        "Client workstation configuration"
                                    ].map((label, idx) => {
                                        const bronzeFeatures = [
                                            "Program IP address",
                                            "Program subnet mask",
                                            "Program gateway",
                                            "Update latest firmware (camera and recorder)"
                                        ];
                                        const silverFeatures = [
                                            "Program IP address",
                                            "Program subnet mask",
                                            "Program gateway",
                                            "Update latest firmware (camera and recorder)",
                                            "Add camera to recorder",
                                            "Install and configure HDD (recorder)",
                                            "Camera name (recorder)"
                                        ];
                                        return (
                                            <tr key={idx} className="">
                                                <td className="py-2 md:py-3 px-2 md:px-6 border border-[#9F9F9F] font-medium text-gray-800 whitespace-pre-line">
                                                    {label}
                                                </td>
                                                {/* Bronze */}
                                                <td className="py-2 md:py-3 px-2 md:px-6 border border-[#9F9F9F] text-center">
                                                    {bronzeFeatures.includes(label) && (
                                                        <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-6 h-6 md:w-8 md:h-8 text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width={20} height={20} md-width={24} md-height={24} viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"></path>
                                                            </svg>
                                                        </span>
                                                    )}
                                                </td>
                                                {/* Silver */}
                                                <td className="py-2 md:py-3 px-2 md:px-6 border border-[#9F9F9F] text-center">
                                                    {silverFeatures.includes(label) && (
                                                        <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-6 h-6 md:w-8 md:h-8 text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width={20} height={20} md-width={24} md-height={24} viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"></path>
                                                            </svg>
                                                        </span>
                                                    )}
                                                </td>
                                                {/* Gold (semua fitur) */}
                                                <td className="py-2 md:py-3 px-2 md:px-6 border border-[#9F9F9F] text-center">
                                                    <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-6 h-6 md:w-8 md:h-8 text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width={20} height={20} md-width={24} md-height={24} viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"></path>
                                                        </svg>
                                                    </span>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                {/* RESOURCE HUB SECTION */}
                <section className="container px-4 md:px-10 lg:px-20 mx-auto flex flex-col md:flex-row my-8 md:my-10 gap-4 md:gap-0">
                    {/* Kolom pertama */}
                    <div className="w-full md:w-5/6 flex flex-col justify-center h-auto">
                        <div
                            className="p-6 md:p-8 h-full text-white rounded-t-lg md:rounded-l-lg md:rounded-t-none"
                            style={{
                                background: "linear-gradient(302.66deg, #0079C2 0%, #0069A9 100%)"
                            }}
                        >
                            <h2 className="text-xl md:text-2xl font-bold font-inter mb-2 md:mb-4">Resource Hub</h2>
                            <p className="font-inter font-medium text-sm md:text-base">
                                Discover ADI's latest articles, buying guides and tools tailored to installers and integrators on industry topics
                            </p>
                        </div>
                    </div>
                    {/* Kolom kedua */}
                    <div className="w-full md:w-1/6 flex justify-center md:justify-end items-center bg-none md:bg-transparent rounded-b-lg md:rounded-r-lg md:rounded-b-none">
                        <img
                            src="/assets/resource-cofig.png"
                            className="w-32 md:w-full h-auto md:h-full object-contain"
                            alt=""
                        />
                    </div>
                </section>
                {/* HOW IT WORKS & QUOTE FORM SECTION */}
                <section id="form-quote" className="container px-4 md:px-10 lg:px-20 mx-auto flex flex-col md:flex-row my-8 md:my-10 gap-10 md:gap-0">
                    {/* Steps */}
                    <div className="w-full md:w-1/2 flex flex-col h-auto">
                        <div className="flex flex-col items-center text-center px-2 md:px-4">
                            <h2 className="text-xl md:text-3xl font-bold mt-10 md:mt-20 mb-6 md:mb-10">
                                How it works
                            </h2>
                            <div className="flex flex-col gap-6 md:gap-10 w-full">
                                {/* Step 1 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step1.svg" alt="Step 1" className="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4" />
                                    <h3 className="text-base md:text-2xl font-semibold mb-1 md:mb-2 text-[#002856]">Step 1</h3>
                                    <p className="text-sm md:text-lg max-w-md">
                                        Before you purchase your cameras, panels or other programmable product, contact us using the form for a free pre-configuration service quote.
                                    </p>
                                </div>
                                {/* Step 2 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step2.png" alt="Step 2" className="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4" />
                                    <h3 className="text-base md:text-2xl font-semibold mb-1 md:mb-2 text-[#002856]">Step 2</h3>
                                    <p className="text-sm md:text-lg max-w-md">
                                        A representative will gather more information about your programming requirements and the scope of your project. Our pre-configuration services are flexible to meet your needs.
                                    </p>
                                </div>
                                {/* Step 3 */}
                                <div className="flex flex-col items-center w-full">
                                    <img src="/assets/step3.png" alt="Step 3" className="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4" />
                                    <h3 className="text-base md:text-2xl font-semibold mb-1 md:mb-2 text-[#002856]">Step 3</h3>
                                    <p className="text-sm md:text-lg max-w-md">
                                        The STS technical team will review your programming needs and design a price based on the level of service you prefer (bronze, silver or gold) or based on your customized project. You'll receive a tailored quote from your account manager.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/* Quote Form */}
                    <div className="w-full md:w-1/2 bg-[#F0F2F3] flex items-center justify-center px-0 md:px-12 py-6 md:py-0">
                        <QuoteForm
                            title="Get Quote"
                            submitEndpoint="/api/quotes"
                            successMessage="Thank you! Your quote request has been submitted."
                            showTitle={true}
                            className="w-full max-w-lg"
                            formId="quote"
                        />
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
