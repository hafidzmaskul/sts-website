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
        // Position the modal fixed at top right, with some margin.
        <div className="fixed inset-0 z-[100] pointer-events-none" role="dialog" aria-modal="true">
            {/* Backdrop */}
            <div
                className="fixed inset-0 bg-[#164778c8] backdrop-blur-sm transition-opacity pointer-events-auto"
                onClick={onClose}
                aria-hidden="true"
            />
            {/* Modal Panel: position at top right with margin */}
            <div
                className="fixed top-6 right-6 w-full max-w-xs md:max-w-md bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-fade-in-up pointer-events-auto"
                style={{ zIndex: 110, minWidth: 350 }}
            >
                {/* Close Button */}
                <button
                    onClick={onClose}
                    className="absolute top-3 right-3 text-xl text-[#1976D2] hover:text-[#009EEA] bg-white focus:outline-none rounded-full p-2 shadow transition z-10"
                    aria-label="Close"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                {/* Login Form */}
                <div className="flex-1 bg-white px-10 py-14 flex flex-col justify-center min-w-[350px]">
                    <div className="text-start mb-7">

                        <p className="font-inter font-light text-[#039BE5]">
                            Login to STS
                        </p>
                    </div>
                    <form onSubmit={submit} className="space-y-6 w-full mx-auto ">
                        {/* Email Input */}
                        <div className="relative">
                            <input
                                type="email"
                                id="modal-email"
                                name="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder=" "
                                style={{ height: '52px' }}
                                autoComplete="username"
                                className="w-full px-3 pt-6 pb-2 border outline-none transition-colors duration-200 focus:border-[#212121] border-[#E0E0E0] peer rounded bg-white text-black placeholder-transparent font-medium"
                                required
                                autoFocus
                                disabled={processing}
                            />
                            <label
                                htmlFor="modal-email"
                                className="absolute left-3 top-3 text-black text-sm transition-all duration-200
                                    peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                                    peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Email Address
                            </label>
                            {errors.email && <p className="mt-1 text-xs text-red-600">{errors.email}</p>}
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
                                style={{ height: '52px' }}
                                autoComplete="current-password"
                                className="w-full px-3 pt-6 pb-2 pr-10 border outline-none transition-colors duration-200 focus:border-[#212121] border-[#E0E0E0] peer rounded bg-white text-black placeholder-transparent font-medium"
                                required
                                disabled={processing}
                            />
                            <label
                                htmlFor="modal-password"
                                className="absolute left-3 top-3 text-black text-sm transition-all duration-200
                                    peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                                    peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Password
                            </label>
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute right-3 top-1/2 -translate-y-1/2 text-black/60 hover:text-black transition-colors"
                                tabIndex={-1}
                                aria-label={showPassword ? "Hide password" : "Show password"}
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
                        <div className="flex items-center justify-between gap-2 text-xs pt-2">
                            <div className="flex items-center gap-2">
                                <input
                                    id="remember-me"
                                    name="remember"
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={e => setData('remember', e.target.checked)}
                                    className="form-checkbox rounded text-black focus:ring-black focus:border-black h-4 w-4 transition"
                                />
                                <label htmlFor="remember-me" className="text-black/80 font-inter font-medium select-none cursor-pointer">
                                    Remember me
                                </label>
                            </div>
                            <a href="/forgot-password" className="text-black font-semibold underline underline-offset-2 hover:text-black/70 transition">
                                Forgot password?
                            </a>
                        </div>
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#039BE5] text-white px-4 py-3 mt-4 text-base font-extrabold font-inter  shadow hover:bg-[#0288c7] active:bg-[#039BE5] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#039BE5] disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                        >
                            {processing ? 'Signing in...' : 'Sign In'}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}
