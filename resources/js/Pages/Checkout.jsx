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

export default function Checkout({ auth }) {
    console.log(auth)
    const isCreditAccount = auth?.roles?.some(role => role.name === 'credit facilities account');
    const [cartItems, setCartItems] = useState([]);
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

    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    useEffect(() => {
        fetchCart();
        fetchSettings();
    }, []);

    const fetchCart = async () => {
        setIsLoading(true);
        try {
            const res = await axios.get('/web/cart');
            setCartItems(res.data.data || res.data || []);
        } catch (error) {
            console.error('Failed to load cart', error);
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

    const subtotal = cartItems.reduce((acc, item) => {
        return acc + (getNumericPrice(item.product?.base_price) * (item.quantity || 0));
    }, 0);

    const shippingSelected = shippingMethods.find(m => String(m.name) === String(shippingMethod));
    const shippingPrice = shippingSelected ? (shippingSelected.price || 0) : 0;
    const taxPercentage = typeof tax.percentage === 'number' ? tax.percentage : 11;
    const taxAmount = subtotal * (taxPercentage / 100);
    const totalAmount = subtotal + shippingPrice + taxAmount;

    // PATCH HERE: shipping_method di payload diubah dari objek jadi string name saja
    const handleCheckout = async () => {
        setIsSubmitting(true);
        try {
            const payload = {
                ...formData,
                shipping_payment_method: isCreditAccount ? 'Credit Limit' : formData.shipping_payment_method,
                shipping_method: shippingMethod, // Changed from 'shippingSelected' (object) to just name (string)
                shipping_price: shippingPrice,
                tax_amount: taxAmount,
                total_amount: totalAmount,
                items: cartItems.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: getNumericPrice(item.product?.base_price)
                }))
            };

            const response = await axios.post('/web/transactions', payload);
            setToast({ show: true, message: 'Checkout successful!', type: 'success' });
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
            <Head title="Checkout" />
            <Header />
            <div className="border-1"></div>
            <div className="container mx-auto  ">
                <nav className="text-xs md:text-sm text-gray-500 py-3" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='/products' className="hover:text-[#0079C2]">Products</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">Checkout</li>
                    </ol>
                </nav>
            </div>

            <main className="flex-1 w-full py-10 px-4 bg-[#F0F2F3]">
                <div className="container px-10 mx-auto">
                    <div className=" flex flex-col md:flex-row gap-8">

                        {/* LEFT FORM */}
                        <div className="w-full md:w-8/12  flex-grow">
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

                        {/* RIGHT: 2/10, order summary */}
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
                                        <div className="text-center py-4 text-sm text-gray-500">Loading items...</div>
                                    ) : cartItems.length === 0 ? (
                                        <div className="text-center py-4 text-sm text-gray-500">No items in cart</div>
                                    ) : (
                                        cartItems.map((item) => (
                                            <div key={item.id} className="mb-4 border rounded p-3 flex flex-col items-start bg-gray-50">
                                                <div className="font-medium mb-1 text-sm line-clamp-2">{item.product?.title}</div>
                                                <div className="text-xs text-gray-500">Qty: {item.quantity}</div>
                                                <div className="text-sm font-medium text-[#0079C2] mt-2">
                                                    {formatPrice(getNumericPrice(item.product?.base_price) * item.quantity)}
                                                </div>
                                            </div>
                                        ))
                                    )}
                                </div>

                                <div className="border-t pt-4 mt-4 space-y-2">
                                    <div className="flex justify-between text-sm">
                                        <span>Subtotal</span>
                                        <span>{formatPrice(subtotal)}</span>
                                    </div>
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
                                        cartItems.length === 0 ||
                                        !shippingMethod ||
                                        (!isCreditAccount && !formData.shipping_payment_method)
                                    }
                                    className={`w-full mt-6 py-3 px-4 rounded font-medium text-white transition-colors ${isSubmitting || cartItems.length === 0 || !shippingMethod || (!isCreditAccount && !formData.shipping_payment_method)
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
            </main>

            <Footer />
            <Toast show={toast.show} message={toast.message} type={toast.type} onClose={() => setToast((t) => ({ ...t, show: false }))} />
        </div>
    );
}


// {
//     "id": 61,
//     "parent_id": null,
//     "name": "Labore et fugiat la Rerum veritatis ex l",
//     "email": "bekajebuzu@mailinator.com",
//     "email_verified_at": null,
//     "two_factor_confirmed_at": null,
//     "created_at": "2026-01-19T03:07:46.000000Z",
//     "updated_at": "2026-01-19T03:08:07.000000Z",
//     "pricing_formula_id": null,
//     "customer": {
//         "id": 61,
//         "user_id": 61,
//         "company_id": 4,
//         "first_name": "Labore et fugiat la",
//         "last_name": "Rerum veritatis ex l",
//         "email": "bekajebuzu@mailinator.com",
//         "role_applied": "credit facilities account",
//         "account_number": "PENDING-696707919b2ff",
//         "job_title": "Manager",
//         "phone": null,
//         "address": null,
//         "city": null,
//         "postal_code": null,
//         "country": null,
//         "status": "active",
//         "status_review": "approved",
//         "review_note": null,
//         "created_at": "2026-01-14T03:03:45.000000Z",
//         "updated_at": "2026-01-19T03:07:46.000000Z",
//         "company": {
//             "id": 4,
//             "name": "Amet aut et fugiat",
//             "registration_number": "Doloremque sunt et",
//             "trading_name": "Officiis fugiat anim",
//             "vat_number": "Velit eaque nobis do",
//             "address": "Ut nulla minima labo",
//             "trading_address": "Est in quia et nihil",
//             "phone": "Cupidatat tempora ra",
//             "fax": "Voluptatum vel nihil",
//             "activities_description": "Voluptatem sit iure",
//             "purchasing_contact_name": "Quia impedit unde d",
//             "purchasing_contact_phone": "Et fugiat non rerum",
//             "purchasing_contact_email": "xydevek@mailinator.com",
//             "accounts_contact_name": "Eligendi distinctio",
//             "accounts_contact_phone": "Ullamco ipsam ut qui",
//             "accounts_contact_email": "fame@mailinator.com",
//             "bank_name": "Dignissimos impedit",
//             "bank_address": "Repudiandae est est",
//             "bank_sort_code": "Commodo esse in dol",
//             "bank_account_number": "Et officia animi nu",
//             "trade_ref_1_details": "Hic officia accusant",
//             "trade_ref_1_phone": "Cupiditate voluptate",
//             "trade_ref_1_email": "novevaliwu@mailinator.com",
//             "trade_ref_2_details": "Autem maxime tempor",
//             "trade_ref_2_phone": "In laboris voluptatu",
//             "trade_ref_2_email": "kujiwi@mailinator.com",
//             "requested_credit_limit": "42.00",
//             "created_at": "2026-01-14T03:03:45.000000Z",
//             "updated_at": "2026-01-14T03:03:45.000000Z"
//         }
//     },
//     "roles": [
//         {
//             "id": 6,
//             "name": "credit facilities account",
//             "guard_name": "web",
//             "created_at": "2026-01-05T14:56:39.000000Z",
//             "updated_at": "2026-01-05T14:56:39.000000Z",
//             "pivot": {
//                 "model_type": "App\\Models\\User",
//                 "model_id": 61,
//                 "role_id": 6
//             }
//         }
//     ]
// }
