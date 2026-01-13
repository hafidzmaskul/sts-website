import React, { useEffect, useState } from 'react';
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

export default function Cart() {
    const [cartItems, setCartItems] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [loadingIds, setLoadingIds] = useState([]);
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    useEffect(() => {
        fetchCart();
    }, []);

    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    const fetchCart = async () => {
        setIsLoading(true);
        try {
            const res = await axios.get('/web/cart');
            setCartItems(res.data.data || res.data || []);
            const count = (res.data.data || res.data || []).reduce((s, i) => s + (i.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));
        } catch (error) {
            console.error('Failed to load cart', error);
            setToast({ show: true, message: 'Failed to load cart', type: 'error' });
        } finally {
            setIsLoading(false);
        }
    };

    const increase = async (item) => {
        const prev = cartItems;
        const newItems = cartItems.map(i => i.id === item.id ? { ...i, quantity: (i.quantity || 0) + 1 } : i);
        setCartItems(newItems);
        setLoadingIds(prevIds => Array.from(new Set([...prevIds, item.id])));
        // update header count immediately
        const count = newItems.reduce((s, it) => s + (it.quantity || 0), 0);
        window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));

        try {
            await axios.post('/web/cart', { product_id: item.product_id, quantity: 1 });
            setToast({ show: true, message: 'Quantity increased', type: 'success' });
        } catch (error) {
            console.error('Failed to increase', error);
            setCartItems(prev);
            setToast({ show: true, message: 'Failed to increase quantity', type: 'error' });
            // restore header
            const restoreCount = prev.reduce((s, it) => s + (it.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count: restoreCount } }));
        } finally {
            setLoadingIds(prevIds => prevIds.filter(id => id !== item.id));
        }
    };

    const decrease = async (item) => {
        const prev = cartItems;
        let newItems;
        if ((item.quantity || 0) <= 1) {
            newItems = cartItems.filter(i => i.id !== item.id);
        } else {
            newItems = cartItems.map(i => i.id === item.id ? { ...i, quantity: (i.quantity || 0) - 1 } : i);
        }

        setCartItems(newItems);
        setLoadingIds(prevIds => Array.from(new Set([...prevIds, item.id])));
        const count = newItems.reduce((s, it) => s + (it.quantity || 0), 0);
        window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));

        try {
            await axios.post('/web/cart/decrease', { product_id: item.product_id, quantity: 1 });
            setToast({ show: true, message: 'Quantity decreased', type: 'success' });
        } catch (error) {
            console.error('Failed to decrease', error);
            setCartItems(prev);
            setToast({ show: true, message: 'Failed to decrease quantity', type: 'error' });
            const restoreCount = prev.reduce((s, it) => s + (it.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count: restoreCount } }));
        } finally {
            setLoadingIds(prevIds => prevIds.filter(id => id !== item.id));
        }
    };

    const removeItem = async (item) => {
        const prev = cartItems;
        const newItems = cartItems.filter(i => i.id !== item.id);
        setCartItems(newItems);
        const count = newItems.reduce((s, it) => s + (it.quantity || 0), 0);
        window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));
        setLoadingIds(prevIds => Array.from(new Set([...prevIds, item.id])));

        try {
            await axios.delete(`/web/cart/${item.id}`);
            setToast({ show: true, message: 'Item removed', type: 'success' });
        } catch (error) {
            console.error('Failed to remove', error);
            setCartItems(prev);
            setToast({ show: true, message: 'Failed to remove item', type: 'error' });
            const restoreCount = prev.reduce((s, it) => s + (it.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count: restoreCount } }));
        } finally {
            setLoadingIds(prevIds => prevIds.filter(id => id !== item.id));
        }
    };

    const totalAmount = cartItems.reduce((s, item) => {
        const price = parseFloat((item.product?.base_price || '0').toString().replace(/[^\d.-]/g, ''));
        const final = Number.isNaN(price) ? 0 : price;
        return s + final * (item.quantity || 0);
    }, 0);

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="Cart" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <div className="max-w-4xl w-full mx-auto bg-white p-6 rounded-lg">
                    <h1 className="text-2xl font-semibold mb-4">Your Cart</h1>

                    {isLoading ? (
                        <div className="text-center py-10">Loading...</div>
                    ) : cartItems.length === 0 ? (
                        <div className="text-center py-10">
                            <p className="mb-4">Your cart is empty.</p>
                            <Link href="/products" className="text-[#0079C2] hover:underline">Continue shopping</Link>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {cartItems.map((item) => (
                                <div key={item.id} className="flex items-center gap-4 py-4 border-b">
                                    <img src={item.product?.images?.[0]?.image_path ? (item.product.images[0].image_path.startsWith('/') ? item.product.images[0].image_path : `/storage/${item.product.images[0].image_path}`) : '/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png'} alt={item.product?.title} className="w-20 h-20 object-cover rounded" />
                                    <div className="flex-1">
                                        <div className="font-medium text-[#232323]">{item.product?.title}</div>
                                        <div className="text-sm text-gray-600">{formatPrice(item.product?.base_price)}</div>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <button onClick={() => decrease(item)} className="px-3 py-1 bg-gray-200 rounded">-</button>
                                        <div className="px-4">{item.quantity}</div>
                                        <button onClick={() => increase(item)} className="px-3 py-1 bg-gray-200 rounded">+</button>
                                    </div>
                                    <div className="w-28 text-right">
                                        <div className="font-medium">{formatPrice(item.product?.base_price * item.quantity)}</div>
                                        <button onClick={() => removeItem(item)} className="text-sm text-red-500 mt-2">Remove</button>
                                    </div>
                                </div>
                            ))}

                            <div className="flex justify-between items-center pt-4">
                                <div className="text-lg font-semibold">Total</div>
                                <div className="text-xl font-bold">{formatPrice(totalAmount)}</div>
                            </div>
                            <div className="mt-6 flex justify-end">
                                <Link
                                    href="/checkout"
                                    className="bg-[#0079C2] text-white px-6 py-3 rounded font-medium hover:bg-[#00629e] transition-colors shadow-sm"
                                >
                                    Proceed to Checkout
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </main>

            <Footer />

            <Toast show={toast.show} message={toast.message} type={toast.type} onClose={() => setToast((t) => ({ ...t, show: false }))} />
        </div>
    );
}
