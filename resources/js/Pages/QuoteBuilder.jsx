import React, { useState, useEffect, useRef } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import Toast from '../components/Toast';

export default function QuoteBuilder() {
    const [quotes, setQuotes] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    // State for Create Quote Modal
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [newQuoteName, setNewQuoteName] = useState('');
    const [isCreating, setIsCreating] = useState(false);

    // State to handle editing for each quote
    const [editingQuoteId, setEditingQuoteId] = useState(null); // the quote id we're editing
    const [editQuoteName, setEditQuoteName] = useState('');     // value for edit input
    const editInputRefs = useRef({});
    const saveClickedRef = useRef(false);
    const deleteClickedRef = useRef(false);
    const addProductClickedRef = useRef(false); // NEW: Track if Add Product was clicked

    const fetchQuotes = async () => {
        setIsLoading(true);
        try {
            const res = await axios.get('/web/quote-builder');
            setQuotes(res.data.data || res.data || []);
        } catch (error) {
            console.error('Failed to fetch quotes', error);
            setToast({ show: true, message: 'Failed to load quotes', type: 'error' });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        // Fetch fresh data from API on mount
        fetchQuotes();
    }, []);

    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    const handleCreateQuote = async (e) => {
        e.preventDefault();
        if (!newQuoteName.trim()) return;

        setIsCreating(true);
        try {
            const res = await axios.post('/web/quote-builder', {
                name: newQuoteName
            });

            // Refresh quotes or add strict to list if response contains it
            await fetchQuotes();

            setToast({
                show: true,
                message: 'Quote created successfully.',
                type: 'success'
            });
            setIsCreateModalOpen(false);
            setNewQuoteName('');
        } catch (error) {
            console.error('Failed to create quote', error);
            setToast({
                show: true,
                message: 'Failed to create quote.',
                type: 'error'
            });
        } finally {
            setIsCreating(false);
        }
    };

    const handleDeleteQuote = async (quoteId) => {
        if (!confirm('Are you sure you want to delete this quote?')) return;
        try {
            await axios.delete(`/web/quote-builder/${quoteId}`);
            setQuotes(prev => prev.filter(q => q.id !== quoteId));
            setToast({
                show: true,
                message: 'Quote deleted successfully.',
                type: 'success'
            });
            // Refresh from server to ensure state is consistent
            await fetchQuotes();
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
            await axios.delete(`/web/quote-builder/${quoteId}/products/${productId}`);
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

    // Update quantity via API
    const updateProductQuantity = async (quoteId, productId, newQty) => {
        let qty = parseInt(newQty);
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            // Correct the UI state immediately
            setQuotes(prev => prev.map(q => {
                if (q.id === quoteId) {
                    return {
                        ...q,
                        products: q.products.map(p => {
                            const pId = p.id || p.product_id;
                            if (pId === productId) {
                                return { ...p, pivot: { ...p.pivot, quantity: qty } };
                            }
                            return p;
                        })
                    };
                }
                return q;
            }));
        }

        try {
            await axios.post(`/web/quote-builder/${quoteId}/products`, {
                product_id: productId,
                quantity: qty
            });
            // Optional: Show success toast or just silent update
        } catch (error) {
            console.error('Failed to update quantity', error);
            setToast({
                show: true,
                message: 'Failed to update quantity.',
                type: 'error'
            });
            // Revert changes by refetching
            fetchQuotes();
        }
    };

    const handleQuantityChange = (quoteId, productId, val) => {
        // Allow empty string to let user clear input
        if (val === '') {
            setQuotes(prev => prev.map(q => {
                if (q.id === quoteId) {
                    return {
                        ...q,
                        products: q.products.map(p => {
                            const pId = p.id || p.product_id;
                            if (pId === productId) {
                                return { ...p, pivot: { ...p.pivot, quantity: '' } };
                            }
                            return p;
                        })
                    };
                }
                return q;
            }));
            return;
        }

        const qty = parseInt(val);
        if (isNaN(qty) || qty < 1) return;

        setQuotes(prev => prev.map(q => {
            if (q.id === quoteId) {
                return {
                    ...q,
                    products: q.products.map(p => {
                        const pId = p.id || p.product_id;
                        if (pId === productId) {
                            return { ...p, pivot: { ...p.pivot, quantity: qty } };
                        }
                        return p;
                    })
                };
            }
            return q;
        }));
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
            const { data } = await axios.put(`/web/quote-builder/${quote.id}`, { name: trimmedName });
            setQuotes(prev =>
                prev.map(q =>
                    q.id === quote.id ? { ...q, name: data?.name ?? trimmedName } : q
                )
            );
            setToast({
                show: true,
                message: 'Quote saved successfully.',
                type: 'success'
            });
            setEditingQuoteId(null);
            setEditQuoteName('');

            // Also refresh to ensure all quantities are synced correctly (though we update on blur, good practice)
            fetchQuotes();
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
        setEditingQuoteId(null);
        setEditQuoteName('');
    };

    // New logic: button add product goes to /products
    const handleAddProductToQuote = (quote) => {
        // Instead of showing a toast, directly navigate to the product list page
        // Use setTimeout to ensure this runs *after* blur/other handlers if needed
        router.visit('/products');
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

            {/* Create Quote Modal */}
            {isCreateModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
                        <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 className="text-lg font-semibold text-gray-800">Create New Quote</h3>
                            <button onClick={() => setIsCreateModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form onSubmit={handleCreateQuote}>
                            <div className="px-6 py-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="quoteName">
                                    Quote Name
                                </label>
                                <input
                                    id="quoteName"
                                    type="text"
                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-[#0079C2]"
                                    placeholder="e.g. My New Project"
                                    value={newQuoteName}
                                    onChange={(e) => setNewQuoteName(e.target.value)}
                                    autoFocus
                                    required
                                />
                            </div>
                            <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                <button
                                    type="button"
                                    onClick={() => setIsCreateModalOpen(false)}
                                    className="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded shadow-sm transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={isCreating}
                                    className="bg-[#0079C2] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm transition disabled:opacity-50"
                                >
                                    {isCreating ? 'Creating...' : 'Create Values'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

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
                    <div className="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                        <button
                            onClick={() => setIsCreateModalOpen(true)}
                            className="bg-[#0079C2] hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition">
                            Create New Quote
                        </button>
                        <button
                            onClick={() => router.visit('/quote-checkout')}
                            className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition">
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
                        <button
                            onClick={() => setIsCreateModalOpen(true)}
                            className="text-[#0079C2] hover:underline mt-2 inline-block font-semibold">
                            Create your first quote
                        </button>
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
                                                    onBlur={(e) => {
                                                        const newTarget = e.relatedTarget;
                                                        if (
                                                            saveClickedRef.current ||
                                                            deleteClickedRef.current ||
                                                            addProductClickedRef.current || // do not cancel if add product clicked
                                                            (newTarget && newTarget.dataset && newTarget.dataset.quoteAction === 'save') ||
                                                            (newTarget && newTarget.className && typeof newTarget.className === 'string' && newTarget.className.includes('qty-input'))
                                                        ) {
                                                            saveClickedRef.current = false;
                                                            deleteClickedRef.current = false;
                                                            addProductClickedRef.current = false;
                                                            return;
                                                        }

                                                        handleCancelEdit();
                                                    }}
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
                                                onMouseDown={() => { deleteClickedRef.current = true; }}
                                                onClick={async () => { await handleDeleteQuote(quote.id); deleteClickedRef.current = false; }}
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
                                                    quote.products.map((product, i) => {
                                                        const pId = product.id || product.product_id;
                                                        return (
                                                            <div key={pId || i} className="min-w-[220px] bg-white rounded-lg border border-gray-200 p-4 relative shrink-0">
                                                                <div className="absolute top-3 left-3">
                                                                    <input type="checkbox" className={isEditing ? "w-4 h-4 text-[#0079C2] rounded focus:ring-[#0079C2]" : "hidden"} />
                                                                </div>
                                                                <button
                                                                    onClick={() => handleDeleteProduct(quote.id, pId)}
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
                                                                    {/* Quantity Display/Edit */}
                                                                    <div className="mt-2 flex items-center justify-center gap-2">
                                                                        {isEditing ? (
                                                                            <div className="flex items-center gap-2">
                                                                                <span className="text-xs text-gray-500 font-bold">Qty:</span>
                                                                                <input
                                                                                    type="number"
                                                                                    min="1"
                                                                                    className="qty-input w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:border-[#0079C2] focus:outline-none"
                                                                                    value={product.pivot?.quantity ?? 1}
                                                                                    onChange={(e) => handleQuantityChange(quote.id, pId, e.target.value)}
                                                                                    onBlur={(e) => updateProductQuantity(quote.id, pId, e.target.value)}
                                                                                />
                                                                            </div>
                                                                        ) : (
                                                                            <div className="text-sm text-gray-500">
                                                                                Qty: <span className="font-semibold text-gray-700">{product.pivot?.quantity ?? 1}</span>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        );
                                                    })
                                                ) : (
                                                    <div className="text-gray-400 italic p-4">No products in this quote.</div>
                                                )}

                                                {/* ADD PRODUCT BUTTON - show after products when isEditing */}
                                                {isEditing && (
                                                    <button
                                                        type="button"
                                                        onMouseDown={() => { addProductClickedRef.current = true; }}
                                                        onClick={() => {
                                                            handleAddProductToQuote(quote);
                                                            addProductClickedRef.current = false;
                                                        }}
                                                        className="min-w-[220px] flex flex-col items-center justify-center border-2 border-dashed border-[#0079C2] bg-white/80 rounded-lg py-8 px-4 text-[#0079C2] font-semibold hover:bg-blue-50 transition cursor-pointer"
                                                        style={{ height: "232px" }} // To visually match product card
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="#0079C2" strokeWidth={2}>
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        Add Product
                                                    </button>
                                                )}

                                            </div>
                                        </div>

                                        {/* Action Buttons */}
                                        <div className="w-full lg:w-64 flex flex-col gap-3 shrink-0 justify-center">
                                            <button
                                                onClick={() => router.visit('/quote-checkout', { data: { quote_ids: [quote.id] } })}
                                                className="bg-[#5FC3FF] hover:bg-blue-400 text-white font-bold py-3 px-4 rounded-md shadow-sm text-center transition">
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
