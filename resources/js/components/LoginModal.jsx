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
        <div className="fixed inset-0 z-[100] flex items-start justify-end p-4 sm:p-6" role="dialog" aria-modal="true">
            {/* Backdrop */}
            <div
                className="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"
                onClick={onClose}
                aria-hidden="true"
            ></div>

            {/* Modal Panel - Top Right */}
            <div className="relative w-full max-w-sm transform overflow-hidden rounded-xl bg-white p-6 text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-gray-100">
                <div className="flex justify-between items-center mb-6">
                    <h3 className="text-lg font-bold text-[#232323] font-inter">
                        Log In to STS
                    </h3>
                    <button
                        onClick={onClose}
                        className="text-gray-400 hover:text-gray-500 transition-colors"
                    >
                        <span className="sr-only">Close</span>
                        <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form onSubmit={submit} className="space-y-4">
                    {/* Email Input */}
                    <div className="relative">
                        <input
                            type="email"
                            id="modal-email"
                            name="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder=" "
                            className="w-full px-3 pt-5 pb-2 border border-[#E0E0E0] rounded-lg outline-none transition-all duration-200 focus:border-[#212121] focus:ring-0 peer text-sm"
                        />
                        <label
                            htmlFor="modal-email"
                            className="absolute left-3 top-3.5 text-gray-500 text-sm transition-all duration-200 pointer-events-none
                            peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                            peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                        >
                            Email Address
                        </label>
                        {errors.email && <p className="mt-1 text-xs text-red-500">{errors.email}</p>}
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
                            className="w-full px-3 pt-5 pb-2 pr-10 border border-[#E0E0E0] rounded-lg outline-none transition-all duration-200 focus:border-[#212121] focus:ring-0 peer text-sm"
                        />
                        <label
                            htmlFor="modal-password"
                            className="absolute left-3 top-3.5 text-gray-500 text-sm transition-all duration-200 pointer-events-none
                            peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                            peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                        >
                            Password
                        </label>
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#212121] transition-colors"
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
                    <div className="flex items-center justify-between text-sm">
                        <label className="flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={data.remember}
                                onChange={(e) => setData('remember', e.target.checked)}
                                className="h-4 w-4 rounded border-gray-300 text-[#0079C2] focus:ring-[#0079C2]"
                            />
                            <span className="ml-2 text-gray-600">Remember me</span>
                        </label>
                        <a href="/forgot-password" className="text-[#0079C2] hover:underline font-medium">
                            Forgot password?
                        </a>
                    </div>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full rounded-lg bg-[#0079C2] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#005a91] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0079C2] disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                    >
                        {processing ? 'Signing in...' : 'Sign In'}
                    </button>
                </form>

                <div className="mt-6 text-center text-sm text-gray-500">
                    Don't have an account?{' '}
                    <a href="/register" className="font-semibold text-[#0079C2] hover:text-[#005a91]">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    );
}
