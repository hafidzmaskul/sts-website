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
    const socialIcons = [
        { src: "/assets/fb.svg", alt: "Facebook", href: "#" },
        { src: "/assets/twitter.svg", alt: "Twitter", href: "#" },
        { src: "/assets/ig.svg", alt: "Instagram", href: "#" },
        { src: "/assets/pinterest.svg", alt: "Pinterest", href: "#" },
        { src: "/assets/youtube.svg", alt: "Youtube", href: "#" },
    ];

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
                    <div className="flex items-center space-x-4 mt-6">
                        {socialIcons.map(icon =>
                            <a href={icon.href} key={icon.alt} className="hover:opacity-80 transition">
                                <img src={icon.src} alt={icon.alt} className="h-7 w-7" />
                            </a>
                        )}
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
                                className="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:bg-[#484848] transition"
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
                <img src="/assets/paypal.svg" alt="Paypal" className="h-8 w-auto" />
                <img src="/assets/amex.svg" alt="American Express" className="h-8 w-auto" />
                <img src="/assets/visa.svg" alt="Visa" className="h-8 w-auto" />
            </div>
        </footer>
    );
}
