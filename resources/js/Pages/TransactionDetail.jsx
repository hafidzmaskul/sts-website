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
                setTransaction(res.data.data);
            } catch (error) {
                console.error('Failed to load transaction details', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchTransaction();
    }, [id]);

    const formatPrice = (price) => {
        let p = Number(price);
        if (isNaN(p)) return '-';
        return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(p);
    };

    const formatDate = (dateString, format = 'long') => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (format === 'short') {
            return date.toLocaleDateString('en-GB', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        }
        return date.toLocaleDateString('en-GB', {
            day: 'numeric', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    };

    if (isLoading) {
        return (
            <div className="min-h-screen flex flex-col bg-[#fff]">
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
            <div className="min-h-screen flex flex-col bg-[#fff]">
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
        <div className="min-h-screen flex flex-col bg-[#fff]">
            <Head title={`Invoice #${transaction.invoice_code || transaction.id}`} />
            <Header />

            <main className="flex-1 w-full py-10 px-4 bg-white">
                <div className="container mx-auto">
                    {/* Breadcrumb */}
                    <nav className="text-xs md:text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
                        <ol className="flex flex-wrap items-center gap-1">
                            <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li><Link href='/my-transactions' className="hover:text-[#0079C2]">Transactions</Link></li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li className="text-gray-700">Invoice #{transaction.invoice_code}</li>
                        </ol>
                    </nav>

                    {/* Invoice Card Container */}
                    <div className="flex justify-center">
                        <div
                            className="relative py-10 w-full max-w-4xl flex flex-col"
                            style={{
                                backgroundImage: "url('/assets/invoice-bg.png')",
                                backgroundSize: "cover",
                                backgroundPosition: "center center",
                            }}
                        >
                            {/* Content */}
                            <div className="relative z-10 p-10 flex flex-col h-full text-[#232323] font-sans">

                                {/* Header */}
                                <div className="flex justify-center items-center mb-12">
                                    <div className="">
                                        <h1 className="text-7xl font-bold text-[#000] mb-2">STS</h1>
                                        <p className="text-sm text-gray-500">{transaction.invoice_code}</p>
                                    </div>
                                    <div className="h-full border-l border-gray-200 mx-10"></div>
                                    <div className="text-right flex flex-col items-start space-y-1">
                                        <span className="text-xs text-gray-400">Invoice number:</span>
                                        <span className="text-lg font-bold text-black">{transaction.invoice_code}</span>
                                        <span className="text-xs text-gray-400 mt-3">Issued:</span>
                                        <span className="text-base text-black">{formatDate(transaction.created_at, 'short')}</span>
                                        <span className="text-xs text-gray-400 mt-3">Status:</span>
                                        <span className={`text-sm font-semibold ${transaction.status === 'paid' ? 'text-green-600' : 'text-gray-600'}`}>
                                            {transaction.status ? transaction.status.toUpperCase() : 'PENDING'}
                                        </span>
                                    </div>
                                </div>

                                {/* Bill To / Date Info */}
                                <div className="flex flex-col md:flex-row gap-4 bg-white p-10 rounded-2xl shadow-xl mb-10">
                                    <div className="w-full md:w-2/5 flex flex-col">
                                        <div className="flex items-center gap-2 mb-2">
                                            <div className="flex items-center justify-center w-5 h-5 rounded-full bg-[#5FC3FF]">
                                                <svg xmlns="http://www.w3.org/2000/svg" className="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <h3 className="text-xs font-bold text-gray-400 uppercase tracking-wider">Billed To</h3>
                                        </div>
                                        <div className="bg-[#EBEFF6] p-4 rounded-lg flex-1 flex flex-col justify-between min-h-[170px]">
                                            <div className="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24" className="w-6 h-6 text-[#2388FF]">
                                                    <path fill="currentColor" d="M6.25 7a5.75 5.75 0 1 1 11.5 0a5.75 5.75 0 0 1-11.5 0m5.548 7.261a1 1 0 0 1 .13-.011h.144q.066 0 .13.011l7.295 1.283l.038.008c1.344.31 2.788 1.163 3.069 2.82l.004.029l.114.877v.002c.264 2.009-1.329 3.47-3.21 3.47a1 1 0 0 1-.124-.01h-14.9c-1.881 0-3.475-1.462-3.21-3.472l.114-.869l.005-.03c.28-1.627 1.736-2.528 3.077-2.819l.029-.006z"></path>
                                                </svg>
                                                <p className="font-bold text-lg">{transaction.shipping_first_name} {transaction.shipping_last_name}</p>
                                            </div>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                {transaction.shipping_phone_number}
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                {transaction.contact_email}
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                {transaction.shipping_address}, {transaction.shipping_city}, {transaction.shipping_postal_code}, {transaction.shipping_country}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="w-full md:w-2/5 flex flex-col">
                                        <div className="flex items-center gap-2 mb-2">
                                            <div className="flex items-center justify-center w-5 h-5 rounded-full bg-[#5FC3FF]">
                                                <svg xmlns="http://www.w3.org/2000/svg" className="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <h3 className="text-xs font-bold text-gray-400 uppercase tracking-wider">From</h3>
                                        </div>
                                        <div className="bg-[#EBEFF6] p-4 rounded-lg flex-1 flex flex-col justify-between min-h-[170px]">
                                            <div className="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
                                                    <g className="building-outline">
                                                        <g fill="#2388FF" className="Vector">
                                                            <path fillRule="evenodd" d="M8 5a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3h-8a3 3 0 0 1-3-3zm3-1a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1z" clipRule="evenodd"></path>
                                                            <path fillRule="evenodd" d="M2 11a3 3 0 0 1 3-3h4.5v2H5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.5v2H5a3 3 0 0 1-3-3z" clipRule="evenodd"></path>
                                                            <path fillRule="evenodd" d="M12 17a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v3h-2v-3h-2v3h-2z" clipRule="evenodd"></path>
                                                            <path d="M12 6a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm0 5a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm-7 4a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm11-9a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm0 5a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0z"></path>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <p className="font-bold text-lg">STS</p>
                                            </div>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                (684) 879 - 0102
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                contact@maurosicard.com
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                Pablo Alto, San Francisco, CA 94109, United States of America
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#868DA6]">
                                                12345 6789 US0001
                                            </p>
                                        </div>
                                    </div>
                                    <div className="w-full md:w-1/5 flex-shrink-0 flex flex-col">
                                        <div className="flex items-center gap-2 mb-2">
                                            <div className="flex items-center justify-center w-5 h-5 rounded-full bg-[#5FC3FF]">
                                                <svg xmlns="http://www.w3.org/2000/svg" className="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <h3 className="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</h3>
                                        </div>
                                        <div className="bg-[#5FC3FF] text-white p-4 rounded-lg flex-1 flex flex-col justify-between min-h-[170px]" style={{ boxShadow: '0px 4.22px 16.87px 0px #2388FF54' }}>
                                            <p className="font-bold text-lg">GBP</p>

                                            <p className="font-inter font-semibold text-xl md:text-2xl break-words">
                                                {formatPrice(transaction.total_amount)}
                                            </p>
                                            <p className="font-inter font-normal text-xs text-[#white]">
                                                {formatDate(transaction.created_at, 'short')}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {/* Table */}
                                <div className="gap-4 bg-white p-10 rounded-2xl shadow-xl mb-10 overflow-x-auto">
                                    <table className="w-full text-left border-separate border-spacing-y-2">
                                        <thead>
                                            <tr>
                                                <th className="py-3 px-4 text-xs font-bold text-gray-400 uppercase">Description</th>
                                                <th className="py-3 text-xs font-bold text-gray-400 uppercase text-center">Qty</th>
                                                <th className="py-3 text-xs font-bold text-gray-400 uppercase text-right">Price</th>
                                                <th className="py-3 px-4 text-xs font-bold text-gray-400 uppercase text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody className="text-sm">
                                            {transaction.items && transaction.items.length > 0 ? (
                                                transaction.items.map((item, index) => (
                                                    <tr key={index}>
                                                        <td className="py-4 pl-4 border-l border-y border-[#EBEFF6] rounded-l-full bg-white">
                                                            <p className="font-semibold text-gray-800">
                                                                {item.product?.title || item.product_name_snapshot || 'Product'}
                                                            </p>
                                                            <p className="text-xs text-gray-500">{item.product_sku_snapshot || '-'}</p>
                                                        </td>
                                                        <td className="py-4 border-y border-[#EBEFF6] bg-white text-center">{item.quantity}</td>
                                                        <td className="py-4 border-y border-[#EBEFF6] bg-white text-right">{formatPrice(item.unit_price)}</td>
                                                        <td className="py-4 pr-4 border-r border-y border-[#EBEFF6] rounded-r-full bg-white text-right font-medium">{formatPrice(item.total_price)}</td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td colSpan="4" className="py-4 text-center text-gray-500">No items found</td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>

                                    <div className="flex justify-end mt-6 pt-4 space-y-2 flex-col items-end">
                                        <div className="flex items-center gap-6 w-full max-w-xs justify-between">
                                            <span className="text-gray-500 text-sm">Subtotal</span>
                                            <span className="font-medium text-gray-700">{formatPrice(transaction.subtotal)}</span>
                                        </div>
                                        <div className="flex items-center gap-6 w-full max-w-xs justify-between">
                                            <span className="text-gray-500 text-sm">Shipping</span>
                                            <span className="font-medium text-gray-700">{formatPrice(transaction.shipping_price)}</span>
                                        </div>
                                        <div className="flex items-center gap-6 w-full max-w-xs justify-between">
                                            <span className="text-gray-500 text-sm">Tax</span>
                                            <span className="font-medium text-gray-700">{formatPrice(transaction.tax_amount)}</span>
                                        </div>
                                        <div className="flex items-center gap-6 w-full max-w-xs justify-between border-t border-gray-100 pt-2 mt-2">
                                            <span className="text-gray-500 font-bold">Total Amount</span>
                                            <span className="text-3xl font-bold text-[#232323]">{formatPrice(transaction.total_amount)}</span>
                                        </div>
                                    </div>
                                </div>

                                {/* Footer Notes */}
                                <div className="pt-6 text-start text-xs text-gray-500">
                                    <p className="mb-1 font-bold">Terms & Conditions:</p>
                                    <p>Fees and payment terms will be established in the contract or agreement prior to the commencement of the project. We reserve the right to suspend or halt work in the event of non-payment.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}
