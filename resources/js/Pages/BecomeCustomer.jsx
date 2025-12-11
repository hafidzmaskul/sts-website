import React, { Component } from 'react'
import HeroSection from '../components/HeroSection'
import Header from '../landing/Header'
import { Head } from '@inertiajs/react'
import Footer from '../landing/Footer'

export default class BecomeCustomer extends Component {
    render() {
        return (
            <div className="min-h-screen flex flex-col">
                <Head title="Become Customer" />
                <Header />

                <main className="">

                    <HeroSection
                        bgUrl="/assets/bg-become-customer.png"
                        title="Become a Customer"
                        text="Register for a new STS account for access to unparalleled product selection, support and services"
                        textColor="text-white"
                        textSize="text-6xl"
                    />
                    <section>
                        <div className="container mx-auto py-20 text-center">
                            <h1 className='text-[#002856] font-bold font-inter text-2xl'>New Customer Form</h1>
                            <span>Complete the form below to become a new STS customer. An STS representative will contact you shortly to continue with your application. Please note that STS does not sell to end users. If you are an existing customer, Complete the form below to become a new ADI customer. An STS representative will contact you shortly to continue with your application. Please note that STS does not sell to end users. If you are an existing customer, <a href="#" className='text-[#0079C2]'>sign in or register for an online account.</a> </span>
                        </div>

                    </section>
                    <section className="container mx-auto  pt-20 pb-10">
                        <div className="flex flex-col md:flex-row">
                            {/* Column 1 */}
                            <div className="flex-1 flex flex-col items-center p-6 text-center">
                                <img src="/assets/Learn About Us.svg" alt="About Us" className="mx-auto h-20 mb-4" />
                                <h2 className="text-xl font-semibold text-[#002856] mb-2">About Us</h2>
                                <p className="text-[#414042] mb-4">For more than 25 years, ADI has been the leading security and low-voltage distributor professionals rely on.</p>
                                <button className="mt-auto bg-[#0079C2] text-white px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                    Learn About Us
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </button>
                            </div>
                            {/* Vertical Divider 1 */}
                            <div className="hidden md:flex items-center">
                                <div className="w-[2px] h-1/2 bg-[#CEEDFF] mx-auto" style={{ minHeight: '130px' }}></div>
                            </div>

                            {/* Column 2 */}
                            <div className="flex-1 flex flex-col items-center p-6 text-center">
                                <img src="/assets/steper.svg" alt="Online Account Benefits" className="mx-auto h-20 mb-4" />
                                <h2 className="text-xl font-semibold text-[#002856] mb-2">Online Account Benefits</h2>
                                <p className="text-[#414042] mb-4">Growing your business and serving your customers is easier and faster with the benefits of an online account at ADI.</p>
                                <button className="mt-auto bg-[#0079C2] text-white px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                    Discover Benefits
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </button>
                            </div>
                            {/* Vertical Divider 2 */}
                            <div className="hidden md:flex items-center">
                                <div className="w-[2px] h-1/2 bg-[#CEEDFF] mx-auto" style={{ minHeight: '130px' }}></div>
                            </div>

                            {/* Column 3 */}
                            <div className="flex-1 flex flex-col items-center p-6 text-center">
                                <img src="/assets/Services.svg" alt="Services" className="mx-auto h-20 mb-4" />
                                <h2 className="text-xl font-semibold text-[#002856] mb-2">Services</h2>
                                <p className="text-[#414042] mb-4">With value-added services, installations are easier and more efficient. Learn how ADI can help you and your clients deliver the best possible value.</p>
                                <button className="mt-auto bg-[#0079C2] text-white px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                    Explore Service
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </button>
                            </div>
                            {/* Vertical Divider 3 */}
                            <div className="hidden md:flex items-center">
                                <div className="w-[2px] h-1/2 bg-[#CEEDFF] mx-auto" style={{ minHeight: '130px' }}></div>
                            </div>

                            {/* Column 4 */}
                            <div className="flex-1 flex flex-col items-center p-6 text-center">
                                <img src="/assets/solution-lamp.svg" alt="Solutions" className="mx-auto h-20 mb-4" />
                                <h2 className="text-xl font-semibold text-[#002856] mb-2">Solutions</h2>
                                <p className="text-[#414042] mb-4">Learn how our offerings provide solutions to help you achieve best-in-class commercial and residential installations.</p>
                                <button className="mt-auto bg-[#0079C2] text-white px-5 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                    View Project Solutions
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>
                    <section className="bg-[#F0F2F3] py-10 px-10 container mx-auto">
                        <div className="text-center">
                            <h1 className='text-[#002856] font-bold font-inter text-2xl'>Contact Informations</h1>
                            <span>*Please enter information in required fields. </span>
                        </div>
                        <form className="mx-auto mt-8 space-y-6 text-left bg-white p-10">
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
                            {/* Post Code & VAT Registration Number */}
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
                                        VAT Registration Number
                                    </label>
                                    <input
                                        type="text"
                                        className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                    />
                                </div>
                            </div>
                            {/* Business Phone & Security Division */}
                            <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                <div className="flex-1">
                                    <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                        Business Phone <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                        required
                                    />
                                </div>
                                <div className="flex-1">
                                    <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                        Which division of security is your business in? <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                        required
                                    />
                                </div>
                            </div>
                            {/* Account Type */}
                            <div className="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                <div className="flex-1">
                                    <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                        What type of account do you want to open with STS Global Distribution? <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                        required
                                    />
                                </div>
                                <div className="flex-1">
                                    <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                        STS Global Distribution does not sell to end users. I confirm I am a fire/security installer or integrator <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 focus:outline-none focus:border-b-2 focus:border-[#000000] bg-transparent"
                                        required
                                    />
                                </div>
                            </div>
                            {/* Submit Button */}
                            <div className="flex justify-start">
                                <button className="mt-auto bg-[#0079C2] text-white px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </section>


                </main>

                <Footer />
            </div>
        )
    }
}
