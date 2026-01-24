import React, { Component } from 'react'
import HeroSection from '../components/HeroSection'
import Header from '../landing/Header'
import { Head } from '@inertiajs/react'
import Footer from '../landing/Footer'
import CountrySelect from '../components/CountrySelect'

// CountrySelect component with API and logic inside

export default class ContactUs extends Component {
    constructor(props) {
        super(props)
        this.state = {
            selectedCountry: '',
        }
    }

    handleCountryChange = (e) => {
        this.setState({ selectedCountry: e.target.value });
    }

    render() {
        const { selectedCountry } = this.state;

        return (
            <div className="min-h-screen flex flex-col">
                <Head title="Become Customer" />
                <Header />

                <main className="">
                    <HeroSection
                        bgUrl="/assets/bg-contact-us.png"
                        title="Contact Us"
                        text="We’re here for you. If you have a question or comment, contact us today."
                        textColor="text-white"
                        textSize="text-4xl md:text-6xl"
                    />

                    <section className="py-8 sm:py-10 px-2 sm:px-4 md:px-8 container mx-auto">
                        <div className="flex flex-col-reverse lg:flex-row items-stretch w-full gap-8 md:gap-10">
                            {/* Left - Form */}
                            <div className="flex-1 bg-[#F0F2F3] p-4 sm:p-6 md:p-8 lg:p-10 rounded-lg shadow-md">
                                <div className="text-center mb-6">
                                    <h1 className="text-[#002856] font-bold font-inter text-xl sm:text-2xl mb-2">Contact Us</h1>
                                    <span className="text-xs sm:text-sm">Fill out the form below, and a representative will respond to your request.</span>
                                </div>
                                <form className="mx-auto space-y-5 sm:space-y-6 text-left bg-white p-4 sm:p-6 md:p-8 rounded shadow">
                                    {/* First & Last Name */}
                                    <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                First Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Last Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                    </div>
                                    {/* Email & Company Name */}
                                    <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Email Address <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="email"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Company Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                    </div>
                                    {/* Post Code & Country */}
                                    <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Post Code <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Country <span className="text-red-500">*</span>
                                            </label>
                                            <CountrySelect
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required={true}
                                                value={selectedCountry}
                                                onChange={this.handleCountryChange}
                                            />
                                        </div>
                                    </div>
                                    {/* Postcode & Topic */}
                                    <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Postcode:
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                            />
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Topic:<span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                required
                                            />
                                        </div>
                                    </div>
                                    {/* Comments */}
                                    <div className="flex flex-col">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Comments:
                                            </label>
                                            <textarea
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                                rows={4}
                                            ></textarea>
                                        </div>
                                    </div>
                                    {/* Submit Button */}
                                    <div className="flex justify-start">
                                        <button className="mt-2 md:mt-auto bg-[#0079C2] text-white px-10 sm:px-16 md:px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2 w-full md:w-auto">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                            {/* Right - Info & Image */}
                            <div className="w-full flex-1 flex flex-col justify-start bg-white shadow mb-8 lg:mb-0 lg:mt-0 rounded-lg overflow-hidden">
                                <img
                                    src="/assets/image-contact-us.png"
                                    alt="Contact Us"
                                    className="object-cover w-full h-40 sm:h-52 md:h-64 lg:h-auto"
                                />
                                <div className="text-left text-[#002856] font-poppins text-xs sm:text-sm w-full space-y-3 mb-6 p-4 sm:p-6 md:p-10">
                                    <h2 className="text-[#002856] font-bold font-inter text-lg sm:text-2xl mb-4 ">
                                        Customer Support
                                    </h2>
                                    <div>
                                        <span className="font-semibold">Main reception: </span>
                                        <a
                                            href="tel:+441616878780"
                                            className="underline underline-offset-2 text-[#0079C2] hover:text-[#005b8c]"
                                        >
                                            +44 (0) 161 687 8780
                                        </a>
                                    </div>
                                    <div>
                                        <span className="font-semibold">Sales Support: </span>
                                        <a
                                            href="tel:+441616878787"
                                            className="underline underline-offset-2 text-[#0079C2] hover:text-[#005b8c]"
                                        >
                                            +44 (0) 161 687 8787
                                        </a>
                                    </div>
                                    <div>
                                        <span className="font-semibold">Tech Support: </span>
                                        <a
                                            href="tel:+441616878789"
                                            className="underline underline-offset-2 text-[#0079C2] hover:text-[#005b8c]"
                                        >
                                            +44 (0) 161 687 8789
                                        </a>
                                    </div>
                                    <div>
                                        <span className="font-semibold">Websupport:&nbsp;</span>
                                        <a
                                            href="tel:+441616878785"
                                            className="underline underline-offset-2 text-[#0079C2] hover:text-[#005b8c]"
                                        >
                                            +44 (0) 161 687 8785
                                        </a>
                                    </div>
                                    <div>
                                        <span className="font-semibold">Email&nbsp;: </span>
                                        <a
                                            href="mailto:sts-support@stscare.com"
                                            className="underline underline-offset-2 text-[#0079C2] hover:text-[#005b8c]"
                                        >
                                            sts-support@stscare.com
                                        </a>
                                    </div>
                                    <div className="">
                                        <button className="bg-[#0079C2] text-white px-6 sm:px-10 py-2 rounded font-medium hover:bg-[#005b8c] gap-2 w-full sm:w-auto">
                                            Find Your Branch
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section className="container mx-auto pt-10 sm:pt-16 md:pt-20 pb-6 sm:pb-10">
                        <div className="flex flex-col md:flex-row">
                            {/* Column 1: System Design */}
                            <div className="flex-1 flex flex-col items-center p-4 sm:p-6 text-center mb-6 md:mb-0">
                                <img src="/assets/system.svg" alt="System Design" className="mx-auto h-16 sm:h-20 mb-4" />
                                <h2 className="text-lg sm:text-xl font-semibold text-[#002856] mb-2">System Design</h2>
                                <p className="text-[#414042] mb-4 text-xs sm:text-sm">
                                    Our team of experts is here to help on all your system integration needs.
                                </p>
                                <a
                                    href="/system-design"
                                    className="mt-auto bg-[#0079C2] text-white px-4 sm:px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2 w-full md:w-auto"
                                >
                                    Get Design Support
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </a>
                            </div>
                            {/* Vertical Divider 1 */}
                            <div className="hidden md:flex items-center">
                                <div className="w-[2px] h-1/2 bg-[#CEEDFF] mx-auto" style={{ minHeight: '130px' }}></div>
                            </div>
                            {/* Column 2: About Us */}
                            <div className="flex-1 flex flex-col items-center p-4 sm:p-6 text-center mb-6 md:mb-0">
                                <img src="/assets/Learn About Us.svg" alt="About Us" className="mx-auto h-16 sm:h-20 mb-4" />
                                <h2 className="text-lg sm:text-xl font-semibold text-[#002856] mb-2">About Us</h2>
                                <p className="text-[#414042] mb-4 text-xs sm:text-sm">
                                    For more than 25 years, STS has been the leading security and low-voltage distributor professionals rely on.
                                </p>
                                <a
                                    href="/about-us"
                                    className="mt-auto bg-[#0079C2] text-white px-4 sm:px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2 w-full md:w-auto"
                                >
                                    Learn About Us
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </a>
                            </div>
                            {/* Vertical Divider 2 */}
                            <div className="hidden md:flex items-center">
                                <div className="w-[2px] h-1/2 bg-[#CEEDFF] mx-auto" style={{ minHeight: '130px' }}></div>
                            </div>
                            {/* Column 3: Services */}
                            <div className="flex-1 flex flex-col items-center p-4 sm:p-6 text-center">
                                <img src="/assets/Services.svg" alt="Services" className="mx-auto h-16 sm:h-20 mb-4" />
                                <h2 className="text-lg sm:text-xl font-semibold text-[#002856] mb-2">Services</h2>
                                <p className="text-[#414042] mb-4 text-xs sm:text-sm">
                                    With value-added services, installations are easier and more efficient. See how you can save time and money.
                                </p>
                                <a
                                    href="/commisioning"
                                    className="mt-auto bg-[#0079C2] text-white px-4 sm:px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2 w-full md:w-auto"
                                >
                                    Explore Services
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </section>
                </main>

                <Footer />
            </div>
        )
    }
}
