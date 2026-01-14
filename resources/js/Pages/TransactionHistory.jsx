import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function TransactionHistory() {
    const [transactions, setTransactions] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const fetchTransactions = async () => {
            try {
                const res = await axios.get('/web/my-transactions');
                setTransactions(res.data.data || res.data || []);
            } catch (error) {
                console.error('Failed to load transactions', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchTransactions();
    }, []);

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: 'numeric', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    };

    const formatPrice = (price) => {
        return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(price);
    };

    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="My Transactions" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <div className="max-w-6xl w-full mx-auto bg-white p-6 rounded-lg shadow-sm">
                    <h1 className="text-2xl font-semibold mb-6">My Transactions</h1>

                    {isLoading ? (
                        <div className="text-center py-10 text-gray-500">Loading transactions...</div>
                    ) : transactions.length === 0 ? (
                        <div className="text-center py-10">
                            <p className="mb-4 text-gray-500">You have no transactions yet.</p>
                            <Link href="/products" className="text-[#0079C2] hover:underline">Start shopping</Link>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse">
                                <thead>
                                    <tr className="border-b text-sm text-gray-600 bg-gray-50">
                                        <th className="py-3 px-4">Date</th>
                                        <th className="py-3 px-4">Reference</th>
                                        <th className="py-3 px-4">Total Amount</th>
                                        <th className="py-3 px-4">Status</th>
                                        <th className="py-3 px-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {transactions.map((trx) => (
                                        <tr key={trx.id} className="border-b last:border-0 hover:bg-gray-50 transition-colors">
                                            <td className="py-4 px-4 text-sm">{formatDate(trx.created_at)}</td>
                                            <td className="py-4 px-4 text-sm font-medium">{trx.invoice_code || trx.reference || `#${trx.id}`}</td>
                                            <td className="py-4 px-4 text-sm font-medium">{formatPrice(trx.total_amount)}</td>
                                            <td className="py-4 px-4 text-sm">
                                                <span className={`px-2 py-1 rounded text-xs font-semibold
                                                    ${trx.status === 'paid' ? 'bg-green-100 text-green-700' :
                                                        trx.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'}`}>
                                                    {trx.status || 'Pending'}
                                                </span>
                                            </td>
                                            <td className="py-4 px-4 text-right">
                                                <Link
                                                    href={`/my-transactions/${trx.id}`}
                                                    className="text-[#0079C2] hover:underline text-sm font-medium"
                                                >
                                                    View Details
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </main>

            <Footer />
        </div>
    );
}
