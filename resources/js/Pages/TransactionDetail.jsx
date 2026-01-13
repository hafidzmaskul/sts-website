import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function TransactionDetail({ id }) {
    const [transaction, setTransaction] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        if (!id) return;
        const fetchTransaction = async () => {
            try {
                const res = await axios.get(`/web/my-transactions/${id}`);
                // Sesuaikan parsing data sesuai contoh response → res.data.data sudah berisi transaksi
                setTransaction(res.data.data);
            } catch (error) {
                console.error('Failed to load transaction details', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchTransaction();
    }, [id]);

    // Pastikan formatPrice bisa handle string angka
    const formatPrice = (price) => {
        let p = Number(price);
        if (isNaN(p)) return '-';
        return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(p);
    };

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: 'numeric', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    };

    if (isLoading) {
        return (
            <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
                <Header />
                <main className="flex-1 container mx-auto px-6 py-10 text-center">
                    Loading details...
                </main>
                <Footer />
            </div>
        );
    }

    if (!transaction) {
        return (
            <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
                <Header />
                <main className="flex-1 container mx-auto px-6 py-10 text-center">
                    <p className="text-gray-500 mb-4">Transaction not found.</p>
                    <Link href="/my-transactions" className="text-[#0079C2] hover:underline">Back to History</Link>
                </main>
                <Footer />
            </div>
        );
    }

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title={`Transaction #${transaction.invoice_code || transaction.id}`} />
            <Header />

            <main className="flex-1 container mx-auto px-4 md:px-10 lg:px-20 py-10">
                <div className="mb-4">
                    <Link href="/my-transactions" className="text-sm text-gray-500 hover:text-[#0079C2] flex items-center">
                        <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
                        Back to My Transactions
                    </Link>
                </div>

                <div className="bg-white p-8 rounded-lg shadow-sm max-w-4xl mx-auto">
                    <div className="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-6 mb-6">
                        <div>
                            <h1 className="text-2xl font-bold text-gray-800">Invoice</h1>
                            <p className="text-sm text-gray-500 mt-1">#{transaction.invoice_code || transaction.id}</p>
                        </div>
                        <div className="mt-4 md:mt-0 text-right">
                            <div className="text-sm text-gray-500">Date</div>
                            <div className="font-medium">{formatDate(transaction.created_at)}</div>
                            <div className={`inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                                ${transaction.status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}`}>
                                {transaction.status ? transaction.status.toUpperCase() : 'PENDING'}
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 className="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Billed To</h3>
                            <div className="text-sm font-medium text-gray-900">{transaction.shipping_first_name} {transaction.shipping_last_name}</div>
                            <div className="text-sm text-gray-600 mt-1">
                                {transaction.shipping_address}<br />
                                {transaction.shipping_city}, {transaction.shipping_postal_code}<br />
                                {transaction.shipping_country}
                            </div>
                            <div className="text-sm text-gray-600 mt-2">{transaction.contact_email}</div>
                            <div className="text-sm text-gray-600">{transaction.shipping_phone_number}</div>
                        </div>
                        <div>
                            <h3 className="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Details</h3>
                            <div className="flex justify-between py-1 border-b border-gray-100">
                                <span className="text-sm text-gray-600">Payment Method</span>
                                {/*
                                  pada contoh response, payment_method tersedia di root transaksi,
                                  field shipping_payment_method kemungkinan salah,
                                  diganti jadi transaction.payment_method, gunakan '-' jika null
                                */}
                                <span className="text-sm font-medium">{transaction.payment_method ? transaction.payment_method.replace('_', ' ') : '-'}</span>
                            </div>
                            <div className="flex justify-between py-1 border-b border-gray-100 mt-2">
                                <span className="text-sm text-gray-600">Shipping Method</span>
                                <span className="text-sm font-medium">{transaction.shipping_method ? transaction.shipping_method.replace('_', ' ') : '-'}</span>
                            </div>
                        </div>
                    </div>

                    <div className="mb-8">
                        <h3 className="text-lg font-semibold mb-4">Items</h3>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead>
                                    <tr className="border-b text-sm text-gray-500">
                                        <th className="py-2">Item</th>
                                        <th className="py-2 text-center">Quantity</th>
                                        <th className="py-2 text-right">Price</th>
                                        <th className="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {transaction.items && transaction.items.map((item, index) => (
                                        <tr key={index} className="border-b last:border-0 text-sm">
                                            <td className="py-4">
                                                <div className="font-medium text-gray-800">
                                                    {
                                                        // Tampilkan dari product.title jika ada,
                                                        // fallback ke product_name_snapshot jika tidak
                                                        (item.product && item.product.title)
                                                            ? item.product.title
                                                            : (item.product_name_snapshot || 'Product')
                                                    }
                                                </div>
                                            </td>
                                            <td className="py-4 text-center">{item.quantity}</td>
                                            <td className="py-4 text-right">
                                                {
                                                    // Di contoh response, per item:
                                                    // unit_price = string;
                                                    // Frontend yg lama pakai item.price, harusnya unit_price
                                                    formatPrice(item.unit_price)
                                                }
                                            </td>
                                            <td className="py-4 text-right font-medium">
                                                {formatPrice(item.total_price)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="flex justify-end">
                        <div className="w-full md:w-1/2 lg:w-1/3 space-y-3">
                            <div className="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>{formatPrice(transaction.subtotal)}</span>
                            </div>
                            <div className="flex justify-between text-sm text-gray-600">
                                <span>Shipping</span>
                                <span>{formatPrice(transaction.shipping_price)}</span>
                            </div>
                            <div className="flex justify-between text-sm text-gray-600">
                                <span>Tax</span>
                                <span>{formatPrice(transaction.tax_amount)}</span>
                            </div>
                            <div className="flex justify-between text-base font-bold text-gray-900 pt-3 border-t">
                                <span>Total Amount</span>
                                <span>{formatPrice(transaction.total_amount)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}
