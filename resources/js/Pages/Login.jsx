import { Head, useForm } from '@inertiajs/react'
import React, { useState } from 'react'
import Header from '../landing/Header'

export default function Login() {
    const [showPassword, setShowPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('login', {
            onFinish: () => reset('password'),
        });
    };
    return (
        <div
            style={{
                backgroundImage: 'url("/assets/bg-login.png")',
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                minHeight: '100vh',
                width: '100vw',
                position: 'relative',
            }}
        >
            <div
                style={{
                    background: '#1976D2E5',
                    position: 'absolute',
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                    zIndex: 1,
                }}
            ></div>
            <div style={{ position: 'relative', zIndex: 2, minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
                <Head title="Login" />
                <Header />
                 <div className="flex items-center justify-center flex-1" >
                     <form onSubmit={submit} className="p-10 max-w-2xl bg-white mx-auto">
                        <h1 className='font-inter text-center font-bold text-2xl mb-10'>Log In to STS</h1>
                        <div className="w-full">
                        <div className="relative">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder=" "
                                style={{ width: '400px' }}
                                className="w-full px-3 pt-6 pb-2 border outline-none transition-color duration-200
                             focus:border-[#212121]
                             border-[#E0E0E0]
                             peer"
                            />
                            <label
                                htmlFor="email"
                                className="absolute left-3 top-3 text-gray-500 text-sm transition-all duration-200
                             peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                             peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Email Address
                            </label>

                        </div>
                        </div>
                        <div className="w-full">

                        </div>
                        <div className="w-full">

                        <div className="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                placeholder=" "
                                style={{ width: '400px' }}
                                className="w-full px-3 pt-6 pb-2 pr-12 border outline-none transition-color duration-200
                             focus:border-[#212121]
                             border-[#E0E0E0]
                             peer"
                            />
                            <label
                                htmlFor="password"
                                className="absolute left-3 top-3 text-gray-500 text-sm transition-all duration-200
                             peer-focus:top-1 peer-focus:text-xs peer-focus:text-[#212121]
                             peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:text-xs"
                            >
                                Password
                            </label>
                            <button
                                type="button"
                                onClick={() => {
                                    const input = document.getElementById('password');
                                    const icon = document.getElementById('toggle-icon');
                                    if (input.type === 'password') {
                                        input.type = 'text';
                                        icon.innerHTML = `<path fill="currentColor" d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5"></path>`;
                                    } else {
                                        input.type = 'password';
                                        icon.innerHTML = `<path fill="currentColor" d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7"></path>`;
                                    }
                                }}
                                className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#212121] transition-colors"
                            >
                                <svg id="toggle-icon" className="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7"></path>
                                </svg>
                            </button>
                        </div>
                        </div>
                        <div className="flex justify-between items-center mt-4 mb-6">
                            <label className="flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="form-checkbox accent-[#1976D2] mr-2"
                                    id="remember"
                                    name="remember"
                                />
                                <span className="text-sm text-gray-700 select-none">Remember me</span>
                            </label>
                            <a href="/forgot-password" className="text-sm text-[#1976D2] hover:underline">
                                Forgot password?
                            </a>
                        </div>
                         <div className="relative mt-10">
                            <button
                                type="submit"
                                disabled={processing}
                                className='bg-[#039BE5] text-white w-full py-3 disabled:opacity-50'
                            >
                                {processing ? 'Signing in...' : 'Sign in'}
                            </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     )
}

