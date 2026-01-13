import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function Checkout() {
    return (
        <div className="min-h-screen flex flex-col bg-[#fff]">
            <Head title="Invoice" />
            <Header />
            {/* Breadcrumb */}
            <div className="container">

            <nav className="text-xs md:text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
                <ol className="flex flex-wrap items-center gap-1">
                    <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                    <li className="mx-1 text-gray-400">/</li>
                    <li><Link href='/products' className="hover:text-[#0079C2]">Products</Link></li>
                    <li className="mx-1 text-gray-400">/</li>
                    <li className="text-gray-700">Invoice</li>
                </ol>
            </nav>
            </div>
            <main className="flex-1 w-full py-10 px-4 bg-[#F0F2F3]">
                <div className="container mx-auto">
                {(() => {
                    // Local component for discount code
                    function DiscountCodeBox() {
                        const [show, setShow] = React.useState(false);
                        const [code, setCode] = React.useState('');
                        return (
                            <div className="mb-4">
                                <button
                                    type="button"
                                    className="text-[#0079C2] text-sm font-semibold hover:underline"
                                    onClick={() => setShow(s => !s)}
                                >
                                    + Discount Code
                                </button>
                                {show && (
                                    <div className="mt-2 flex">
                                        <input
                                            type="text"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000] rounded-l"
                                            placeholder="Enter discount code"
                                            value={code}
                                            onChange={e => setCode(e.target.value)}
                                        />
                                        <button
                                            className="bg-[#0079C2] text-white px-3 py-1 rounded-r ml-2 text-xs"
                                            disabled
                                        >
                                            Apply
                                        </button>
                                    </div>
                                )}
                            </div>
                        )
                    }

                    return (
                        <div className="flex flex-col md:flex-row gap-8">
                            {/* LEFT: 8/10 */}
                            <div className="w-full md:w-8/10 max-w-3xl flex-grow">
                                {/* Contact Information */}
                                <div className="rounded-md mb-6 px-6 py-5 shadow" style={{background:'#fff'}}>
                                    <h2 className="text-lg font-semibold mb-4">Contact Information</h2>
                                    <div className="mb-4">
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">
                                            Email Address
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            placeholder="example@email.com"
                                            value="demo@demo.com"
                                            readOnly
                                        />
                                    </div>
                                </div>
                                {/* Shipping Address */}
                                <div className="rounded-md mb-6 px-6 py-5 shadow" style={{background:'#fff'}}>
                                    <h2 className="text-lg font-semibold mb-4">Shipping Address</h2>
                                    <div className="flex gap-4 mb-4">
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                First Name *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="First Name"
                                                value="John"
                                                readOnly
                                            />
                                        </div>
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                Last Name *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="Last Name"
                                                value="Doe"
                                                readOnly
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Address *
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            placeholder="Street address"
                                            value="123 Main St"
                                            readOnly
                                        />
                                    </div>
                                    <div className="flex gap-4 mb-4">
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                City *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="City"
                                                value="London"
                                                readOnly
                                            />
                                        </div>
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                Postal Code *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="Postal Code"
                                                value="W1A 1AA"
                                                readOnly
                                            />
                                        </div>
                                    </div>
                                    <div className="flex gap-4 mb-4">
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                Country *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="Country"
                                                value="United Kingdom"
                                                readOnly
                                            />
                                        </div>
                                        <div className="w-1/2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                Phone Number *
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                placeholder="Phone Number"
                                                value="+44 1234 567890"
                                                readOnly
                                            />
                                        </div>
                                    </div>
                                </div>
                                {/* Shipping Method */}
                                <div className="rounded-md mb-6 px-6 py-5 shadow" style={{background:'#fff'}}>
                                    <h2 className="text-lg font-semibold mb-4">Shipping Method</h2>
                                    <div className="space-y-4">
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="shipping" className="mt-1" defaultChecked readOnly />
                                            <div className="flex-grow">
                                                <div className="flex justify-between w-full">
                                                    <div className="font-medium">Standard Shipping</div>
                                                    <div className="font-medium text-green-700">Free</div>
                                                </div>
                                                <div className="text-xs text-gray-500">Delivery in 5-7 business days</div>
                                            </div>
                                        </label>
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="shipping" className="mt-1" readOnly />
                                            <div className="flex-grow">
                                                <div className="flex justify-between w-full">
                                                    <div className="font-medium">Express Shipping</div>
                                                    <div className="font-medium text-[#0079C2]">£9.99</div>
                                                </div>
                                                <div className="text-xs text-gray-500">Delivery in 2-3 business days</div>
                                            </div>
                                        </label>
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="shipping" className="mt-1" readOnly />
                                            <div className="flex-grow">
                                                <div className="flex justify-between w-full">
                                                    <div className="font-medium">Overnight Shipping</div>
                                                    <div className="font-medium text-[#0079C2]">£19.99</div>
                                                </div>
                                                <div className="text-xs text-gray-500">Next day delivery</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                {/* Payment Method */}
                                <div className="rounded-md px-6 py-5 shadow" style={{background:'#fff'}}>
                                    <h2 className="text-lg font-semibold mb-4">Payment Method</h2>
                                    <div className="space-y-4">
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="payment" className="mt-1" defaultChecked readOnly />
                                            <div>
                                                <div className="font-medium">Credit / Debit Card</div>
                                            </div>
                                        </label>
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="payment" className="mt-1" readOnly />
                                            <div>
                                                <div className="font-medium">PayPal</div>
                                                <div className="text-xs text-gray-500">Pay securely with your credit or debit card</div>
                                            </div>
                                        </label>
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="payment" className="mt-1" readOnly />
                                            <div>
                                                <div className="font-medium">PayPal</div>
                                                <div className="text-xs text-gray-500">You will be redirected to PayPal to complete payment</div>
                                            </div>
                                        </label>
                                        <label className="flex items-start gap-3 cursor-pointer border rounded px-4 py-3">
                                            <input type="radio" name="payment" className="mt-1" readOnly />
                                            <div>
                                                <div className="font-medium">Bank Transfer</div>
                                                <div className="text-xs text-gray-500">Transfer funds directly from your bank account</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {/* RIGHT: 2/10, order summary */}
                            <div className="w-full md:w-2/10 max-w-xs flex-shrink-0">
                                <div className="rounded-md shadow px-5 py-6 md:sticky top-28" style={{background:'#fff'}}>
                                    <h2 className="text-lg font-semibold mb-4">Order Summary</h2>
                                    {/* Product - dummy */}
                                    <div className="mb-4 border rounded p-3 flex flex-col items-start bg-gray-50">
                                        <div className="font-medium mb-1">Sample Product</div>
                                        <div className="text-xs text-gray-500">Variant 1, Color Black</div>
                                        <div className="text-sm font-medium text-[#0079C2] mt-2">£99.99</div>
                                    </div>
                                    <DiscountCodeBox />
                                    <div className="border-t pt-4 mt-4 space-y-2">
                                        <div className="flex justify-between text-sm">
                                            <span>Subtotal</span>
                                            <span>£99.99</span>
                                        </div>
                                        <div className="flex justify-between text-sm">
                                            <span>Tax</span>
                                            <span>£7.00</span>
                                        </div>
                                        <div className="flex justify-between text-base font-semibold mt-3">
                                            <span>Total</span>
                                            <span>£106.99</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    );
                })()}
                </div>
            </main>

            <Footer />
        </div>
    );
}
