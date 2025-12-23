import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';

export default function LoginModal({ isOpen, onClose }) {
    const [showPassword, setShowPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login', {
            onFinish: () => reset('password'),
            onSuccess: () => onClose(), // Close modal on success if needed
        });
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center" role="dialog" aria-modal="true">
            {/* Backdrop */}
            <div
                className="fixed inset-0 bg-[#1976D2E5] backdrop-blur-sm transition-opacity"
                onClick={onClose}
                aria-hidden="true"
            ></div>

            {/* Modal Panel - Two Column */}
            <div className="relative w-full max-w-5xl mx-auto bg-white shadow-2xl flex overflow-hidden">
                {/* Modal Title */}
                <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full z-10 w-full text-center mb-6">
                    <h2 className="text-3xl font-bold text-[#1976D2] font-inter bg-white px-4 py-2 rounded-t-md shadow-md inline-block">
                        Login & Register
                    </h2>
                </div>
                {/* Left Side - Login Form with blue background */}
                <div className="w-1/2 bg-[#039BE5] px-8 py-10 flex flex-col justify-between">
                    <div>
                        <div className="text-center mb-8">

                            <h3 className="text-2xl font-bold text-white font-inter">
                                Already a Customer?
                            </h3>
                            <p className='font-inter font-regular text-white'>Login Here</p>

                        </div>

                        <form onSubmit={submit} className="space-y-5">
                            {/* Email Input */}
                            <div className="relative">
                                <input
                                    type="email"
                                    id="modal-email"
                                    name="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder=" "
                                    className="w-full px-3 pt-5 pb-2 border border-[#E0E0E0] bg-[#039BE5] text-white outline-none transition-all duration-200 focus:border-white focus:ring-0 peer text-sm placeholder-transparent"
                                />
                                <label
                                    htmlFor="modal-email"
                                    className="absolute left-3 top-3.5 text-white/70 text-sm transition-all duration-200 pointer-events-none
                                    peer-focus:top-1 peer-focus:text-xs peer-focus:text-white
                                    peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                                >
                                    Email Address
                                </label>
                                {errors.email && <p className="mt-1 text-xs text-yellow-200">{errors.email}</p>}
                            </div>

                            {/* Password Input */}
                            <div className="relative">
                                <input
                                    type={showPassword ? "text" : "password"}
                                    id="modal-password"
                                    name="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder=" "
                                    className="w-full px-3 pt-5 pb-2 pr-10 border border-[#E0E0E0] bg-[#039BE5] text-white outline-none transition-all duration-200 focus:border-white focus:ring-0 peer text-sm placeholder-transparent"
                                />
                                <label
                                    htmlFor="modal-password"
                                    className="absolute left-3 top-3.5 text-white/70 text-sm transition-all duration-200 pointer-events-none
                                    peer-focus:top-1 peer-focus:text-xs peer-focus:text-white
                                    peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                                >
                                    Password
                                </label>
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors"
                                >
                                    {showPassword ? (
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5" />
                                        </svg>
                                    ) : (
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7" />
                                        </svg>
                                    )}
                                </button>
                            </div>

                            {/* Remember & Forgot Password */}
                            <div className="flex items-center justify-between text-xs">
                                <a href="/forgot-password" className="text-white font-medium underline underline-offset-2 hover:opacity-75">
                                    Forgot password?
                                </a>
                            </div>

                            {/* Submit Button */}
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-white text-[#039BE5] px-4 py-3 text-sm font-semibold rounded shadow hover:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                            >
                                {processing ? 'Signing in...' : 'Sign In'}
                            </button>
                        </form>
                    </div>

                </div>
                {/* Right Side - Register Form with Name, Email, Password */}
                <div className="w-1/2 bg-white px-8 py-10 flex flex-col justify-center">
                    <div className="text-center mb-8">

                        <h3 className="text-2xl font-bold text-[#039BE5] font-inter">
                            Not a Trade Customer
                        </h3>
                        <p className='font-inter font-regular text-[#C7C7C7]'>You can get benefit, great saving,etc</p>

                    </div>
                    <form className="space-y-5">
                        {/* Name Input */}
                        <div className="relative">
                            <input
                                type="text"
                                id="modal-register-name"
                                name="register-name"
                                placeholder=" "
                                className="w-full px-3 pt-5 pb-2 border border-[#E0E0E0] bg-white text-gray-900 outline-none transition-all duration-200 focus:border-[#039BE5] focus:ring-0 peer text-sm"
                                autoComplete="off"
                            />
                            <label
                                htmlFor="modal-register-name"
                                className="absolute left-3 top-3.5 text-gray-500 text-sm transition-all duration-200 pointer-events-none
                                peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#039BE5]
                                peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Name
                            </label>
                        </div>
                        {/* Email Input */}
                        <div className="relative">
                            <input
                                type="email"
                                id="modal-register-email"
                                name="register-email"
                                placeholder=" "
                                className="w-full px-3 pt-5 pb-2 border border-[#E0E0E0] bg-white text-gray-900 outline-none transition-all duration-200 focus:border-[#039BE5] focus:ring-0 peer text-sm"
                                autoComplete="off"
                            />
                            <label
                                htmlFor="modal-register-email"
                                className="absolute left-3 top-3.5 text-gray-500 text-sm transition-all duration-200 pointer-events-none
                                peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#039BE5]
                                peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Email
                            </label>
                        </div>
                        {/* Password Input */}
                        <div className="relative">
                            <input
                                type="password"
                                id="modal-register-password"
                                name="register-password"
                                placeholder=" "
                                className="w-full px-3 pt-5 pb-2 border border-[#E0E0E0] bg-white text-gray-900 outline-none transition-all duration-200 focus:border-[#039BE5] focus:ring-0 peer text-sm"
                            />
                            <label
                                htmlFor="modal-register-password"
                                className="absolute left-3 top-3.5 text-gray-500 text-sm transition-all duration-200 pointer-events-none
                                peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#039BE5]
                                peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Password
                            </label>
                        </div>
                        {/* Register Button - disable submit logic here, demo only */}
                        <button
                            type="button"
                            className="w-full bg-[#039BE5] text-white px-4 py-3 text-sm font-semibold rounded shadow hover:bg-[#0079C2] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#039BE5] transition-all"
                        >
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}

