import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import BenefitCard from '../components/BenefitCard';



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

export default function Commisioning({ services = [] }) {
    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Services" />
            <Header />
            <HeroSection
                bgUrl="/assets/bg-commisioning.png"
                title="Commisioning"
                text="Lorem ipsum dolor sit amet consectetur. Feugiat suspendisse diam mauris nec odio sed feugiat sollicitudin rutrum aliquam."
                textColor="text-black"
                textAlign="end"
                textSize='text-5xl'
            />
            <main className="flex-1 ">
                <section>
                    <div className="container px-10 md:px-20 mx-auto py-20 flex flex-wrap">
                        <div className="w-full md:w-4/6">
                            <img src="/assets/img-condiguration.jpg" className='h-150' alt="" />
                        </div>
                        <div className="w-full md:w-2/6 px-4 flex mt-20 flex-row justify-between h-full">
                            <div>
                                <h1 className='font-inter text-[#0079C2] font-bold text-2xl mb-2'>
                                    Have your order pre-built and configured prior to delivery
                                </h1>

                                <span className='font-poppins text-base'>
                                    From our central support centre your order can be pre-built and configured prior to delivery, eliminating risk with out-of-box failures and saving time and money on site. <br /><br />
                                    Built and configured in a safe and isolated environment, our team can <br />
                                    conduct tests to identify faults and help eliminate costly risks of failures. We are also able to make customizations, so your equipment is ready for installation upon delivery – meaning less downtime trying to arrange replacements and less time on site. To get a quote for a project or place an order for pre-configuration services,
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
                <section className='container md:px-10 px-20 mx-auto'>
                    <div
                        style={{
                            background: 'linear-gradient(90deg, #2A5180 0%, #1A324E 100%)',
                        }}
                        className="w-full pl-5 py-5 rounded-lg"
                    >
                        <h1 className='font-nunito-sans font-bold text-white text-3xl'>Services include:</h1>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 justify-center h-full  text-white">
                            <div className="flex items-center">

                                <ul className="list-disc ml-6 space-y-1 font-medium text-base">
                                    <li>Configuration of IP settings on all network devices</li>
                                    <li>Installation and configuration of software on all equipment</li>
                                    <li>Configuration of recording and viewing resolutions</li>
                                    <li>Configuration of remote viewing PCs</li>
                                    <li>Check that firmware is updated to the latest version</li>
                                </ul>
                            </div>
                            <div className="flex items-center">

                                <ul className="list-disc ml-6 space-y-1 font-medium text-base">
                                    <li>Create and install custom firmware</li>
                                    <li>Testing for out-of-box failures</li>
                                    <li>Hardware optimisation and guaranteed storage for qualifying enterprise solutions</li>
                                    <li>Custom label equipment for easy location whilst on site</li>
                                    <li>Provide full system documentation</li>
                                </ul>
                            </div>
                            <div className="flex items-center">
                                <img src="/assets/service-include-img.png" alt="" />
                            </div>
                        </div>
                    </div>

                </section>
                <section className='bg-[#F0F2F3] my-20 py-20'>
                    <h1 className='font-inter font-bold text-3xl text-center mt-10 mb-15 text-[#002856]'>Benefits of pre-build and configuration</h1>
                    <div className="container mx-auto px-10 md:px-20">
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 justify-center benefit">
                            {aboutbenefits.slice(0, 6).map((benefit, idx) => (
                                <BenefitCard key={idx} image={benefit.image} text={benefit.text} />
                            ))}
                        </div>
                    </div>
                </section>
                <section className='container md:px-20 px-10 mx-auto'>

                    <h1 className='  font-inter font-bold text-3xl text-center mt-10 mb-15 text-[#002856]'>Pre-configuration services</h1>
                    <p className=' text-center font-poppins font-normal'> Whether you are working on a small project, a complex integrated solution or require a bespoke install, ADI’s pre-build and configuration service can save you time and money. See below for our levels of pre-configuration services: </p>
                    <div className="mt-12 flex justify-center pt-10 pb-20">
                        <table className="min-w-max w-full font-nunito-sans text-left border-collapse ">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th className="py-3 text-center px-6 border border-[#9F9F9F] text-white" style={{ background: '#CD7F32' }}>BRONZE</th>
                                    <th className="py-3 text-center px-6 border border-[#9F9F9F] text-white" style={{ background: '#9F9F9F' }}>SILVER</th>
                                    <th className="py-3 text-center px-6 border border-[#9F9F9F] text-white" style={{ background: '#E0CB07' }}>GOLD</th>
                                </tr>

                            </thead>
                            <tbody className="bg-white ">
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
                                    // Kebijakan feature setiap package
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
                                    // gold: semua fitur
                                    return (
                                        <tr key={idx} className="">
                                            <td className="py-3 px-6 border border-[#9F9F9F] font-medium text-gray-800">
                                                {label}
                                            </td>
                                            {/* Bronze */}
                                            <td className="py-3 px-6 border border-[#9F9F9F] text-center">
                                                {bronzeFeatures.includes(label) && (
                                                    <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-8 h-8 text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"></path>
                                                        </svg>
                                                    </span>
                                                )}
                                            </td>
                                            {/* Silver */}
                                            <td className="py-3 px-6 border border-[#9F9F9F] text-center">
                                                {silverFeatures.includes(label) && (
                                                    <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-8 h-8 text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"></path>
                                                        </svg>
                                                    </span>
                                                )}
                                            </td>
                                            {/* Gold (semua fitur) */}
                                            <td className="py-3 px-6 border border-[#9F9F9F] text-center">
                                                <span className="inline-flex items-center justify-center bg-green-500 rounded-full w-8 h-8 text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
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
                </section>
                <section className="container md:px-20 px-10 mx-auto flex flex-row  my-10">
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
                <section className="container md:px-20 px-10 mx-auto flex flex-row  my-10">
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
