import React, { useState } from 'react';

export default function Footer() {
    const [name, setName] = useState('');
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState(null);
    const [error, setError] = useState(null);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setMessage(null);
        setError(null);

        // Dummy submit - Replace with your API call for newsletter if needed!
        setTimeout(() => {
            setMessage("Thank you for subscribing!");
            setName('');
            setLoading(false);
        }, 1000);
    };

    // Social icons path example - replace with your actual assets if needed
    // Sosial media icon manual
    return (
        <footer className="bg-[#fff] border-t font-inter pt-8 pb-4 px-4">
            {/* Baris 1 */}
            <div className="container md:px-20 px-10 mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-gray-200">
                {/* Kiri: Text dan Sosmed */}
                <div>
                    <div className="mb-6">
                        <p className="text-base text-[#272343] font-light mb-1">Vivamus tristique odio sit amet velit semper, eu posuere turpis interdum.</p>
                        <p className="text-base text-[#272343] font-light mb-1">Cras egestas purus</p>
                    </div>
                    <div className="flex items-center mt-6">
                        <a
                            href="#"
                            className="transition rounded-full group"
                            aria-label="Facebook"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span className="flex items-center justify-center h-11 w-11 rounded-full group-hover:border group-hover:border-[#007580]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width={24}
                                    height={24}
                                    viewBox="0 0 24 24"
                                    className="  group-hover:text-[#007580] transition-colors"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95"
                                    />
                                </svg>
                            </span>
                        </a>
                        <a
                            href="#"
                            className="transition rounded-full group"
                            aria-label="Twitter"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span className="flex items-center justify-center h-11 w-11 rounded-full group-hover:border group-hover:border-[#007580]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width={24}
                                    height={24}
                                    viewBox="0 0 24 24"
                                    className="  group-hover:text-[#007580] transition-colors"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M22.46 6c-.77.35-1.6.58-2.46.69c.88-.53 1.56-1.37 1.88-2.38c-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29c0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15c0 1.49.75 2.81 1.91 3.56c-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.2 4.2 0 0 1-1.93.07a4.28 4.28 0 0 0 4 2.98a8.52 8.52 0 0 1-5.33 1.84q-.51 0-1.02-.06C3.44 20.29 5.7 21 8.12 21C16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56c.84-.6 1.56-1.36 2.14-2.23"
                                    />
                                </svg>
                            </span>
                        </a>
                        <a
                            href="#"
                            className="transition rounded-full group"
                            aria-label="Instagram"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span className="flex items-center justify-center h-11 w-11 rounded-full group-hover:border group-hover:border-[#007580]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width={24}
                                    height={24}
                                    viewBox="0 0 24 24"
                                    className="  group-hover:text-[#007580] transition-colors"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4zm9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8A1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5a5 5 0 0 1-5 5a5 5 0 0 1-5-5a5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3"
                                    />
                                </svg>
                            </span>
                        </a>
                        <a
                            href="#"
                            className="transition rounded-full group"
                            aria-label="Pinterest"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span className="flex items-center justify-center h-11 w-11 rounded-full group-hover:border group-hover:border-[#007580]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width={24}
                                    height={24}
                                    viewBox="0 0 24 24"
                                    className="  group-hover:text-[#007580] transition-colors"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M9.04 21.54c.96.29 1.93.46 2.96.46a10 10 0 0 0 10-10A10 10 0 0 0 12 2A10 10 0 0 0 2 12c0 4.25 2.67 7.9 6.44 9.34c-.09-.78-.18-2.07 0-2.96l1.15-4.94s-.29-.58-.29-1.5c0-1.38.86-2.41 1.84-2.41c.86 0 1.26.63 1.26 1.44c0 .86-.57 2.09-.86 3.27c-.17.98.52 1.84 1.52 1.84c1.78 0 3.16-1.9 3.16-4.58c0-2.4-1.72-4.04-4.19-4.04c-2.82 0-4.48 2.1-4.48 4.31c0 .86.28 1.73.74 2.3c.09.06.09.14.06.29l-.29 1.09c0 .17-.11.23-.28.11c-1.28-.56-2.02-2.38-2.02-3.85c0-3.16 2.24-6.03 6.56-6.03c3.44 0 6.12 2.47 6.12 5.75c0 3.44-2.13 6.2-5.18 6.2c-.97 0-1.92-.52-2.26-1.13l-.67 2.37c-.23.86-.86 2.01-1.29 2.7z"
                                    />
                                </svg>
                            </span>
                        </a>
                        <a
                            href="#"
                            className="transition rounded-full group"
                            aria-label="Youtube"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span className="flex items-center justify-center h-11 w-11 rounded-full group-hover:border group-hover:border-[#007580]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width={24}
                                    height={24}
                                    viewBox="0 0 24 24"
                                    className="  group-hover:text-[#007580] transition-colors"
                                >
                                    <path
                                        fill="currentColor"
                                        d="m10 15l5.19-3L10 9zm11.56-7.83c.13.47.22 1.1.28 1.9c.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83c-.25.9-.83 1.48-1.73 1.73c-.47.13-1.33.22-2.65.28c-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44c-.9-.25-1.48-.83-1.73-1.73c-.13-.47-.22-1.1-.28-1.9c-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83c.25-.9.83-1.48 1.73-1.73c.47-.13 1.33-.22 2.65-.28c1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44c.9.25 1.48.83 1.73 1.73"
                                    />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                {/* Category */}
                <div>
                    <h3 className="text-lg font-medium mb-4 text-[#9A9CAA]">Category</h3>
                    <ul className="space-y-2 text-base font-normal">
                        <li><a className="hover:underline cursor-pointer">Instruction</a></li>
                        <li><a className="hover:underline cursor-pointer">Access Control</a></li>
                        <li><a className="hover:underline cursor-pointer">Video Survilance</a></li>
                        <li><a className="hover:underline cursor-pointer">Communication</a></li>
                        <li><a className="hover:underline cursor-pointer">Pro Av</a></li>
                        <li><a className="hover:underline cursor-pointer">Data Comm and Network</a></li>
                    </ul>
                </div>
                {/* Support */}
                <div>
                    <h3 className="text-lg font-medium mb-4 text-[#9A9CAA]">Support</h3>
                    <ul className="space-y-2 text-base font-normal">
                        <li><a className="hover:underline cursor-pointer">Help & Support</a></li>
                        <li><a className="hover:underline cursor-pointer">Tearms & Conditions</a></li>
                        <li><a className="hover:underline cursor-pointer">Privacy Policy</a></li>
                        <li><a className="hover:underline cursor-pointer">Help</a></li>
                    </ul>
                </div>
                {/* Newsletter */}
                <div>
                    <h3 className="text-lg font-medium mb-4 text-[#9A9CAA]">Newsletter</h3>
                    <form className="space-y-3" onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-2">
                            <input
                                type="text"
                                placeholder="Your Name"
                                className="w-full px-3 py-2 border rounded-lg border-gray-300 focus:outline-none text-base"
                                value={name}
                                onChange={e => setName(e.target.value)}
                                required
                                disabled={loading}
                            />
                            <button
                                type="submit"
                                className="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition"
                                disabled={loading}
                            >
                                {loading ? "Subscribing..." : "Subscribe"}
                            </button>
                        </div>
                        {error && <div className="text-red-500 text-sm">{error}</div>}
                        {message && <div className="text-green-600 text-sm">{message}</div>}
                    </form>
                    <p className="mt-4 text-sm text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tincidunt erat enim.</p>
                </div>
            </div>
            {/* Baris 2 */}
            <div className="max-w-7xl mx-auto flex justify-center items-center gap-8 mt-6">
                <img src="/assets/payment-logo.png" alt="Paypal" className="h-8 w-auto filter grayscale" />
            </div>
        </footer>
    );
}
