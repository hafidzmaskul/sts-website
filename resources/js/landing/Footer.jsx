import React, { useState } from 'react';

function Alert({ type, children, onClose }) {
    // type: "success" | "error"
    // You can adjust colors as suits your branding/taste
    const baseStyles = "flex items-center px-4 py-3 rounded shadow-md transition-opacity duration-200";
    const typeStyles =
        type === "success"
            ? "bg-green-100 border border-green-300 text-green-800"
            : "bg-red-100 border border-red-300 text-red-800";

    return (
        <div className={`${baseStyles} ${typeStyles} relative w-full max-w-md ml-auto`}>
            <svg
                className="w-5 h-5 mr-2 flex-shrink-0"
                fill="none"
                stroke={type === "success" ? "#22c55e" : "#ef4444"}
                viewBox="0 0 24 24"
            >
                {type === "success"
                    ? (
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                            d="M5 13l4 4L19 7" />
                    ) : (
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                            d="M6 18L18 6M6 6l12 12" />
                    )
                }
            </svg>
            <span className="font-medium flex-1">{children}</span>
            <button
                type="button"
                className="ml-4 focus:outline-none text-lg"
                aria-label="Dismiss"
                onClick={onClose}
            >
                <svg className="w-4 h-4 text-gray-500 hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    );
}

export default function Footer() {
    const year = new Date().getFullYear();
    const [email, setEmail] = useState('');
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState(null);
    const [error, setError] = useState(null);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setMessage(null);
        setError(null);

        try {
            const resp = await fetch('/api/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email }),
            });

            if (resp.ok) {
                setMessage("Successfully subscribed to the newsletter!");
                setEmail('');
                // Removed old window.alert in favor of an inline alert
            } else {
                const data = await resp.json();
                if (data && data.errors && data.errors.email) {
                    setError(data.errors.email[0]);
                } else if (data && data.message) {
                    setError(data.message);
                } else {
                    setError("Failed to subscribe. Please try again.");
                }
            }
        } catch (err) {
            setError("Failed to subscribe. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <footer
            className=" pt-5 "
            style={{
                backgroundImage: "url('/assets/bg-footer.svg')",
                backgroundRepeat: "no-repeat",
                backgroundSize: "cover",
                backgroundPosition: "top"
            }}
        >
            <div className="mx-auto container mt-30 px-5 sm:px-10 md:px-20 py-10">
                <div className="grid grid-cols-1 md:grid-cols-2 grid-rows-4 gap-8 md:gap-10">
                    {/* Baris 1 */}
                    <div className="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <img src="/assets/logo.svg" alt="Logo" className="h-10 mb-2 sm:mb-0" />
                        <div className="flex items-start sm:items-center space-x-2">
                            <img src="/assets/address.svg" alt="Address" className="h-6" />
                            <span>
                                <p className="font-montserrat text-sm font-semibold lin">16 Society Road</p>
                                <p className='font-inter font-regular text-sm'>South Queensferry, Edinburgh EH30 9RX</p>
                            </span>
                        </div>
                    </div>
                    <div className="flex flex-col justify-center">
                        <h6 className='font-roboto font-bold text-lg sm:text-xl md:text-2xl'>Newsletter</h6>
                        <p className='font-roboto font-regular text-sm md:text-base mt-1'>Lorem ipsum dolor sit amet consectetur. Nunc laoreet</p>
                    </div>
                    {/* Baris 2 */}
                    <div className="flex items-center">
                        <p className='font-roboto font-regular text-sm md:text-base'>
                            Absolutely&nbsp;Human&nbsp;Resources Limited support clients in their Human Resource and Employment Law needs. We ensure that our clients are regularly updated with the current, frequent and ongoing changes to UK Employment Law.
                        </p>
                    </div>
                    <div className="flex flex-col w-full  ">
                        {/* Alerts - appear above form */}
                        {error && (
                            <div className="mb-2">
                                <Alert type="error" onClose={() => setError(null)}>
                                    {error}
                                </Alert>
                            </div>
                        )}
                        {message && (
                            <div className="mb-2">
                                <Alert type="success" onClose={() => setMessage(null)}>
                                    {message}
                                </Alert>
                            </div>
                        )}
                        <form className="flex" onSubmit={handleSubmit}>
                            <input
                                type="email"
                                className="flex-grow px-4 py-2 rounded-l border bg-white border-gray-300 focus:outline-none text-sm"
                                placeholder="Enter your email"
                                value={email}
                                onChange={e => setEmail(e.target.value)}
                                required
                                disabled={loading}
                            />
                            <button
                                type="submit"
                                className="px-4 sm:px-6 py-2 text-white font-semibold rounded-r transition-colors"
                                style={{ background: "#302F2F" }}
                                disabled={loading}
                            >
                                {loading ? "Loading..." : "Subscribe"}
                            </button>
                        </form>
                    </div>
                    {/* Baris 3 */}
                    <div className="col-span-1 md:col-span-2">
                        <hr style={{ borderColor: "#4D558D" }} />

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div className="flex flex-wrap items-center space-x-4 space-y-2 md:space-y-0 md:space-x-4">
                                <a href="#" className="font-roboto text-sm hover:underline">Service</a>
                                <span className="hidden md:inline h-4 w-px bg-[#9095B8]"></span>
                                <a href="#" className="font-roboto text-sm hover:underline">About Us</a>
                                <span className="hidden md:inline h-4 w-px bg-[#9095B8]"></span>
                                <a href="#" className="font-roboto text-sm hover:underline">News</a>
                                <span className="hidden md:inline h-4 w-px bg-[#9095B8]"></span>
                                <a href="#" className="font-roboto text-sm hover:underline">Contact Us</a>
                            </div>
                            <div className="flex justify-start md:justify-start  md:mt-0">
                                <div className='grid grid-cols-1 sm:grid-cols-2 gap-4'>
                                    <div className="flex items-center space-x-2 justify-start md:justify-end">
                                        <img src="/assets/phone.svg" alt="Phone" className="h-6" />
                                        <span>
                                            <p className="font-montserrat text-sm font-semibold lin">0131 331 2735 or 07970 797 544</p>
                                            <p className='font-inter font-normal text-sm'>info@absolutelyhumanresources.co.uk</p>
                                        </span>
                                    </div>
                                    <div className="flex items-center space-x-2 justify-start md:justify-end">
                                        <img src="/assets/clock.svg" alt="Address" className="h-6" />
                                        <span>
                                            <p className="font-montserrat text-sm font-semibold lin">24/7</p>
                                            <p className='font-inter font-normal text-sm'>Monday to Sunday</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/* Baris 4 - tambahan */}
                    <div className="col-span-1 md:col-span-2">
                        <hr className='-mt-5' style={{ borderColor: "#4D558D" }} />
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 items-center">
                            <div className="flex items-center space-x-4">
                                <p className='font-roboto font-normal text-sm'>© Copyright 2025 Absolutely HR</p>
                            </div>
                            <div className="flex justify-start md:justify-end mt-4 md:mt-0">
                                <div className="flex items-center space-x-4 sm:space-x-6">
                                    <p className='font-roboto text-base font-medium'>Follow Us</p>
                                    <a href="#" className="hover:opacity-80 transition">
                                        <img src="/assets/fb.svg" alt="Facebook" className="h-6 w-6" />
                                    </a>
                                    <a href="#" className="hover:opacity-80 transition">
                                        <img src="/assets/ig.svg" alt="Instagram" className="h-6 w-6" />
                                    </a>
                                    <a href="#" className="hover:opacity-80 transition">
                                        <img src="/assets/tiktok.svg" alt="TikTok" className="h-6 w-6" />
                                    </a>
                                    <a href="#" className="hover:opacity-80 transition">
                                        <img src="/assets/x.svg" alt="X (Twitter)" className="h-6 w-6" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
}
