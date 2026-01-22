import React, { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import Toast from '../components/Toast';

const formatPrice = (price) => {
    if (!price && price !== 0) return '-';
    const cleanedPrice = price.toString().replace(/[^\d.-]/g, '');
    const parsedPrice = parseFloat(cleanedPrice);
    if (Number.isNaN(parsedPrice)) return '-';
    return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(parsedPrice);
};

export default function QuoteCheckout({ quoteIds, auth }) {
    const isCreditAccount = auth?.roles?.some(role => role.name === 'credit facilities account');
    const [quotes, setQuotes] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [formData, setFormData] = useState({
        contact_email: '',
        shipping_first_name: '',
        shipping_last_name: '',
        shipping_address: '',
        shipping_city: '',
        shipping_postal_code: '',
        shipping_country: '',
        shipping_phone_number: '',
        shipping_payment_method: '',
    });
    const [shippingMethods, setShippingMethods] = useState([]);
    const [paymentMethods, setPaymentMethods] = useState([]);
    const [shippingMethod, setShippingMethod] = useState('');
    const [tax, setTax] = useState({ percentage: 11 });
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });
    const [availableCoupons, setAvailableCoupons] = useState([]);
    const [selectedCoupon, setSelectedCoupon] = useState(null);
    const [showCouponModal, setShowCouponModal] = useState(false);
    const [isFetchingCoupons, setIsFetchingCoupons] = useState(false);

    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    useEffect(() => {
        fetchQuotes();
        fetchSettings();
    }, []);

    const fetchQuotes = async () => {
        setIsLoading(true);
        try {
            const res = await axios.get('/web/quote-builder');
            let allQuotes = res.data.data || res.data || [];

            // Filter if specific IDs requested
            if (quoteIds && quoteIds.length > 0) {
                const idsToKeep = quoteIds.map(id => String(id));
                allQuotes = allQuotes.filter(q => idsToKeep.includes(String(q.id)));
            }

            setQuotes(allQuotes);
        } catch (error) {
            console.error('Failed to load quotes', error);
            setToast({ show: true, message: 'Failed to load quotes', type: 'error' });
        } finally {
            setIsLoading(false);
        }
    };

    const fetchSettings = async () => {
        try {
            const res = await axios.get('/api/settings');
            setShippingMethods(res.data.shipping_methods || []);
            setPaymentMethods(res.data.payment_methods || []);
            if (res.data.tax) setTax(res.data.tax);
        } catch (error) {
            console.error('Failed to fetch settings', error);
        }
    };

    const fetchCoupons = async () => {
        setIsFetchingCoupons(true);
        try {
            const res = await axios.get('/web/coupons');
            setAvailableCoupons(res.data.data || []);
        } catch (error) {
            console.error('Failed to fetch coupons', error);
        } finally {
            setIsFetchingCoupons(false);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleShippingMethodChange = (e) => {
        setShippingMethod(e.target.value);
    };

    const handlePaymentMethodChange = (e) => {
        setFormData(prev => ({
            ...prev,
            shipping_payment_method: e.target.value,
        }));
    };

    // Helper to normalize price for calculation
    const getNumericPrice = (price) => {
        const cleaned = (price || '0').toString().replace(/[^\d.-]/g, '');
        const parsed = parseFloat(cleaned);
        return Number.isNaN(parsed) ? 0 : parsed;
    };

    const getProductPrice = (product) => {
        if (!product) return 0;
        let price = product.calculated_price;
        if (price === undefined || price === null) {
            price = product.special_price || product.base_price;
        }
        return getNumericPrice(price);
    };

    // Calculate subtotal from all products in all displayed quotes
    const subtotal = quotes.reduce((acc, quote) => {
        const quoteTotal = (quote.products || []).reduce((qAcc, p) => {
            const qty = p.pivot?.quantity || 1;
            return qAcc + (getProductPrice(p) * qty);
        }, 0);
        return acc + quoteTotal;
    }, 0);

    const shippingSelected = shippingMethods.find(m => String(m.name) === String(shippingMethod));
    const shippingPrice = shippingSelected ? (shippingSelected.price || 0) : 0;

    let discountAmount = 0;
    if (selectedCoupon) {
        if (selectedCoupon.discount_type === 'percentage') {
            discountAmount = subtotal * (parseFloat(selectedCoupon.discount_value) / 100);
        } else {
            discountAmount = parseFloat(selectedCoupon.discount_value);
        }
    }
    // Ensure discount doesn't exceed subtotal
    if (discountAmount > subtotal) discountAmount = subtotal;

    const taxPercentage = typeof tax.percentage === 'number' ? tax.percentage : 11;
    // Server calculates tax on ((subtotal - discount) + shipping)
    const taxableAmount = Math.max(0, subtotal - discountAmount) + shippingPrice;
    const taxAmount = taxableAmount * (taxPercentage / 100);
    const totalAmount = Math.max(0, subtotal - discountAmount) + shippingPrice + taxAmount;

    const handleCheckout = async () => {
        setIsSubmitting(true);
        try {
            const payload = {
                ...formData,
                shipping_payment_method: isCreditAccount ? 'Credit Limit' : formData.shipping_payment_method,
                shipping_method: shippingMethod,
                shipping_price: shippingPrice,
                tax_amount: taxAmount,
                total_amount: totalAmount,
                coupon_id: selectedCoupon?.id,
                quote_builder_ids: quotes.map(q => q.id)
            };

            const response = await axios.post('/web/transactions', payload);
            setToast({ show: true, message: 'Checkout successful, Waiting For Redirect To Invoice !', type: 'success' });
            setTimeout(() => {
                window.location.href = `/my-transactions/${response.data.data.id}`;
            }, 1500);
        } catch (error) {
            console.error('Checkout failed', error);
            setToast({ show: true, message: 'Checkout failed: ' + (error.response?.data?.message || error.message), type: 'error' });
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="min-h-screen flex flex-col bg-[#fff]">
            <Head title="Checkout Quotes" />
            <Header />
            <div className="border-1"></div>
            <div className="container mx-auto">
                <nav className="text-xs md:text-sm text-gray-500 py-3" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='/quote-builder' className="hover:text-[#0079C2]">Quote Builder</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">Checkout</li>
                    </ol>
                </nav>
            </div>

            <main className="flex-1 w-full py-10 px-4 bg-[#F0F2F3]">
                <div className="container mx-auto">
                    <div className="flex flex-col md:flex-row gap-8">

                        {/* LEFT FORM */}
                        <div className="w-full md:w-8/12 flex-grow">
                            {/* Contact Information */}
                            <div className="rounded-md mb-6 px-6 py-5 shadow bg-white">
                                <h2 className="text-lg font-semibold mb-4">Contact Information</h2>
                                <div className="mb-4">
                                    <label htmlFor="contact_email" className="block text-sm font-medium text-gray-700 mb-1">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        name="contact_email"
                                        className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                        placeholder="example@email.com"
                                        value={formData.contact_email}
                                        onChange={handleChange}
                                    />
                                </div>
                            </div>

                            {/* Shipping Address */}
                            <div className="rounded-md mb-6 px-6 py-5 shadow bg-white">
                                <h2 className="text-lg font-semibold mb-4">Shipping Address</h2>
                                <div className="flex gap-4 mb-4">
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input
                                            type="text"
                                            name="shipping_first_name"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_first_name}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input
                                            type="text"
                                            name="shipping_last_name"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_last_name}
                                            onChange={handleChange}
                                        />
                                    </div>
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                    <input
                                        type="text"
                                        name="shipping_address"
                                        className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                        value={formData.shipping_address}
                                        onChange={handleChange}
                                    />
                                </div>
                                <div className="flex gap-4 mb-4">
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                        <input
                                            type="text"
                                            name="shipping_city"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_city}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                                        <input
                                            type="text"
                                            name="shipping_postal_code"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_postal_code}
                                            onChange={handleChange}
                                        />
                                    </div>
                                </div>
                                <div className="flex gap-4 mb-4">
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                                        <input
                                            type="text"
                                            name="shipping_country"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_country}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    <div className="w-1/2">
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                        <input
                                            type="text"
                                            name="shipping_phone_number"
                                            className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                            value={formData.shipping_phone_number}
                                            onChange={handleChange}
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Shipping Method */}
                            <div className="rounded-md mb-6 px-6 py-5 shadow bg-white">
                                <h2 className="text-lg font-semibold mb-4">Shipping Method</h2>
                                <div className="space-y-4">
                                    {shippingMethods.length === 0 ? (
                                        <div className="text-xs text-gray-400">No shipping methods available</div>
                                    ) : (
                                        shippingMethods.map((method) => (
                                            <label
                                                key={method.id}
                                                className={`flex items-start gap-3 cursor-pointer border rounded px-4 py-3 ${shippingMethod === method.name ? 'border-[#0079C2] bg-blue-50' : ''
                                                    }`}
                                            >
                                                <input
                                                    type="radio"
                                                    name="shipping_method"
                                                    value={method.name}
                                                    className="mt-1"
                                                    checked={shippingMethod === method.name}
                                                    onChange={handleShippingMethodChange}
                                                />
                                                <div className="flex-grow">
                                                    <div className="flex justify-between w-full">
                                                        <div className="font-medium">{method.name}</div>
                                                        <div className="font-medium text-[#0079C2]">{method.price > 0 ? formatPrice(method.price) : "Free"}</div>
                                                    </div>
                                                    {method.description && (
                                                        <div className="text-xs text-gray-500">{method.description}</div>
                                                    )}
                                                </div>
                                            </label>
                                        ))
                                    )}
                                </div>
                            </div>

                            {/* Payment Method */}
                            {!isCreditAccount && (
                                <div className="rounded-md px-6 py-5 shadow bg-white">
                                    <h2 className="text-lg font-semibold mb-4">Payment Method</h2>
                                    <div className="space-y-4">
                                        {paymentMethods.length === 0 ? (
                                            <div className="text-xs text-gray-400">No payment methods available</div>
                                        ) : (
                                            paymentMethods.map((method) => (
                                                <label
                                                    key={method.id}
                                                    className={`flex items-start gap-3 cursor-pointer border rounded px-4 py-3 ${formData.shipping_payment_method === method.name ? 'border-[#0079C2] bg-blue-50' : ''
                                                        }`}
                                                >
                                                    <input
                                                        type="radio"
                                                        name="payment_method"
                                                        value={method.name}
                                                        className="mt-1"
                                                        checked={formData.shipping_payment_method === method.name}
                                                        onChange={handlePaymentMethodChange}
                                                    />
                                                    <div>
                                                        <div className="font-medium">{method.name}</div>
                                                        {method.description && (
                                                            <div className="text-xs text-gray-500">{method.description}</div>
                                                        )}
                                                    </div>
                                                </label>
                                            ))
                                        )}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* RIGHT: Order Summary */}
                        <div className="w-full md:w-4/12  flex-shrink-0">
                            <div className="rounded-md shadow px-5 py-6 md:sticky top-28 bg-white">
                                {isCreditAccount && (
                                    <div className="mb-6 pb-6 border-b border-gray-200">
                                        <h2 className="text-sm font-medium text-gray-500 mb-1">Credit Limit</h2>
                                        <div className="text-2xl font-bold text-[#0079C2]">
                                            {formatPrice(auth?.customer?.company?.requested_credit_limit || 0)}
                                        </div>
                                    </div>
                                )}
                                <h2 className="text-lg font-semibold mb-4">Order Summary</h2>

                                <div className="max-h-96 overflow-auto custom-scrollbar">
                                    {isLoading ? (
                                        <div className="text-center py-4 text-sm text-gray-500">Loading quotes...</div>
                                    ) : quotes.length === 0 ? (
                                        <div className="text-center py-4 text-sm text-gray-500">No quotes selected</div>
                                    ) : (
                                        quotes.map((quote) => (
                                            <div key={quote.id} className="mb-4 border rounded p-3 flex flex-col items-start bg-gray-50">
                                                <div className="font-semibold text-gray-800 text-sm mb-2 pb-1 border-b border-gray-200 w-full">
                                                    {quote.name || `Quote #${quote.id}`}
                                                </div>
                                                {quote.products && quote.products.length > 0 ? (
                                                    quote.products.map((product, pIdx) => (
                                                        <div key={product.id || pIdx} className="w-full flex justify-between items-start gap-2 mb-2 last:mb-0">
                                                            <div className="text-xs text-gray-600 line-clamp-2">{product.title || product.name || 'Unknown Product'}</div>
                                                            <div className="text-xs font-medium text-[#0079C2] whitespace-nowrap">
                                                                {formatPrice(getProductPrice(product))}
                                                            </div>
                                                        </div>
                                                    ))
                                                ) : (
                                                    <div className="text-xs text-gray-400 italic">No products</div>
                                                )}
                                                {quote.products && quote.products.length > 0 && (
                                                    <div className="text-xs font-bold text-gray-700 mt-2 pt-1 border-t w-full text-right">
                                                        Subtotal: {formatPrice(quote.products.reduce((acc, p) => acc + (getProductPrice(p) * (p.pivot?.quantity || 1)), 0))}
                                                    </div>
                                                )}
                                            </div>
                                        ))
                                    )}
                                </div>

                                <div className="mt-4 pt-4 border-t border-gray-100">
                                    {selectedCoupon ? (
                                        <div className="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div className="flex flex-col">
                                                <span className="text-xs text-blue-600 font-semibold uppercase tracking-wider">Coupon Applied</span>
                                                <span className="text-sm font-bold text-gray-800">{selectedCoupon.code}</span>
                                            </div>
                                            <button
                                                onClick={() => setSelectedCoupon(null)}
                                                className="text-gray-400 hover:text-red-500 transition-colors"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                    ) : (
                                        <button
                                            onClick={() => {
                                                setShowCouponModal(true);
                                                fetchCoupons();
                                            }}
                                            className="w-full flex items-center justify-center gap-2 py-2 px-4 border-2 border-dashed border-[#0079C2] text-[#0079C2] rounded-lg hover:bg-blue-50 transition-colors font-semibold text-sm"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M15 5l-1.761 1.761a2 2 0 0 0 0 2.828L15 11.414l1.761-1.761a2 2 0 0 0 0-2.828L15 5z"></path><path d="M13 13.5a2.5 2.5 0 0 1 5 0"></path><path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path></svg>
                                            Add Coupon / Voucher
                                        </button>
                                    )}
                                </div>

                                <div className="border-t pt-4 mt-4 space-y-2">
                                    <div className="flex justify-between text-sm">
                                        <span>Subtotal</span>
                                        <span>{formatPrice(subtotal)}</span>
                                    </div>
                                    {selectedCoupon && (
                                        <div className="flex justify-between text-sm text-green-600 font-medium italic">
                                            <span>Discount ({selectedCoupon.code})</span>
                                            <span>-{formatPrice(discountAmount)}</span>
                                        </div>
                                    )}
                                    <div className="flex justify-between text-sm">
                                        <span>Shipping</span>
                                        <span>{formatPrice(shippingPrice)}</span>
                                    </div>
                                    <div className="flex justify-between text-sm">
                                        <span>Tax ({taxPercentage}%)</span>
                                        <span>{formatPrice(taxAmount)}</span>
                                    </div>
                                    <div className="flex justify-between text-base font-semibold mt-3 pt-3 border-t">
                                        <span>Total</span>
                                        <span>{formatPrice(totalAmount)}</span>
                                    </div>
                                </div>

                                <button
                                    onClick={handleCheckout}
                                    disabled={
                                        isSubmitting ||
                                        quotes.length === 0 ||
                                        !shippingMethod ||
                                        (!isCreditAccount && !formData.shipping_payment_method)
                                    }
                                    className={`w-full mt-6 py-3 px-4 rounded font-medium text-white transition-colors ${isSubmitting || quotes.length === 0 || !shippingMethod || (!isCreditAccount && !formData.shipping_payment_method)
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-[#0079C2] hover:bg-[#00629e]'
                                        }`}
                                >
                                    {isSubmitting ? 'Processing...' : 'Place Order'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main >

            <Footer />
            <Toast show={toast.show} message={toast.message} type={toast.type} onClose={() => setToast((t) => ({ ...t, show: false }))} />

            {/* Coupon Modal */}
            {showCouponModal && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity duration-300">
                    <div
                        className="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-100"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <h3 className="text-xl font-bold text-gray-800">Available Coupons</h3>
                            <button
                                onClick={() => setShowCouponModal(false)}
                                className="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>

                        <div className="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            {isFetchingCoupons ? (
                                <div className="flex flex-col items-center justify-center py-12 space-y-4">
                                    <div className="w-10 h-10 border-4 border-[#0079C2] border-t-transparent rounded-full animate-spin"></div>
                                    <p className="text-gray-500 font-medium">Fetching best deals for you...</p>
                                </div>
                            ) : availableCoupons.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-12 text-center">
                                    <div className="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                    </div>
                                    <p className="text-gray-800 font-bold text-lg">No coupons available</p>
                                    <p className="text-gray-500 text-sm mt-1">Check back later for exclusive vouchers!</p>
                                </div>
                            ) : (
                                <div className="grid gap-4">
                                    {availableCoupons.map((coupon) => (
                                        <div
                                            key={coupon.id}
                                            onClick={() => {
                                                setSelectedCoupon(coupon);
                                                setShowCouponModal(false);
                                            }}
                                            className="group relative bg-white border-2 border-dashed border-gray-200 hover:border-[#0079C2] hover:bg-blue-50/50 rounded-xl p-4 cursor-pointer transition-all duration-300 transform hover:-translate-y-1"
                                        >
                                            <div className="flex justify-between items-start">
                                                <div className="flex-1">
                                                    <div className="flex items-center gap-2 mb-1">
                                                        <span className="px-2 py-0.5 bg-blue-100 text-[#0079C2] text-[10px] font-bold uppercase rounded tracking-wider">
                                                            {coupon.discount_type === 'percentage' ? 'Percentage' : 'Fixed Discount'}
                                                        </span>
                                                    </div>
                                                    <h4 className="text-lg font-black text-gray-800 tracking-tight">{coupon.code}</h4>
                                                    <p className="text-sm text-gray-600 mt-1 font-medium">
                                                        Save {coupon.discount_type === 'percentage' ? `${coupon.discount_value}%` : formatPrice(coupon.discount_value)} on your order
                                                    </p>
                                                </div>
                                                <div className="ml-4 h-full flex items-center">
                                                    <div className="w-8 h-8 rounded-full border-2 border-gray-200 group-hover:border-[#0079C2] group-hover:bg-[#0079C2] flex items-center justify-center transition-all duration-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" className="text-transparent group-hover:text-white"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            {/* Decorative punch holes */}
                                            <div className="absolute top-1/2 -left-3 w-6 h-6 bg-[#F0F2F3] rounded-full border-r-2 border-gray-100 -translate-y-1/2"></div>
                                            <div className="absolute top-1/2 -right-3 w-6 h-6 bg-[#F0F2F3] rounded-full border-l-2 border-gray-100 -translate-y-1/2"></div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>

                        <div className="px-6 py-4 bg-gray-50 text-center text-[10px] text-gray-400 font-medium uppercase tracking-[0.2em] border-t border-gray-100">
                            Safe & Secure Checkout • STS Security Solution
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
