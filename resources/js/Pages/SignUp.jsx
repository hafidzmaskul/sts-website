import React, { useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import { Head } from '@inertiajs/react';

// Custom Toastr Implementation
function toast(message, type = 'success') {
    // Remove existing toast if present
    const existing = document.getElementById('custom-toast-notification');
    if (existing) {
        existing.remove();
    }

    let bgColor = '#4BB543'; // success
    if (type === 'error') {
        bgColor = '#dc2626';
    } else if (type === 'info') {
        bgColor = '#2563eb';
    } else if (type === 'warning') {
        bgColor = '#f59e42';
    }

    const toastDiv = document.createElement('div');
    toastDiv.id = 'custom-toast-notification';
    toastDiv.style.position = 'fixed';
    toastDiv.style.top = '32px';
    toastDiv.style.right = '32px';
    toastDiv.style.zIndex = 10000;
    toastDiv.style.background = bgColor;
    toastDiv.style.color = '#fff';
    toastDiv.style.padding = '14px 32px';
    toastDiv.style.borderRadius = '6px';
    toastDiv.style.boxShadow = '0 2px 12px rgba(0,0,0,0.14)';
    toastDiv.style.fontSize = '16px';
    toastDiv.style.fontWeight = 'bold';
    toastDiv.style.opacity = '0.96';
    toastDiv.textContent = message;

    document.body.appendChild(toastDiv);

    setTimeout(() => {
        toastDiv.style.transition = 'opacity 0.3s';
        toastDiv.style.opacity = '0';
        setTimeout(() => {
            if (toastDiv.parentNode) toastDiv.parentNode.removeChild(toastDiv);
        }, 300);
    }, 2800);
}

export default function BecomeCustomer() {
    const [data, setData] = useState({
        account: '',
        email: '',
        first_name: '',
        last_name: '',
        job_title: '',
    });

    const [errors, setErrors] = useState({});
    const [processing, setProcessing] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});
        try {
            const response = await fetch('http://127.0.0.1:8000/api/sign-up', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            const result = await response.json();

            if (response.ok) {
                toast('Registration submitted successfully.', 'success');
                setData({
                    account: '',
                    email: '',
                    first_name: '',
                    last_name: '',
                    job_title: '',
                });
            } else {
                if (result.errors) {
                    setErrors(result.errors);
                    // Optionally, tampilkan error global:
                    if (result.message) toast(result.message, 'error');
                } else if (result.message) {
                    toast(result.message, 'error');
                } else {
                    toast('Registration failed. Please check your input.', 'error');
                }
            }
        } catch (err) {
            toast('Something went wrong. Please try again later.', 'error');
        } finally {
            setProcessing(false);
        }
    };

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
                        <form onSubmit={handleSubmit} className="flex-1">
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
                                            value={data.account}
                                            onChange={e => setData({ ...data, account: e.target.value })}
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                        {errors.account && <div className="text-red-500 text-xs mt-1">{errors.account}</div>}
                                    </div>
                                    <div className="flex-1 mt-6 md:mt-0">
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            Email Address <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData({ ...data, email: e.target.value })}
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                        {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
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

                                <div className="flex flex-col md:flex-row md:space-x-8">
                                    <div className="flex-1">
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            First Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.first_name}
                                            onChange={e => setData({ ...data, first_name: e.target.value })}
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                        {errors.first_name && <div className="text-red-500 text-xs mt-1">{errors.first_name}</div>}
                                    </div>
                                    <div className="flex-1">
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            Last Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.last_name}
                                            onChange={e => setData({ ...data, last_name: e.target.value })}
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            required
                                        />
                                        {errors.last_name && <div className="text-red-500 text-xs mt-1">{errors.last_name}</div>}
                                    </div>
                                </div>
                                <div className="flex flex-col md:flex-row md:space-x-8">
                                    <div className='flex-1 mt-6 md:mt-0'>
                                        <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                            Select Job Title
                                        </label>
                                        <select
                                            value={data.job_title}
                                            onChange={e => setData({ ...data, job_title: e.target.value })}
                                            className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                        >
                                            <option value="">Select Job Title</option>
                                            <option value="manager">Manager</option>
                                            <option value="technician">Technician</option>
                                            <option value="engineer">Engineer</option>
                                            <option value="purchasing">Purchasing</option>
                                            <option value="other">Other</option>
                                        </select>
                                        {errors.job_title && <div className="text-red-500 text-xs mt-1">{errors.job_title}</div>}
                                    </div>
                                </div>

                                <div className="flex justify-start mt-8">
                                    <button type="submit" disabled={processing} className="bg-[#0079C2] text-white px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                        {processing ? 'Registering...' : 'Register Now'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
