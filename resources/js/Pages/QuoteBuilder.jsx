import React, { useState, useEffect, useRef } from 'react';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import Toast from '../components/Toast';

export default function QuoteBuilder({ quote: initialQuotes = [] }) {
    const [quotes, setQuotes] = useState(Array.isArray(initialQuotes) ? initialQuotes : []);
    const [isLoading, setIsLoading] = useState(false);
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    // State to handle editing for each quote
    const [editingQuoteId, setEditingQuoteId] = useState(null); // the quote id we're editing
    const [editQuoteName, setEditQuoteName] = useState('');     // value for edit input
    const editInputRefs = useRef({});
    const saveClickedRef = useRef(false);

    useEffect(() => {
        if (Array.isArray(initialQuotes)) {
            setQuotes(initialQuotes);
        }
    }, [initialQuotes]);
    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);
    const handleDeleteQuote = async (quoteId) => {
        if (!confirm('Are you sure you want to delete this quote?')) return;
        try {
            await axios.delete(`/api/quote-builder/${quoteId}`);
            setQuotes(prev => prev.filter(q => q.id !== quoteId));
            setToast({
                show: true,
                message: 'Quote deleted successfully.',
                type: 'success'
            });
            // If the edited quote was deleted, exit edit mode
            if (editingQuoteId === quoteId) {
                setEditingQuoteId(null);
                setEditQuoteName('');
            }
        } catch (error) {
            console.error('Failed to delete quote', error);
            setToast({
                show: true,
                message: 'Failed to delete quote.',
                type: 'error'
            });
        }
    };

    const handleDeleteProduct = async (quoteId, productId) => {
        if (!confirm('Are you sure you want to remove this product from the quote?')) return;
        try {
            await axios.delete(`/api/quote-builder/${quoteId}/products/${productId}`);
            setQuotes(prevQuotes => prevQuotes.map(quote => {
                if (quote.id === quoteId) {
                    return {
                        ...quote,
                        products: quote.products.filter(p => (p.id || p.product_id) !== productId)
                    };
                }
                return quote;
            }));
            setToast({
                show: true,
                message: 'Product removed from quote.',
                type: 'success'
            });
        } catch (error) {
            console.error('Failed to delete product', error);
            setToast({
                show: true,
                message: 'Failed to remove product from quote.',
                type: 'error'
            });
        }
    };

    // Handle click for modify/save button
    const handleModifyClick = (quote) => {
        setEditingQuoteId(quote.id);
        setEditQuoteName(quote.name ?? '');
        setTimeout(() => {
            if (editInputRefs.current[quote.id]) {
                editInputRefs.current[quote.id].focus();
            }
        }, 100);
    };

    const handleEditInputChange = (e) => {
        setEditQuoteName(e.target.value);
    };

    // Save updated quote name
    const handleSaveQuoteName = async (quote) => {
        const trimmedName = editQuoteName.trim();
        if (!trimmedName) return;

        try {
            setIsLoading(true);
            const { data } = await axios.put(`/api/quote-builder/${quote.id}`, { name: trimmedName });
            setQuotes(prev =>
                prev.map(q =>
                    q.id === quote.id ? { ...q, name: data?.name ?? trimmedName } : q
                )
            );
            setToast({
                show: true,
                message: 'Quote name updated successfully.',
                type: 'success'
            });
            setEditingQuoteId(null);
            setEditQuoteName('');
        } catch (error) {
            setToast({
                show: true,
                message: 'Failed to update quote name.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

    // Handle input keyenter/escape
    const handleInputKeyDown = (e, quote) => {
        if (e.key === "Enter") {
            e.preventDefault();
            handleSaveQuoteName(quote);
        } else if (e.key === "Escape") {
            setEditingQuoteId(null);
            setEditQuoteName('');
        }
    };

    // Cancel edit mode for a quote (only trigger if not losing focus to Save/Modify/other input)
    const handleCancelEdit = (e) => {
        // Don't close if focus moves to Save button or Modify, only close if leaving the quote card
        // Basic logic: only close if the relatedTarget (receiving focus) is not inside this card
        // but for simplicity, just close unless it's Save button
        setEditingQuoteId(null);
        setEditQuoteName('');
    };

    return (
        <div className="min-h-screen flex flex-col bg-white">
            <Head title="Quote Builder" />
            <Header />

            <Toast
                show={toast.show}
                message={toast.message}
                type={toast.type}
                onClose={() => setToast((t) => ({ ...t, show: false }))}
            />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <nav className="text-xs md:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol className="flex flex-wrap items-center gap-1">
                        <li><Link href='/home' className="hover:text-[#0079C2]">Home</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li><Link href='/products' className="hover:text-[#0079C2]">Products</Link></li>
                        <li className="mx-1 text-gray-400">/</li>
                        <li className="text-gray-700">Quote Builder</li>
                    </ol>
                </nav>

                {/* Page Header */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
                    <h1 className="text-3xl font-bold text-gray-800">Quote Builder</h1>
                    <div className="flex flex-col gap-2 w-full md:w-auto">
                        <button className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition">
                            ADD TO CART
                        </button>
                    </div>
                </div>

                {/* Quote List */}
                {isLoading && !editingQuoteId ? (
                    <div className="text-center py-10">Loading quotes...</div>
                ) : quotes.length === 0 ? (
                    <div className="text-center py-10 bg-gray-50 rounded-xl">
                        <p className="text-gray-500 text-lg">You have no quotes yet.</p>
                        <Link href="/products" className="text-[#0079C2] hover:underline mt-2 inline-block">
                            Browse Products
                        </Link>
                    </div>
                ) : (
                    <div className="space-y-6">
                        {quotes.map((quote) => {
                            const isEditing = editingQuoteId === quote.id;
                            return (
                                <div key={quote.id} className="bg-[#F5F5F5] rounded-xl p-6 shadow-sm">
                                    {/* Card Header */}
                                    <div className="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                                        <div className="flex items-center gap-3">
                                            {isEditing ? (
                                                <input type="checkbox" className="w-5 h-5 text-[#0079C2] rounded focus:ring-[#0079C2]" />
                                            ) : null}
                                            {isEditing ? (
                                                <input
                                                    ref={(el) => { editInputRefs.current[quote.id] = el; }}
                                                    type="text"
                                                    className="font-semibold text-lg text-gray-700 border border-gray-300 bg-white rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0079C2]"
                                                    value={editQuoteName}
                                                    onChange={handleEditInputChange}
                                                    onKeyDown={(e) => handleInputKeyDown(e, quote)}
                                                    onBlur={() => { if (saveClickedRef.current || (document.activeElement && document.activeElement.dataset && document.activeElement.dataset.quoteAction === 'save')) { saveClickedRef.current = false; return; } handleCancelEdit(); }}
                                                    style={{ minWidth: 160 }}
                                                />
                                            ) : (
                                                <span className="font-semibold text-lg text-gray-700">
                                                    {(quote.name && quote.name.length > 0) ? quote.name : `Quote #${quote.id}`}
                                                </span>
                                            )}
                                        </div>
                                        {isEditing ? (
                                            <button
                                                onClick={() => handleDeleteQuote(quote.id)}
                                                className="text-gray-400 hover:text-red-500 transition"
                                                title="Delete Quote"
                                                type="button"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        ) : null}
                                    </div>

                                    <div className="flex flex-col lg:flex-row gap-6">
                                        {/* Scrollable Product List */}
                                        <div className="flex-1 overflow-x-auto">
                                            <div className="flex gap-4 pb-4 min-w-min">
                                                {quote.products && quote.products.length > 0 ? (
                                                    quote.products.map((product, i) => (
                                                        <div key={product.id || i} className="min-w-[220px] bg-white rounded-lg border border-gray-200 p-4 relative shrink-0">
                                                            <div className="absolute top-3 left-3">
                                                                <input type="checkbox" className={isEditing ? "w-4 h-4 text-[#0079C2] rounded focus:ring-[#0079C2]" : "hidden"} />
                                                            </div>
                                                            <button
                                                                onClick={() => handleDeleteProduct(quote.id, product.id || product.product_id)}
                                                                className={`absolute top-3 right-3 text-gray-400 hover:text-red-500 transition ${isEditing ? '' : 'hidden'}`}
                                                                title="Remove Product"
                                                                tabIndex={isEditing ? undefined : -1}
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                            <Link href={`/products/${product.slug || '#'}`} className="h-40 flex items-center justify-center mt-4">
                                                                <img
                                                                    src={
                                                                        product.images && product.images.length > 0 && product.images[0].image_path
                                                                            ? (product.images[0].image_path.startsWith('/') ? product.images[0].image_path : `/storage/${product.images[0].image_path}`)
                                                                            : (product.image || product.image_url || '/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png')
                                                                    }
                                                                    alt={product.title || product.name || 'Product'}
                                                                    className="max-h-full max-w-full object-contain"
                                                                    onError={(e) => { e.target.onerror = null; e.target.src = '/assets/logo.png'; }}
                                                                />
                                                            </Link>
                                                            <div className="mt-2 text-center">
                                                                <p className="text-sm font-medium text-gray-600 line-clamp-2" title={product.name}>
                                                                    {product.title || 'Unknown Product'}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    ))
                                                ) : (
                                                    <div className="text-gray-400 italic p-4">No products in this quote.</div>
                                                )}
                                            </div>
                                        </div>

                                        {/* Action Buttons */}
                                        <div className="w-full lg:w-64 flex flex-col gap-3 shrink-0 justify-center">
                                            <button className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition">
                                                PROCEED TO CHECKOUT
                                            </button>
                                            {isEditing ? (
                                                <button
                                                    data-quote-action="save"
                                                    onMouseDown={() => { saveClickedRef.current = true; }}
                                                    className="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition"
                                                    onClick={() => handleSaveQuoteName(quote)}
                                                    disabled={isLoading || editQuoteName.trim().length === 0}
                                                    type="button"
                                                >
                                                    SAVE
                                                </button>
                                            ) : (
                                                <button
                                                    className="bg-[#0079C2] hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition"
                                                    onClick={() => handleModifyClick(quote)}
                                                    type="button"
                                                >
                                                    MODIFY QUOTE
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </main>

            <Footer />
        </div>
    );
}
