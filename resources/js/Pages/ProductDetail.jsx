import React, { useState, useRef, useMemo } from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import FeaturedProductsSection from '../components/FeaturedProductsSection';

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
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
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

export default function ProductDetail({ product, products = [] }) {
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
    const [openAccordion, setOpenAccordion] = useState('description');

    const specs = useMemo(() => [
        { label: 'Brand', value: data.brand_name || '-' },
        { label: 'Status', value: data.status === 'active' ? 'Ready Stock' : 'Unavailable' },
        { label: 'Harga', value: formatPrice(data.base_price) },
        { label: 'Exclusive', value: data.is_exclusive ? 'Yes' : 'No' },
    ], [data.brand_name, data.status, data.base_price, data.is_exclusive]);

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

    return (
        <div className="min-h-screen flex flex-col ">
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
                            <div className="rounded-xl border border-gray-200 bg-gray-50">
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
                                <p className="text-md md:text-xl font-semibold font-inter">
                                    {formatPrice(data.base_price)}
                                </p>
                            </div>
                            {data.is_sign_up_for_pricing && (
                                <div className="flex flex-wrap gap-3">
                                    <button
                                        type="button"
                                        className="inline-flex items-center justify-center rounded-xl bg-[#0079C2] px-20 py-3 text-sm font-light text-white hover:bg-[#005a91] transition"
                                    >
                                        Sign In
                                    </button>
                                </div>
                            )}

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
                                                                    <dd className="font-medium text-[#232323]">{spec.value}</dd>
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
