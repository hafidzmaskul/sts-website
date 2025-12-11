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
                    <section className="bg-[#F0F2F3] py-10 px-10 container mx-auto my-20">
                        <div className="text-center">
                            <h1 className='text-[#002856] font-bold font-inter text-2xl'>STS | Sign In or Register for an Online Account</h1>
                        </div>
                        <div className="flex flex-col md:flex-row md:space-x-8 space-y-8 md:space-y-0 mx-auto mt-8 text-left">
                            {/* Sign In Form */}
                            <form className="flex-1">

                                <h2 className="text-2xl font-bold text-[#002856] mb-4">Sign In</h2>
                                <span>*Please enter information in required fields. </span>
                                <div className=" mt-10 bg-white p-10 space-y-6 md:max-h-[360px] md:h-[360px] overflow-auto">
                                    <div>
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            User Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            Password <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                    </div>
                                    <div className="flex justify-start mt-8">
                                        <button className="bg-[#0079C2] text-white px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                            Sign In
                                        </button>
                                    </div>
                                </div>
                            </form>

                            {/* Registration Form */}
                            <form className="flex-1">
                                <h2 className="text-2xl font-bold text-[#002856] mb-4">Register</h2>
                                <span>*Please enter information in required fields.</span>
                                <div className="mt-10 p-10 space-y-6 md:max-h-none md:h-auto overflow-auto bg-white">
                                    <div className="flex flex-col md:flex-row md:space-x-8">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Account <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                        </div>
                                        <div className="flex-1 mt-6 md:mt-0">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Email Address <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="email"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div className="flex flex-col md:flex-row gap-4 text-xs font-poppins text-[#000000]">
                                        <span>
                                            Don't have an account number?&nbsp;
                                            <a href="#" className="text-[#0079C2] underline">Become a customer.</a>
                                        </span>
                                        <span className="ml-0 md:ml-4 text-[#707070]">
                                            This will become your username.
                                        </span>
                                    </div>
                                    <div>
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            First Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                    </div>
                                    <div className="flex flex-col md:flex-row md:space-x-8">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                First Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Last Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                        </div>

                                    </div>
                                    <div className="flex flex-col md:flex-row md:space-x-8">

                                        <div className='flex-1 mt-6 md:mt-0'>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Select Job Title
                                            </label>
                                            <select
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            >
                                                <option value="">Select Job Title</option>
                                                <option value="manager">Manager</option>
                                                <option value="technician">Technician</option>
                                                <option value="engineer">Engineer</option>
                                                <option value="purchasing">Purchasing</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div className="flex justify-start mt-8">
                                        <button className="bg-[#0079C2] text-white px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                            Register Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>


                </main>

                <Footer />
            </div>
        )
    }
}
