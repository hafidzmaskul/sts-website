import React, { useState, useRef, useEffect, useMemo } from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import FeaturedProductsSection from '../components/FeaturedProductsSection';

// Define TOTAL_PRODUCTS at module scope
const TOTAL_PRODUCTS = 100;

// 添加图片缩放组件
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
                className="w-full h-full object-cover"
                loading="lazy"
            />
            {isZoomed && (
                <div
                    className="absolute inset-0 pointer-events-none"
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

// Dummy base products array for buildProducts to work, if not defined elsewhere
const baseProducts = [
    {
        id: 1,
        name: "IP Camera 8MP",
        price: "Rp 1.250.000",
        image: "/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png",
        category: "Security Systems"
    },
    {
        id: 2,
        name: "WizSense Series",
        price: "Rp 2.500.000",
        image: "/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg",
        category: "Security Systems"
    },
    {
        id: 3,
        name: "Outdoor Camera",
        price: "Rp 1.900.000",
        image: "/assets/dummmy/fecd358a1b56bef6f0106d5df4cf057608b437f0.png",
        category: "Security Systems"
    }
];

const buildProducts = (total) => {
    const products = [];

    for (let index = 0; index < total; index += 1) {
        const base = baseProducts[index % baseProducts.length];
        const badge = index % 3 === 0 ? 'New' : index % 3 === 1 ? 'Best Seller' : 'Limited';

        products.push({
            ...base,
            id: index + 1,
            name: `${base.name} ${index + 1}`,
            badge,
        });
    }

    return products;
};
export default function ProductDetail({ product, products = [] }) {
    const allProducts = useMemo(() => buildProducts(TOTAL_PRODUCTS), []);
    const featuredProducts = useMemo(
        () => allProducts.slice(0, 8),
        [allProducts],
    );
    if (!product) {
        return null;
    }

    const productImages = [
        '/assets/dummmy/427e6a38b9f21cabf9f278b8d278b378ad645ab1.png',
        '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        '/assets/dummmy/fecd358a1b56bef6f0106d5df4cf057608b437f0.png',
    ];

    const [activeImageIndex, setActiveImageIndex] = useState(0);
    const [openAccordion, setOpenAccordion] = useState('description');

    const dummyProduct = {
        name: product.name ?? 'Smart CCTV Camera Pro',
        category: 'Security Systems',
        series: 'Model STS-CCTV-100',
        badge: 'Best Seller',
        price: product.price ?? 'Rp 1.250.000',
        availability: 'Ready Stock',
        description: 'Smart CCTV Camera Pro adalah solusi kamera keamanan pintar yang dirancang untuk kebutuhan bisnis dan kantor modern. Dilengkapi fitur monitoring realtime, koneksi jaringan stabil, dan kualitas gambar tajam sepanjang hari.',
        highlights: [
            'Resolusi tinggi dengan dukungan penglihatan malam.',
            'Terintegrasi dengan sistem keamanan dan jaringan kantor.',
            'Pemasangan fleksibel untuk area indoor maupun outdoor.',
        ],
        specs: [
            { label: 'Kategori', value: 'CCTV & Security Systems' },
            { label: 'Tipe Produk', value: 'Smart CCTV Camera Pro' },
            { label: 'Konektivitas', value: 'IP Network' },
            { label: 'Resolusi', value: '4MP Full HD' },
            { label: 'Daya', value: 'PoE / DC 12V' },
        ],
    };

    const accordionItems = [
        {
            id: 'description',
            title: 'Deskripsi Produk',
            content: (
                <div className="space-y-3 text-sm text-gray-700">
                    <p>
                        {dummyProduct.description}
                    </p>
                    <ul className="list-disc list-inside space-y-1">
                        {dummyProduct.highlights.map((item) => (
                            <li key={item}>{item}</li>
                        ))}
                    </ul>
                </div>
            ),
        },
        {
            id: 'specs',
            title: 'Spesifikasi Teknis',
            content: (
                <dl className="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm text-gray-700">
                    {dummyProduct.specs.map((spec) => (
                        <div key={spec.label} className="flex flex-col">
                            <dt className="text-gray-500">{spec.label}</dt>
                            <dd className="font-medium text-[#232323]">
                                {spec.value}
                            </dd>
                        </div>
                    ))}
                </dl>
            ),
        },
        {
            id: 'warranty',
            title: 'Garansi & Dukungan',
            content: (
                <div className="space-y-2 text-sm text-gray-700">
                    <p>
                        Produk ini dilengkapi garansi resmi dan dukungan teknis dari tim STS untuk membantu kebutuhan instalasi maupun pemeliharaan.
                    </p>
                    <p>
                        Untuk informasi lebih lanjut atau konsultasi solusi keamanan, silakan hubungi tim sales kami.
                    </p>
                </div>
            ),
        },
    ];

    const handleToggleAccordion = (sectionId) => {
        setOpenAccordion((current) => (current === sectionId ? null : sectionId));
    };


    return (
        <div className="min-h-screen flex flex-col ">
            <Head title={product.name ?? 'Product Detail'} />
            <Header />

            <main className="">
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
                                >
                                    Home
                                </Link>
                            </li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li>
                                <Link
                                    href='/products'
                                    className="hover:text-[#0079C2]"
                                >
                                    Products
                                </Link>
                            </li>
                            <li className="mx-1 text-gray-400">/</li>
                            <li className="text-gray-700">
                                {dummyProduct.name}
                            </li>
                        </ol>
                    </nav>

                    <div className="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10 items-start">
                        <div className="lg:col-span-2 space-y-4">
                            {/* 使用新的ImageZoom组件替换原来的图片 */}
                            <div className="rounded-xl border border-gray-200 bg-gray-50">
                                <ImageZoom
                                    src={productImages[activeImageIndex]}
                                    alt={dummyProduct.name}
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
                                                alt={`${dummyProduct.name} thumbnail ${index + 1}`}
                                                className="w-full h-20 md:h-24 object-cover transition-transform duration-200 ease-out group-hover:scale-110"
                                                loading="lazy"
                                            />
                                        </button>
                                    );
                                })}
                            </div>
                        </div>

                        <div className="lg:col-span-3 space-y-6">
                            <div>
                                <p className="text-xs  tracking-wide font-inter font-light mb-1">
                                    {dummyProduct.category}
                                </p>
                                <h1 className="font-inter font-semibold text-3xl md:text-4xl lg:text-5xl text-[#232323] mb-2">
                                    {dummyProduct.name}
                                </h1>
                            </div>



                            <div className="space-y-3">
                                <p className="text-md md:text-xl font-semibold font-inter">
                                    {dummyProduct.price}
                                </p>
                            </div>

                            <div className="flex flex-wrap gap-3">
                                <button
                                    type="button"
                                    className="inline-flex items-center justify-center rounded-xl bg-[#0079C2] px-20 py-3 text-sm font-light text-white hover:bg-[#005a91] transition"
                                >
                                    Sign In
                                </button>
                            </div>
                            <div className="">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Key Feature
                                </div>
                                <ul className="list-disc list-inside space-y-1 font-poppins font-normal text-md">
                                    <li>8-MP 1/2.7 CMOS image sensor, low luminance, and high definition image</li>
                                    <li>Outputs max. 8 MP (3840 × 2160) at 25/30 fps</li>
                                    <li>H.265 codec, high compression rate, ultra-low bit rate</li>
                                </ul>
                            </div>
                            <hr />
                            <div className="">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Product Overview
                                </div>
                                <p className="font-poppins font-normal text-md">
                                    With advanced deep learning algorithm, Dahua WizSense 3 Series network camera supports intelligent functions, such as perimeter protection and smart motion detection. With starlight technology, this series camera provides a better image effect in the condition of low illuminance.
                                </p>
                            </div>
                            <hr />
                            <div className="">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Main Features
                                </div>
                                <ul className="list-disc list-inside space-y-1 font-poppins font-normal text-md">
                                    <li>8-MP 1/2.7" CMOS image sensor, low luminance, and high definition image</li>
                                    <li>Outputs max. 8 MP (3840 × 2160) at 25/30 fps</li>
                                    <li>H.265 codec, high compression rate, ultra-low bit rate</li>
                                    <li>Built-in multi-core light, the max. IR distance is 50 m and the max. warm light distance is 40 m</li>
                                    <li>ROI, SMART H.264+/H.265+, AI H.264/H.265, flexible coding, applicable to various bandwidth and storage environments</li>
                                    <li>Rotation mode, WDR, 3D NR, HLC, BLC, digital watermarking, applicable to various monitoring scenes</li>
                                    <li>Intelligent monitoring: Intrusion, tripwire (the two function support the classification and accurate detection of vehicle and human)</li>
                                    <li>Abnormality detection - Motion detection, video tampering, scene changing, audio detection, no SD card, SD card full, SD card error, network disconnection, IP conflict, illegal access, and voltage detection</li>
                                    <li>Supports max. 512 G Micro SD card; built-in Mic</li>
                                    <li>12 VDC/PoE power supply</li>
                                </ul>
                            </div>
                            <hr />
                            <div className="">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Information
                                </div>
                                <ul className="list-none list-inside space-y-1 font-poppins font-normal text-md">
                                    <li>Category : IP Cameras, Products, Turret Cameras, Video Surveillance</li>
                                    <li>EAN Code : 6923172576736</li>


                                </ul>
                            </div>
                            <hr />

                            <div className="mt-4 space-y-3">
                                <div className="font-inter font-bold text-lg mb-2">
                                    Specification
                                </div>
                                {accordionItems.map((item) => {
                                    const isOpen = openAccordion === item.id;

                                    return (
                                        <div
                                            key={item.id}
                                            className=""
                                        >
                                            <button
                                                type="button"
                                                onClick={() => handleToggleAccordion(item.id)}
                                                className="w-full flex flex-row items-center gap-2  py-3 text-left"
                                                aria-expanded={isOpen}
                                            >
                                                <span className=" inline-flex text-2xl items-center justify-center   text-[#0079C2] ">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth="2"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        className={`h-6 w-6 transition-transform ${isOpen ? 'rotate-180' : ''
                                                            }`}
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
                                                    {item.content}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </section>

                <section className='container mmx-auto px-10 md:px-20'>
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
