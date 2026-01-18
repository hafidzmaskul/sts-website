import React, { useState, useRef, useMemo, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import FeaturedProductsSection from '../components/FeaturedProductsSection';
import LoginModal from '../components/LoginModal';
import Toast from '../components/Toast';

const formatPrice = (price) => {
    if (!price) {
        return '-';
    }
    const cleanedPrice = price.toString().replace(/[^\d.-]/g, '');
    const parsedPrice = parseFloat(cleanedPrice);
    if (Number.isNaN(parsedPrice)) {
        return '-';
    }
    const finalPrice = parsedPrice > 10000 ? parsedPrice : parsedPrice * 1000;
    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP',
        maximumFractionDigits: 0,
    }).format(finalPrice);
};

const ImageZoom = ({ src, alt, className }) => {
    const [isZoomed, setIsZoomed] = useState(false);
    const [position, setPosition] = useState({ x: 0, y: 0 });
    const containerRef = useRef(null);
    const imageRef = useRef(null);
    const handleMouseMove = (e) => {
        if (!containerRef.current || !imageRef.current) return;
        const { left, top, width, height } = containerRef.current.getBoundingClientRect();
        const x = ((e.clientX - left) / width) * 100;
        const y = ((e.clientY - top) / height) * 100;
        setPosition({ x, y });
    };
    return (
        <div
            ref={containerRef}
            className={`relative overflow-hidden ${className}`}
            onMouseEnter={() => setIsZoomed(true)}
            onMouseLeave={() => setIsZoomed(false)}
            onMouseMove={handleMouseMove}
        >
            <img
                ref={imageRef}
                src={src}
                alt={alt}
                className="w-full h-full object-contain p-10"
                loading="lazy"
            />
            {isZoomed && (
                <div
                    className="absolute inset-0 pointer-events-none p-10"
                    style={{
                        backgroundImage: `url(${src})`,
                        backgroundPosition: `${position.x}% ${position.y}%`,
                        backgroundSize: '200%',
                        backgroundRepeat: 'no-repeat',
                    }}
                />
            )}
        </div>
    );
};

// Modified Modal to support custom sizes
const Modal = ({ isOpen, onClose, title, children, size = 'md' }) => {
    if (!isOpen) return null;
    // Sizes: md, lg, xl
    const sizeClasses = {
        md: "max-w-md",
        lg: "max-w-3xl",
        xl: "max-w-5xl"
    };
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-2 py-6">
            <div className={`bg-white rounded-lg shadow-xl w-full ${sizeClasses[size]} p-6 relative`}>
                <button
                    onClick={onClose}
                    className="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                {title && <h3 className="text-xl font-bold mb-4">{title}</h3>}
                {children}
            </div>
        </div>
    );
};


export default function ProductDetail({ product, products = [], logged }) {

    const [isLoginModalOpen, setIsLoginModalOpen] = useState(false);

    // Toast state
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    // Quote Builder States
    const [isQuoteOptionModalOpen, setIsQuoteOptionModalOpen] = useState(false);
    const [isExistingQuoteModalOpen, setIsExistingQuoteModalOpen] = useState(false);
    const [isCreateQuoteModalOpen, setIsCreateQuoteModalOpen] = useState(false);
    const [existingQuotes, setExistingQuotes] = useState([]);
    const [selectedQuoteIds, setSelectedQuoteIds] = useState([]);
    const [newQuoteName, setNewQuoteName] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    // Default quantity for quote builder logic
    const DEFAULT_QUANTITY = 1;

    // Auto-hide toast
    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    if (!product || !product.id) {
        return (
            <div className="min-h-screen flex flex-col">
                <Head title="Product Not Found" />
                <Header />
                <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-20 text-center">
                    <h1 className="text-2xl font-semibold text-gray-700 mb-4">Product Not Found</h1>
                    <Link href="/products" className="text-[#0079C2] hover:underline">
                        Back to Products
                    </Link>
                </main>
                <Footer />
            </div>
        );
    }

    const data = product;

    const productImages = useMemo(() => {
        if (Array.isArray(data.images) && data.images.length > 0) {
            return data.images
                .sort((a, b) => (a.sequence || 0) - (b.sequence || 0))
                .map((img) => {
                    const imagePath = img.image_path;
                    return imagePath?.startsWith('/') ? imagePath : `/storage/${imagePath}`;
                });
        }
        return ['/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png'];
    }, [data.images]);

    const [activeImageIndex, setActiveImageIndex] = useState(0);
    const imageContainerRef = useRef(null);
    const [isAddingToCart, setIsAddingToCart] = useState(false);
    const [openAccordion, setOpenAccordion] = useState('description');

    // Set specs with price conditional
    const specs = useMemo(() => [
        { label: 'Brand', value: data.brand_name || '-' },
        { label: 'Status', value: data.status === 'active' ? 'Ready Stock' : 'Unavailable' },
        {
            label: 'Harga',
            value:
                data.is_sign_up_for_pricing
                    ? (logged
                        ? formatPrice(data.base_price)
                        : <span className="italic text-gray-400">Login untuk melihat harga</span>)
                    : formatPrice(data.base_price),
        },
        { label: 'Exclusive', value: data.is_exclusive ? 'Yes' : 'No' },
    ], [data.brand_name, data.status, data.base_price, data.is_exclusive, data.is_sign_up_for_pricing, logged]);

    const accordionItems = useMemo(() => [
        {
            id: 'description',
            title: 'Deskripsi Produk',
            content: data.product_overview || '',
        },
        {
            id: 'specs',
            title: 'Spesifikasi Teknis',
            content: specs,
        },
        {
            id: 'information',
            title: 'Information',
            content: data.information || '',
        },
    ], [data.product_overview, data.information, specs]);

    const handleToggleAccordion = (sectionId) => {
        setOpenAccordion((current) => (current === sectionId ? null : sectionId));
    };

    // Quote Builder Handlers
    const handleAddToQuoteClick = () => {
        setIsQuoteOptionModalOpen(true);
    };

    const handleOpenExistingQuotes = async () => {
        setIsQuoteOptionModalOpen(false);
        setIsLoading(true);
        try {
            const response = await axios.get('/web/quote-builder');
            setExistingQuotes(response.data.data || response.data || []);
            setIsExistingQuoteModalOpen(true);
        } catch (error) {
            console.error('Failed to fetch quotes', error);
            setToast({
                show: true,
                message: 'Failed to load existing quotes.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

    const handleOpenCreateQuote = () => {
        setIsQuoteOptionModalOpen(false);
        setIsCreateQuoteModalOpen(true);
    };

    const handleQuoteCheckboxChange = (quoteId) => {
        setSelectedQuoteIds(prev => {
            if (prev.includes(quoteId)) {
                return prev.filter(id => id !== quoteId);
            } else {
                return [...prev, quoteId];
            }
        });
    };

    // --- CHANGES: handleSaveToExistingQuotes (payload) ---
    const handleSaveToExistingQuotes = async () => {
        if (selectedQuoteIds.length === 0) {
            setToast({
                show: true,
                message: 'Please select at least one quote.',
                type: 'error'
            });
            return;
        }
        setIsLoading(true);
        try {
            await Promise.all(selectedQuoteIds.map(quoteId =>
                axios.post(`/web/quote-builder/${quoteId}/products`, {
                    product_id: data.id,
                    quantity: DEFAULT_QUANTITY
                })
            ));

            setIsExistingQuoteModalOpen(false);
            setSelectedQuoteIds([]);
            setToast({
                show: true,
                message: 'Product added to selected quote(s) successfully!',
                type: 'success'
            });
            setTimeout(() => {
                router.visit('/quote-builder');
            }, 1000); // Wait a bit for toast to be seen
        } catch (error) {
            console.error('Failed to add product to quotes', error);
            setToast({
                show: true,
                message: 'Failed to add product to some quotes.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

    // --- CHANGES: handleCreateQuote (payload) ---
    const handleCreateQuote = async () => {
        if (!newQuoteName.trim()) {
            setToast({
                show: true,
                message: 'Please enter a quote name.',
                type: 'error'
            });
            return;
        }
        setIsLoading(true);
        try {
            await axios.post('/web/quote-builder', {
                name: newQuoteName,
                items: [
                    {
                        product_id: data.id,
                        quantity: DEFAULT_QUANTITY
                    }
                ]
            });

            setIsCreateQuoteModalOpen(false);
            setNewQuoteName('');
            setToast({
                show: true,
                message: 'New quote created and product added successfully!',
                type: 'success'
            });
            setTimeout(() => {
                router.visit('/quote-builder');
            }, 1000);
        } catch (error) {
            console.error('Failed to create quote', error);
            setToast({
                show: true,
                message: 'Failed to create new quote.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

    // Cart helpers: animation and add-to-cart
    const animateAddToCart = () => {
        const img = imageContainerRef.current?.querySelector('img');
        if (!img) return;
        const cartEl = document.querySelector('a[href="/cart"]');
        const imgRect = img.getBoundingClientRect();
        const cartRect = cartEl ? cartEl.getBoundingClientRect() : { left: window.innerWidth - 40, top: 20, width: 20, height: 20 };
        const clone = img.cloneNode();
        clone.style.position = 'fixed';
        clone.style.left = `${imgRect.left}px`;
        clone.style.top = `${imgRect.top}px`;
        clone.style.width = `${imgRect.width}px`;
        clone.style.height = `${imgRect.height}px`;
        clone.style.zIndex = 9999;
        clone.style.transition = 'transform 700ms ease-in-out, opacity 700ms ease-in-out';
        document.body.appendChild(clone);
        requestAnimationFrame(() => {
            const translateX = cartRect.left + cartRect.width / 2 - (imgRect.left + imgRect.width / 2);
            const translateY = cartRect.top + cartRect.height / 2 - (imgRect.top + imgRect.height / 2);
            clone.style.transform = `translate(${translateX}px, ${translateY}px) scale(0.15)`;
            clone.style.opacity = '0.6';
        });
        setTimeout(() => clone.remove(), 800);
    };

    const refreshCart = async () => {
        try {
            const res = await axios.get('/web/cart');
            const items = res.data.data || res.data || [];
            const count = items.reduce((s, i) => s + (i.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));
        } catch (e) {
            // ignore
        }
    };

    const handleAddToCart = async () => {
        setIsAddingToCart(true);
        animateAddToCart();
        try {
            await axios.post('/web/cart', { product_id: data.id, quantity: 1 });
            setToast({ show: true, message: 'Product added to cart', type: 'success' });
            await refreshCart();
        } catch (error) {
            console.error('Add to cart failed', error);
            setToast({ show: true, message: error?.response?.data?.message || 'Failed to add to cart', type: 'error' });
        } finally {
            setIsAddingToCart(false);
        }
    };

    const featuredProducts = useMemo(() => {
        if (products.length === 0) {
            return [];
        }

        return products
            .filter((p) => p.id !== data.id)
            .slice(0, 8)
            .map((product, index) => {
                const imagePath = product.images?.[0]?.image_path;
                const image = imagePath
                    ? (imagePath.startsWith('/') ? imagePath : `/storage/${imagePath}`)
                    : '/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png';

                let basePrice = null;
                if (product.base_price) {
                    const cleanedPrice = product.base_price.toString().replace(/[^\d.-]/g, '');
                    const parsedPrice = parseFloat(cleanedPrice);
                    if (!Number.isNaN(parsedPrice)) {
                        basePrice = parsedPrice > 10000 ? parsedPrice : parsedPrice * 1000;
                    }
                }

                const badge = index % 3 === 0 ? 'New' : index % 3 === 1 ? 'Best Seller' : 'Limited';

                return {
                    id: product.id,
                    title: product.title || product.name || '',
                    price: basePrice,
                    image,
                    badge: product.badge || badge,
                };
            });
    }, [products, data.id]);


    // Helper for product image url
    const getCoverImage = (product) => {
        if (Array.isArray(product.images) && product.images.length > 0) {
            const sorted = [...product.images].sort((a, b) => (a.sequence || 0) - (b.sequence || 0));
            const imagePath = sorted[0].image_path;
            if (imagePath?.startsWith("http")) {
                return imagePath;
            }
            return imagePath ? `/storage/${imagePath}` : "/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png";
        }
        return "/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png";
    };

    // --- RENDER ---
    return (
        <div className="min-h-screen flex flex-col ">
            <Toast
                show={toast.show}
                message={toast.message}
                type={toast.type}
                onClose={() => setToast((t) => ({ ...t, show: false }))}
            />
            <LoginModal
                isOpen={isLoginModalOpen}
                onClose={() => setIsLoginModalOpen(false)}
            />

            {/* Quote Option Modal */}
            <Modal
                isOpen={isQuoteOptionModalOpen}
                onClose={() => setIsQuoteOptionModalOpen(false)}
                title="Add to Quote"
            >
                <div className="flex flex-col gap-4">
                    <button
                        onClick={handleOpenExistingQuotes}
                        className="w-full py-3 bg-[#0079C2] text-white rounded-lg hover:bg-[#005a91] transition font-medium"
                    >
                        Add to Existing Quote
                    </button>
                    <button
                        onClick={handleOpenCreateQuote}
                        className="w-full py-3 border border-[#0079C2] text-[#0079C2] rounded-lg hover:bg-blue-50 transition font-medium"
                    >
                        Create New Quote
                    </button>
                </div>
            </Modal>

            {/* Existing Quote Modal (BIGGER, with product info displayed) */}
            <Modal
                isOpen={isExistingQuoteModalOpen}
                onClose={() => setIsExistingQuoteModalOpen(false)}
                title="Select Quote"
                size="lg"
            >
                {isLoading ? (
                    <div className="text-center py-6 text-lg">Loading quotes...</div>
                ) : (
                    <div className="flex flex-col md:flex-row gap-8">
                        {/* Product Info Area */}
                        <div className="w-full md:w-2/5 bg-gray-50 rounded-lg p-4 flex flex-col items-center md:items-start justify-center border border-gray-200">
                            {/* Product Image */}
                            <img
                                src={productImages[activeImageIndex]}
                                alt={data.title}
                                className="w-32 h-32 object-contain mb-3 border rounded-lg bg-white"
                            />
                            <h3 className="text-xl font-semibold mb-2 text-[#232323]">{data.title}</h3>
                            <p className="text-gray-500 text-sm mb-1">{data.brand_name}</p>
                            <p className="text-[#0079C2] text-lg font-bold mb-2">
                                {data.is_sign_up_for_pricing && !logged
                                    ? <span className="italic text-gray-400">Login untuk melihat harga</span>
                                    : formatPrice(data.base_price)
                                }
                            </p>
                        </div>
                        {/* Quote Selection Area, scrollable */}
                        <div className="w-full md:w-3/5 flex flex-col gap-4 max-h-[400px] overflow-y-auto">
                            <div className="space-y-1 border rounded-lg p-2 flex-1">
                                {existingQuotes.length > 0 ? (
                                    existingQuotes.map(quote => (
                                        <div key={quote.id} className="border-b border-dashed border-gray-200 py-3 last:border-none px-1">
                                            <label className="flex items-center gap-3 cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    checked={selectedQuoteIds.includes(quote.id)}
                                                    onChange={() => handleQuoteCheckboxChange(quote.id)}
                                                    className="w-5 h-5 text-[#0079C2] border-gray-300 rounded focus:ring-[#0079C2]"
                                                />
                                                <div className="flex flex-col gap-0">
                                                    <span className="text-gray-700 font-medium">{quote.name || `Quote #${quote.id}`}</span>
                                                    <span className="text-xs text-gray-400">
                                                        {Array.isArray(quote.products)
                                                            ? `${quote.products.length} product${quote.products.length !== 1 ? "s" : ""} in this quote`
                                                            : '0 products'
                                                        }
                                                    </span>
                                                </div>
                                            </label>
                                            {/* Show existing quote's products thumbnails and titles */}
                                            {quote.products && quote.products.length > 0 && (
                                                <div className="flex flex-row flex-wrap gap-2 mt-1 mb-2 pl-8">
                                                    {quote.products.slice(0, 5).map(prod => (
                                                        <div key={prod.id} className="flex flex-col items-center w-20">
                                                            <div className="w-10 h-10 overflow-hidden rounded border border-gray-200 bg-white flex items-center justify-center">
                                                                <img
                                                                    src={getCoverImage(prod)}
                                                                    alt={prod.title}
                                                                    className="w-full h-full object-contain"
                                                                    loading="lazy"
                                                                />
                                                            </div>
                                                            <span className="mt-1 text-[10px] text-center text-gray-700 line-clamp-2 break-words w-full">{prod.title || prod.name}</span>
                                                        </div>
                                                    ))}
                                                    {quote.products.length > 5 &&
                                                        <span className="pl-2 text-xs text-gray-400 self-center align-middle">
                                                            +{quote.products.length - 5} more
                                                        </span>
                                                    }
                                                </div>
                                            )}
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-gray-500 text-center py-6">No existing quotes found.</p>
                                )}
                            </div>
                            <button
                                onClick={handleSaveToExistingQuotes}
                                disabled={selectedQuoteIds.length === 0}
                                className={`w-full py-3 rounded-lg text-white font-medium transition ${selectedQuoteIds.length > 0 ? 'bg-[#0079C2] hover:bg-[#005a91]' : 'bg-gray-300 cursor-not-allowed'
                                    }`}
                            >
                                Save
                            </button>
                        </div>
                    </div>
                )}
            </Modal>

            {/* Create Quote Modal */}
            <Modal
                isOpen={isCreateQuoteModalOpen}
                onClose={() => setIsCreateQuoteModalOpen(false)}
                title="Create New Quote"
            >
                <div className="flex flex-col gap-4">
                    <div>
                        <label htmlFor="quoteName" className="block text-sm font-medium text-gray-700 mb-1">
                            Quote Name
                        </label>
                        <input
                            type="text"
                            id="quoteName"
                            value={newQuoteName}
                            onChange={(e) => setNewQuoteName(e.target.value)}
                            placeholder="Enter quote name..."
                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0079C2] focus:border-[#0079C2] outline-none"
                        />
                    </div>
                    <button
                        onClick={handleCreateQuote}
                        disabled={isLoading || !newQuoteName.trim()}
                        className={`w-full py-3 rounded-lg text-white font-medium transition ${!isLoading && newQuoteName.trim() ? 'bg-[#0079C2] hover:bg-[#005a91]' : 'bg-gray-300 cursor-not-allowed'
                            }`}
                    >
                        {isLoading ? 'Creating...' : 'Create & Add Product'}
                    </button>
                </div>
            </Modal>

            <Head>
                {/* The title will be managed by Inertia */}
                <title>{data.seo_title ?? 'Product Detail'}</title>

                {/* Add your dynamic SEO meta tags */}
                <meta name="description" content={data.seo_description} />
                <meta name="keywords" content={data.seo_keywords} />

                {/* You can even add Open Graph tags for social sharing */}
                <meta property="og:title" content={data.seo_title ?? 'Product Detail'} />
                <meta property="og:description" content={data.seo_description} />
            </Head>
            <Header />

            <main>
                <section
                    id="detail-product"
                    className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 space-y-10"
                >
                    {/* Breadcrumb */}
                    <nav className="text-xs md:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                        <ol className="flex flex-wrap items-center gap-1">
                            <li>
                                <Link
                                    href='/home'
                                    className="hover:text-[#0079C2]"
                                >Home</Link>
                            </li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li>
                                <Link
                                    href='/products'
                                    className="hover:text-[#0079C2]"
                                >Products</Link>
                            </li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li className="text-gray-700">{data.title}</li>
                        </ol>
                    </nav>

                    <div className="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10 items-start">
                        {/* Image Section */}
                        <div className="lg:col-span-2 space-y-4">
                            <div ref={imageContainerRef} className="rounded-xl border border-gray-200 bg-gray-50">
                                <ImageZoom
                                    src={productImages[activeImageIndex]}
                                    alt={data.title}
                                    className="w-full h-full max-h-[420px]"
                                />
                            </div>
                            <div className="grid grid-cols-4 gap-3">
                                {productImages.map((imageUrl, index) => {
                                    const isActive = index === activeImageIndex;
                                    return (
                                        <button
                                            key={`${imageUrl}-${index}`}
                                            type="button"
                                            onClick={() => setActiveImageIndex(index)}
                                            className={`group relative overflow-hidden rounded-lg border bg-gray-50 transition ${isActive
                                                ? 'border-[#0079C2] ring-2 ring-[#0079C2]/40'
                                                : 'border-gray-200 hover:border-[#0079C2]/60'
                                                }`}
                                        >
                                            <img
                                                src={imageUrl}
                                                alt={`${data.title} thumbnail ${index + 1}`}
                                                className="w-full h-20 md:h-24 object-cover transition-transform duration-200 ease-out group-hover:scale-110"
                                                loading="lazy"
                                            />
                                        </button>
                                    );
                                })}
                            </div>
                        </div>

                        {/* Main detail section */}
                        <div className="lg:col-span-3 space-y-6">
                            <div>
                                <p className="text-xs  tracking-wide font-inter font-light mb-1">
                                    {data.brand_name}
                                </p>
                                <h1 className="font-inter font-semibold text-3xl md:text-4xl lg:text-5xl text-[#232323] mb-2">
                                    {data.title}
                                </h1>
                            </div>
                            <div className="space-y-3">
                                {/* Harga: show logic for login and is_sign_up_for_pricing */}
                                {data.is_sign_up_for_pricing ? (
                                    logged ? (
                                        <p className="text-md md:text-xl font-semibold font-inter">
                                            {formatPrice(data.base_price)}
                                        </p>
                                    ) : (
                                        <p className="text-md  font-semibold font-inter text-black">
                                            Sign in for your Pricing
                                        </p>
                                    )
                                ) : (
                                    <p className="text-md md:text-xl font-semibold font-inter">
                                        {formatPrice(data.base_price)}
                                    </p>
                                )}
                            </div>
                            {/* Show Sign In button only if is_sign_up_for_pricing=true and not logged in */}
                            {data.is_sign_up_for_pricing && !logged && (
                                <div className="flex flex-wrap gap-3">
                                    <button
                                        type="button"
                                        className="inline-flex items-center justify-center rounded-xl bg-[#0079C2] px-20 py-3 text-sm font-light text-white hover:bg-[#005a91] transition"
                                        onClick={() => setIsLoginModalOpen(true)}
                                    >
                                        Sign In
                                    </button>
                                </div>
                            )}
                            <div className="flex flex-col gap-5 items-stretch max-w-xs w-full">
                                {logged && (
                                    <button
                                        type="button"
                                        onClick={handleAddToCart}
                                        disabled={isAddingToCart}
                                        className={`inline-flex items-center justify-center rounded-sm bg-[#5FC3FF] px-10 py-3 text-sm font-normal text-white cursor-pointer hover:shadow-xl transition w-full ${isAddingToCart ? 'opacity-70 cursor-wait' : ''}`}
                                    >
                                        {isAddingToCart ? 'Adding...' : 'Add To Cart'}
                                    </button>
                                )}

                                {logged && (
                                    <button
                                        type="button"
                                        onClick={handleAddToQuoteClick}
                                        className="inline-flex items-center justify-center rounded-sm bg-[#0079C2] border border-[#0079C2] px-8 py-3 text-sm font-normal text-white cursor-pointer hover:shadow-xl transition w-full"
                                    >
                                        Add To Quote
                                    </button>
                                )}
                            </div>

                            {/* Key Feature */}
                            <div>
                                <div className="font-inter font-bold text-lg mb-2">
                                    Key Feature
                                </div>
                                <div className="font-poppins font-normal text-md">
                                    <div dangerouslySetInnerHTML={{ __html: data.key_feature }} />
                                </div>
                            </div>
                            <hr />
                            {/* Product Overview */}
                            <div>
                                <div className="font-inter font-bold text-lg mb-2">
                                    Product Overview
                                </div>
                                <div className="font-poppins font-normal text-md">
                                    <div dangerouslySetInnerHTML={{ __html: data.product_overview }} />
                                </div>
                            </div>
                            <hr />
                            {/* Main Features */}
                            <div>
                                <div className="font-inter font-bold text-lg mb-2">
                                    Main Features
                                </div>
                                <div className="font-poppins font-normal text-md">
                                    <div dangerouslySetInnerHTML={{ __html: data.main_feature }} />
                                </div>
                            </div>
                            <hr />
                            {/* Information */}
                            <div>
                                <div className="font-inter font-bold text-lg mb-2">
                                    Information
                                </div>
                                <div className="font-poppins font-normal text-md">
                                    <div dangerouslySetInnerHTML={{ __html: data.information }} />
                                </div>
                            </div>
                            <hr />

                            {/* Accordion */}
                            <div className="mt-4 space-y-3">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Specification
                                </div>
                                {accordionItems.map((item) => {
                                    const isOpen = openAccordion === item.id;
                                    return (
                                        <div key={item.id}>
                                            <button
                                                type="button"
                                                onClick={() => handleToggleAccordion(item.id)}
                                                className="w-full flex flex-row items-center gap-2 py-3 text-left"
                                                aria-expanded={isOpen}
                                            >
                                                <span className="inline-flex text-2xl items-center justify-center text-[#0079C2]">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth="2"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        className={`h-6 w-6 transition-transform ${isOpen ? 'rotate-180' : ''}`}
                                                    >
                                                        <polyline points="6 9 12 15 18 9" />
                                                    </svg>
                                                </span>
                                                <span className="text-sm font-semibold text-[#232323]">
                                                    {item.title}
                                                </span>
                                            </button>
                                            {isOpen && (
                                                <div className="px-4 pb-4 font-poppins font-normal text-md">
                                                    {item.id === 'specs' ? (
                                                        <dl className="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm text-gray-700">
                                                            {item.content.map((spec) => (
                                                                <div key={spec.label} className="flex flex-col">
                                                                    <dt className="text-gray-500">{spec.label}</dt>
                                                                    <dd className="font-medium text-[#232323]">
                                                                        {spec.value}
                                                                    </dd>
                                                                </div>
                                                            ))}
                                                        </dl>
                                                    ) : (
                                                        <div
                                                            className="space-y-2 text-sm text-gray-700"
                                                            dangerouslySetInnerHTML={{ __html: item.content }}
                                                        />
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </section>

                <section className='container mx-auto px-10 md:px-20'>
                    <FeaturedProductsSection
                        products={featuredProducts}
                        title="Other Products"
                        titleSize="text-4xl font-bebas-neue"
                        slidesPerView={5}
                        sectionId="other-product"
                    />
                </section>
            </main>
            <Footer />
        </div>
    );
}
