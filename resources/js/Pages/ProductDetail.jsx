import React, { useState, useRef, useMemo, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import FeaturedProductsSection from '../components/FeaturedProductsSection';
import LoginModal from '../components/LoginModal';
import Toast from '../components/Toast';

const formatPrice = (price) => {
    if (!price && price !== 0) {
        return '-';
    }
    const parsedPrice = parseFloat(String(price).replace(/[^\d.-]/g, ''));
    if (Number.isNaN(parsedPrice)) {
        return '-';
    }
    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP',
        maximumFractionDigits: 0,
    }).format(parsedPrice);
};

// Helper function to get product price with priority: calculated_price -> special_price -> base_price
const getProductPrice = (product) => {
    if (product.calculated_price !== null && product.calculated_price !== undefined) {
        return product.calculated_price;
    }
    if (product.special_price !== null && product.special_price !== undefined) {
        return product.special_price;
    }
    return product.base_price;
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

const Modal = ({ isOpen, onClose, title, children, size = 'md' }) => {
    if (!isOpen) return null;
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

export default function ProductDetail({ product, products = [], logged, is_guest: isGuest = false, variants = [] }) {
    const [activeProduct, setActiveProduct] = useState(product);

    useEffect(() => {
        setActiveProduct(product);
    }, [product]);

    // Check if any category has name 'Discontinued'
    const isDiscontinued = Array.isArray(activeProduct?.categories)
        ? activeProduct.categories.some(cat => (cat?.name || '').toLowerCase() === 'discontinued')
        : false;

    const [isLoginModalOpen, setIsLoginModalOpen] = useState(false);

    // Toast state
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    // CTA: Product Request modal for is_cta
    const [isProductRequestModalOpen, setIsProductRequestModalOpen] = useState(false);
    const [requestProductLoading, setRequestProductLoading] = useState(false);
    const [requestProductName, setRequestProductName] = useState('');
    const [requestProductEmail, setRequestProductEmail] = useState('');
    const [requestProductPhone, setRequestProductPhone] = useState('');
    const [requestProductMessage, setRequestProductMessage] = useState('');

    // Quote Builder States
    const [isQuoteOptionModalOpen, setIsQuoteOptionModalOpen] = useState(false);
    const [isExistingQuoteModalOpen, setIsExistingQuoteModalOpen] = useState(false);
    const [isCreateQuoteModalOpen, setIsCreateQuoteModalOpen] = useState(false);
    const [existingQuotes, setExistingQuotes] = useState([]);
    const [selectedQuoteIds, setSelectedQuoteIds] = useState([]);
    const [newQuoteName, setNewQuoteName] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    // Quantity state for Cart and Quote Builder
    const [quantity, setQuantity] = useState(1);

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

    const data = activeProduct;

    // SAFE access for images (api returns image_url directly)
    const productImages = useMemo(() => {
        const imagesList = [];
        const formatImg = (img) => img.image_url || (img.image_path?.startsWith('/') ? img.image_path : `/storage/${img.image_path}`);

        if (Array.isArray(data.images) && data.images.length > 0) {
            const sortedMain = [...data.images].sort((a, b) => (a.sequence || 0) - (b.sequence || 0));
            sortedMain.forEach(img => {
                imagesList.push(formatImg(img));
            });
        }

        if (Array.isArray(variants)) {
            variants.forEach(v => {
                if (v.id !== data.id && Array.isArray(v.images)) {
                    const sortedV = [...v.images].sort((a, b) => (a.sequence || 0) - (b.sequence || 0));
                    sortedV.forEach(img => {
                        const formatted = formatImg(img);
                        if (!imagesList.includes(formatted)) {
                            imagesList.push(formatted);
                        }
                    });
                }
            });
        }

        if (imagesList.length > 0) {
            return imagesList;
        }

        return ['/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png'];
    }, [data.images, data.id, variants]);

    const [activeImageIndex, setActiveImageIndex] = useState(0);
    const imageContainerRef = useRef(null);
    const [isAddingToCart, setIsAddingToCart] = useState(false);
    const [openAccordion, setOpenAccordion] = useState('description');

    // Get price with priority: calculated_price -> special_price -> base_price
    const displayPrice = getProductPrice(data);

    // Set specs with price conditional, reference latest API shape
    const specs = useMemo(() => [
        { label: 'Brand', value: data.brand?.name || '-' },
        { label: 'Status', value: data.status === 'active' ? 'Ready Stock' : 'Unavailable' },
        {
            label: 'Harga',
            value:
                data.is_sign_up_for_pricing
                    ? (logged
                        ? formatPrice(displayPrice)
                        : <span className="italic text-gray-400">Login untuk melihat harga</span>)
                    : formatPrice(displayPrice),
        },
        { label: 'Exclusive', value: data.is_exclusive ? 'Yes' : 'No' },
    ], [
        data.brand?.name,
        data.status,
        displayPrice,
        data.is_exclusive,
        data.is_sign_up_for_pricing,
        logged
    ]);

    const accordionItems = useMemo(() => [
        {
            id: 'description',
            title: 'Product Description',
            content: data.product_overview ? data.product_overview : '',
        },
        {
            id: 'specs',
            title: 'Technical Specification',
            content: specs,
        },
        {
            id: 'information',
            title: 'Additional Information',
            content: data.information ? data.information : '',
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
                    quantity: quantity
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
            }, 1000);
        } catch (error) {
            setToast({
                show: true,
                message: 'Failed to add product to some quotes.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

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
                        quantity: quantity
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
            setToast({
                show: true,
                message: 'Failed to create new quote.',
                type: 'error'
            });
        } finally {
            setIsLoading(false);
        }
    };

    // Product Request CTA
    const handleRequestProduct = async () => {
        if (!requestProductName.trim() || !requestProductEmail.trim() || !requestProductPhone.trim()) {
            setToast({
                show: true,
                message: 'Isi form lengkap (nama, email, phone) terlebih dahulu.',
                type: 'error'
            });
            return;
        }
        setRequestProductLoading(true);
        try {
            await axios.post('/web/product-requests', {
                product_id: data.id,
                name: requestProductName,
                email: requestProductEmail,
                phone: requestProductPhone,
                message: requestProductMessage,
            });
            setIsProductRequestModalOpen(false);
            setRequestProductName('');
            setRequestProductEmail('');
            setRequestProductPhone('');
            setRequestProductMessage('');
            setToast({ show: true, message: 'Thank you. Your product request has been submitted successfully.', type: 'success' });
        } catch (err) {
            setToast({
                show: true,
                message: err?.response?.data?.message || 'Failed to submit product request.',
                type: 'error'
            });
        } finally {
            setRequestProductLoading(false);
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
        } catch (e) { }
    };

    const handleAddToCart = async () => {
        setIsAddingToCart(true);
        animateAddToCart();
        try {
            await axios.post('/web/cart', { product_id: data.id, quantity: quantity });
            setToast({ show: true, message: 'Product added to cart', type: 'success' });

            // Handle guest first-time refresh
            if (isGuest) {
                const hasAddedBefore = localStorage.getItem('guest_has_added_to_cart');
                if (!hasAddedBefore) {
                    localStorage.setItem('guest_has_added_to_cart', 'true');
                    window.location.reload();
                    return;
                }
            }

            await refreshCart();
        } catch (error) {
            setToast({ show: true, message: error?.response?.data?.message || 'Failed to add to cart', type: 'error' });
        } finally {
            setIsAddingToCart(false);
        }
    };

    // Featured products. Use image_url if available.
    const featuredProducts = useMemo(() => {
        if (products.length === 0) return [];
        return products
            .filter((p) => p.id !== data.id)
            .slice(0, 8)
            .map((product, index) => {
                let image = '/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png';
                if (Array.isArray(product.images) && product.images.length > 0) {
                    const imgObj = product.images[0];
                    image = imgObj.image_url || (imgObj.image_path?.startsWith('/') ? imgObj.image_path : `/storage/${imgObj.image_path}`);
                }
                // Get price with priority: calculated_price -> special_price -> base_price
                const productPrice = getProductPrice(product);
                const badge = index % 3 === 0 ? 'New' : index % 3 === 1 ? 'Best Seller' : 'Limited';

                return {
                    id: product.id,
                    title: product.title || product.name || '',
                    price: productPrice,
                    image,
                    badge: product.badge || badge,
                };
            });
    }, [products, data.id]);

    // Helper for product image url (for quote thumbnails)
    const getCoverImage = (product) => {
        if (Array.isArray(product.images) && product.images.length > 0) {
            const sorted = [...product.images].sort((a, b) => (a.sequence || 0) - (b.sequence || 0));
            const imgObj = sorted[0];
            if (imgObj?.image_url) return imgObj.image_url;
            const imagePath = imgObj.image_path;
            if (imagePath?.startsWith("http")) {
                return imagePath;
            }
            return imagePath ? `/storage/${imagePath}` : "/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png";
        }
        return "/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png";
    };

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

            {/* Product Request Modal CTA */}
            {!isDiscontinued && (
                <Modal
                    isOpen={isProductRequestModalOpen}
                    onClose={() => setIsProductRequestModalOpen(false)}
                    title="Request This Product"
                >
                    <div className="flex flex-col gap-4">
                        <div>
                            <label htmlFor="requestProductName" className="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input
                                id="requestProductName"
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0079C2] focus:border-[#0079C2] outline-none"
                                value={requestProductName}
                                onChange={e => setRequestProductName(e.target.value)}
                                placeholder="Enter your name"
                            />
                        </div>
                        <div>
                            <label htmlFor="requestProductEmail" className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                id="requestProductEmail"
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0079C2] focus:border-[#0079C2] outline-none"
                                type="email"
                                value={requestProductEmail}
                                onChange={e => setRequestProductEmail(e.target.value)}
                                placeholder="Your email"
                            />
                        </div>
                        <div>
                            <label htmlFor="requestProductPhone" className="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input
                                id="requestProductPhone"
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0079C2] focus:border-[#0079C2] outline-none"
                                type="tel"
                                value={requestProductPhone}
                                onChange={e => setRequestProductPhone(e.target.value)}
                                placeholder="Your phone"
                            />
                        </div>
                        <div>
                            <label htmlFor="requestProductMessage" className="block text-sm font-medium text-gray-700 mb-1">Message (optional)</label>
                            <textarea
                                id="requestProductMessage"
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0079C2] focus:border-[#0079C2] outline-none"
                                value={requestProductMessage}
                                onChange={e => setRequestProductMessage(e.target.value)}
                                placeholder="Message or request detail..."
                            />
                        </div>
                        <button
                            onClick={handleRequestProduct}
                            disabled={requestProductLoading}
                            className={`w-full py-3 rounded-lg text-white font-medium transition ${requestProductLoading ? 'bg-gray-300 cursor-wait' : 'bg-[#0079C2] hover:bg-[#005a91]'}`}
                        >
                            {requestProductLoading ? 'Sending...' : 'Submit Request'}
                        </button>
                    </div>
                </Modal>
            )}

            {/* Quote Option Modal */}
            {!isDiscontinued && (
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
            )}

            {/* Existing Quote Modal */}
            {!isDiscontinued && (
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
                            <div className="w-full md:w-2/5 bg-gray-50 rounded-lg p-4 flex flex-col items-center md:items-start justify-center border border-gray-200">
                                <img
                                    src={productImages[activeImageIndex]}
                                    alt={data.title}
                                    className="w-32 h-32 object-contain mb-3 border rounded-lg bg-white"
                                />
                                <h3 className="text-xl font-semibold mb-2 text-[#232323]">{data.title}</h3>
                                <p className="text-gray-500 text-sm mb-1">{data.brand?.name}</p>
                                <p className="text-[#0079C2] text-lg font-bold mb-2">
                                    {data.is_sign_up_for_pricing && !logged
                                        ? <span className="italic text-gray-400">Login untuk melihat harga</span>
                                        : formatPrice(displayPrice)
                                    }
                                </p>
                            </div>
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
            )}

            {/* Create Quote Modal */}
            {!isDiscontinued && (
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
            )}

            <Head title={data.seo_title || data.title || 'Product Detail'}>
                <meta name="description" content={data.seo_description || data.product_overview || ''} />
                <meta name="keywords" content={data.seo_keywords || ''} />
                <meta property="og:title" content={data.seo_title || data.title || 'Product Detail'} />
                <meta property="og:description" content={data.seo_description || data.product_overview || ''} />
                {productImages && productImages.length > 0 && (
                    <meta property="og:image" content={productImages[0]} />
                )}
            </Head>
            <Header />

            <main>
                <section
                    id="detail-product"
                    className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 space-y-10"
                >
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
                            {Array.isArray(data.categories) && data.categories[0] && (
                                <>
                                    <li className="mx-1 text-gray-400">/</li>
                                    <li>
                                        <Link
                                            href={`/category/${data.categories[0].slug}`}
                                            className="hover:text-[#0079C2]"
                                        >
                                            {data.categories[0].name}
                                        </Link>
                                    </li>
                                </>
                            )}
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
                                    className="w-full h-full "
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
                                                className="w-full object-cover transition-transform duration-200 ease-out group-hover:scale-110"
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
                                    {data.brand?.name}
                                </p>
                                <h1 className="font-inter font-semibold text-3xl md:text-4xl lg:text-5xl text-[#232323] mb-2">
                                    {data.title}
                                </h1>
                                <h1 className="font-inter font-semibold text-md  text-[#232323] mb-2">
                                    {data.sku}
                                </h1>
                            </div>
                            <div className="space-y-3">
                                <p className="text-sm font-medium">
                                    Stock: {data.status === 'active' ? (
                                        <span className="text-green-600">Ready Stock</span>
                                    ) : (
                                        <span className="text-red-600">Unavailable</span>
                                    )}
                                </p>
                                {data.is_sign_up_for_pricing ? (
                                    logged ? (
                                        <p className="text-md md:text-xl font-semibold font-inter">
                                            {formatPrice(displayPrice)}
                                        </p>
                                    ) : (
                                        <p className="text-md  font-semibold font-inter text-black">
                                            Sign in for your Pricing
                                        </p>
                                    )
                                ) : (
                                    <p className="text-md md:text-xl font-semibold font-inter">
                                        {formatPrice(displayPrice)}
                                    </p>
                                )}
                            </div>

                            {/* Variant Selector Dropdown */}
                            {variants && variants.length > 1 && (
                                <div className="space-y-2 mt-4">
                                    <label htmlFor="variant-select" className="block text-sm font-medium text-gray-700">
                                        Select Variant:
                                    </label>
                                    <div className="relative max-w-xs">
                                        <select
                                            id="variant-select"
                                            value={activeProduct.id}
                                            onChange={(e) => {
                                                const selectedId = parseInt(e.target.value);
                                                const selectedVariant = variants.find(v => v.id === selectedId);
                                                if (selectedVariant) {
                                                    setActiveProduct(selectedVariant);
                                                    if (selectedVariant.slug) {
                                                        window.history.pushState(null, '', `/products/${selectedVariant.slug}`);
                                                    }
                                                    // Automatically select the first image of the variant if it exists
                                                    if (Array.isArray(selectedVariant.images) && selectedVariant.images.length > 0) {
                                                        const firstImage = selectedVariant.images[0];
                                                        const formatted = firstImage.image_url || (firstImage.image_path?.startsWith('/') ? firstImage.image_path : `/storage/${firstImage.image_path}`);
                                                        const index = productImages.findIndex(img => img === formatted);
                                                        if (index !== -1) {
                                                            setActiveImageIndex(index);
                                                        }
                                                    }
                                                }
                                            }}
                                            className="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm font-medium text-gray-900 shadow-sm focus:border-[#0079C2] focus:outline-none focus:ring-1 focus:ring-[#0079C2] transition-colors appearance-none cursor-pointer"
                                        >
                                            {variants.map((variant) => (
                                                <option key={variant.id} value={variant.id}>
                                                    {!variant.parent_id
                                                        ? `${variant.title} (Default)`
                                                        : variant.title
                                                    }
                                                </option>
                                            ))}
                                        </select>
                                        <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                                            <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fillRule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clipRule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Quantity Input */}
                            {!isDiscontinued && (
                                <div className="flex items-center gap-4 mt-4">
                                    <span className="text-sm font-medium text-gray-700">Qty:</span>
                                    <div className="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button
                                            type="button"
                                            className="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition text-gray-600"
                                            onClick={() => setQuantity(Math.max(1, quantity - 1))}
                                        >
                                            -
                                        </button>
                                        <input
                                            type="number"
                                            min="1"
                                            className="w-16 text-center py-1 border-none outline-none focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none"
                                            value={quantity}
                                            onChange={(e) => {
                                                const val = parseInt(e.target.value);
                                                if (!isNaN(val) && val > 0) {
                                                    setQuantity(val);
                                                } else if (e.target.value === '') {
                                                    setQuantity('');
                                                }
                                            }}
                                            onBlur={(e) => {
                                                if (e.target.value === '' || parseInt(e.target.value) < 1) {
                                                    setQuantity(1);
                                                }
                                            }}
                                        />
                                        <button
                                            type="button"
                                            className="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition text-gray-600"
                                            onClick={() => setQuantity((prev) => (prev === '' ? 1 : prev + 1))}
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                            )}

                            {data.is_sign_up_for_pricing && !logged && !isDiscontinued && (
                                <div className="flex flex-wrap gap-3 mt-4">
                                    <button
                                        type="button"
                                        className="inline-flex items-center justify-center rounded-xl bg-[#0079C2] px-20 py-3 text-sm font-light text-white hover:bg-[#005a91] transition"
                                        onClick={() => setIsLoginModalOpen(true)}
                                    >
                                        Sign In
                                    </button>
                                </div>
                            )}

                            {/* Only show action buttons if not discontinued */}
                            {!isDiscontinued && (
                                <div className="flex flex-col gap-5 items-stretch max-w-xs w-full">
                                    <button
                                        type="button"
                                        onClick={handleAddToCart}
                                        disabled={isAddingToCart}
                                        className={`inline-flex items-center justify-center rounded-sm bg-[#5FC3FF] px-10 py-3 text-sm font-normal text-white cursor-pointer hover:shadow-xl transition w-full ${isAddingToCart ? 'opacity-70 cursor-wait' : ''}`}
                                    >
                                        {isAddingToCart ? 'Adding...' : 'Add To Cart'}
                                    </button>

                                    {logged && (
                                        <button
                                            type="button"
                                            onClick={handleAddToQuoteClick}
                                            className="inline-flex items-center justify-center rounded-sm bg-[#0079C2] border border-[#0079C2] px-8 py-3 text-sm font-normal text-white cursor-pointer hover:shadow-xl transition w-full"
                                        >
                                            Add To Quote
                                        </button>
                                    )}

                                    {/* CTA Button for Product Request */}
                                    {data.is_cta && (
                                        <button
                                            type="button"
                                            onClick={() => setIsProductRequestModalOpen(true)}
                                            className="inline-flex items-center justify-center rounded-sm bg-[#FFA723] border border-[#FFA723] px-8 py-3 text-sm font-medium text-white cursor-pointer hover:shadow-xl transition w-full"
                                        >
                                            Request Product CTA
                                        </button>
                                    )}
                                </div>
                            )}

                            {data.key_feature && (
                                <div>
                                    <div className="font-inter font-bold text-lg mb-2">
                                        Key Feature
                                    </div>
                                    <div className="font-poppins font-normal text-md">
                                        <div dangerouslySetInnerHTML={{ __html: data.key_feature }} />
                                    </div>
                                </div>
                            )}
                            <hr />
                            {data.product_overview && (
                                <div>
                                    <div className="font-inter font-bold text-lg mb-2">
                                        Product Overview
                                    </div>
                                    <div className="font-poppins font-normal text-md">
                                        <div dangerouslySetInnerHTML={{ __html: data.product_overview }} />
                                    </div>
                                </div>
                            )}
                            <hr />
                            {data.main_feature && (
                                <div>
                                    <div className="font-inter font-bold text-lg mb-2">
                                        Main Features
                                    </div>
                                    <div className="font-poppins font-normal text-md">
                                        <div dangerouslySetInnerHTML={{ __html: data.main_feature }} />
                                    </div>
                                </div>
                            )}
                            <hr />
                            {data.information && (
                                <div>
                                    <div className="font-inter font-bold text-lg mb-2">
                                        Information
                                    </div>
                                    <div className="font-poppins font-normal text-md">
                                        <div dangerouslySetInnerHTML={{ __html: data.information }} />
                                    </div>
                                </div>
                            )}
                            <hr />

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
