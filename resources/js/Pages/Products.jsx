import React, { useEffect, useMemo, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import ProductListingCard from '../components/ProductListingCard';
import FeaturedProductsSection from '../components/FeaturedProductsSection';
import 'swiper/css';

const sliderImages = [
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
];

const PRODUCT_PAGE_SIZE = 28;
const TOTAL_PRODUCTS = 100;

const baseProducts = [
    {
        id: 1,
        name: 'Smart CCTV Camera Pro',
        slug: 'smart-cctv-camera-pro',
        price: 1250000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-CCTV-100',
        categoryId: 'security-systems',
        subCategoryId: 'cctv',
    },
    {
        id: 2,
        name: 'Access Control Door Lock',
        slug: 'access-control-door-lock',
        price: 1850000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-ACCESS-210',
        categoryId: 'access-control',
        subCategoryId: 'door-lock',
    },
    {
        id: 3,
        name: 'Network Switch 24 Port',
        slug: 'network-switch-24-port',
        price: 2150000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-NET-24',
        categoryId: 'networking',
        subCategoryId: 'switch',
    },
    {
        id: 4,
        name: 'Smart Office Starter Kit',
        slug: 'smart-office-starter-kit',
        price: 3500000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-OFFICE-01',
        categoryId: 'smart-office',
        subCategoryId: 'office-kit',
    },
    {
        id: 5,
        name: 'IP Camera Outdoor 4K',
        slug: 'ip-camera-outdoor-4k',
        price: 2750000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-IP-4K',
        categoryId: 'security-systems',
        subCategoryId: 'ip-camera',
    },
    {
        id: 6,
        name: 'Video Door Phone Set',
        slug: 'video-door-phone-set',
        price: 2250000,
        image: '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
        brand: 'STS',
        series: 'Model STS-VDP-01',
        categoryId: 'access-control',
        subCategoryId: 'video-door-phone',
    },
];

const filterCategories = [
    {
        id: 'security-systems',
        name: 'Security Systems',
        children: [
            { id: 'cctv', name: 'CCTV Camera' },
            { id: 'ip-camera', name: 'IP Camera' },
        ],
    },
    {
        id: 'access-control',
        name: 'Access Control',
        children: [
            { id: 'door-lock', name: 'Door Lock' },
            { id: 'video-door-phone', name: 'Video Door Phone' },
        ],
    },
    {
        id: 'networking',
        name: 'Networking',
        children: [
            { id: 'switch', name: 'Switch' },
            { id: 'router', name: 'Router' },
        ],
    },
    {
        id: 'smart-office',
        name: 'Smart Office',
        children: [
            { id: 'office-kit', name: 'Office Kit' },
            { id: 'meeting-room', name: 'Meeting Room' },
        ],
    },
];

const formatPrice = (amount) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
}).format(amount);

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

export default function Products() {
    const allProducts = useMemo(() => buildProducts(TOTAL_PRODUCTS), []);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortBy, setSortBy] = useState('featured');
    const [expandedCategories, setExpandedCategories] = useState(
        filterCategories.map((category) => category.id),
    );
    const [selectedSubCategories, setSelectedSubCategories] = useState([]);
    const [visibleCount, setVisibleCount] = useState(PRODUCT_PAGE_SIZE);
    const [isInitialLoading, setIsInitialLoading] = useState(true);
    const [isLoadingMore, setIsLoadingMore] = useState(false);

    const featuredProducts = useMemo(
        () => allProducts.slice(0, 8),
        [allProducts],
    );

    const baseSlidesPerView = 6;
    const sliderProducts = allProducts.length > baseSlidesPerView
        ? allProducts
        : [...allProducts, ...allProducts];
    const loopEnabled = sliderProducts.length > baseSlidesPerView;

    useEffect(() => {
        const timeout = setTimeout(() => {
            setIsInitialLoading(false);
        }, 400);

        return () => clearTimeout(timeout);
    }, []);

    const filteredProducts = useMemo(() => {
        let products = allProducts;

        if (searchQuery.trim() !== '') {
            const query = searchQuery.trim().toLowerCase();
            products = products.filter((product) => (
                product.name.toLowerCase().includes(query)
                || product.brand.toLowerCase().includes(query)
                || product.series.toLowerCase().includes(query)
            ));
        }

        if (selectedSubCategories.length > 0) {
            products = products.filter(
                (product) => selectedSubCategories.includes(product.subCategoryId),
            );
        }

        const sorted = [...products];

        if (sortBy === 'price-asc') {
            sorted.sort((a, b) => a.price - b.price);
        } else if (sortBy === 'price-desc') {
            sorted.sort((a, b) => b.price - a.price);
        } else if (sortBy === 'name-asc') {
            sorted.sort((a, b) => a.name.localeCompare(b.name));
        }

        return sorted;
    }, [allProducts, searchQuery, selectedSubCategories, sortBy]);

    useEffect(() => {
        setVisibleCount(PRODUCT_PAGE_SIZE);
    }, [searchQuery, selectedSubCategories, sortBy]);

    const totalProducts = filteredProducts.length;
    const visibleProducts = filteredProducts.slice(0, visibleCount);

    const handleToggleCategory = (categoryId) => {
        setExpandedCategories((current) => (
            current.includes(categoryId)
                ? current.filter((id) => id !== categoryId)
                : [...current, categoryId]
        ));
    };

    const handleToggleSubCategory = (subCategoryId) => {
        setSelectedSubCategories((current) => (
            current.includes(subCategoryId)
                ? current.filter((id) => id !== subCategoryId)
                : [...current, subCategoryId]
        ));
    };

    const handleLoadMore = () => {
        if (visibleCount >= totalProducts) {
            return;
        }

        setIsLoadingMore(true);

        setTimeout(() => {
            setVisibleCount((current) => Math.min(
                current + PRODUCT_PAGE_SIZE,
                totalProducts,
            ));
            setIsLoadingMore(false);
        }, 500);
    };

    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Products" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-6 text-[#232323]">
                    STS Exclusive brand
                </h1>
                <section id="img-slider" className="py-10">
                    <Swiper
                        modules={[Autoplay]}
                        spaceBetween={16}
                        slidesPerView={1}
                        loop={loopEnabled}
                        autoplay={{
                            delay: 2500,
                            disableOnInteraction: false,
                        }}
                        breakpoints={{
                            640: {
                                slidesPerView: Math.min(3, baseSlidesPerView),
                            },
                            1024: {
                                slidesPerView: baseSlidesPerView,
                            },
                        }}
                        grabCursor
                    >
                        {sliderProducts.map((product, index) => {
                            const imageUrl = product.image
                                ?? sliderImages[index % sliderImages.length];
                            const title = product.name ?? 'Product';

                            return (
                                <SwiperSlide key={`${product.slug ?? product.id}-${index}`}>
                                    <div className=" overflow-hidden ">
                                        <Link
                                            // href={route('products.detail', product.slug)}
                                            className="block"
                                        >
                                            <img
                                                src={imageUrl}
                                                alt={title}
                                                className="w-full h-56 object-cover"
                                                loading="lazy"
                                            />
                                        </Link>
                                        <div className="p-3">
                                            <p className="text-sm font-medium text-[#232323] truncate">
                                                {title}
                                            </p>
                                        </div>
                                    </div>
                                </SwiperSlide>
                            );
                        })}
                    </Swiper>
                </section>
                <section>
                    <h1 className="font-bebas-neue text-4xl md:text-5xl mb-6 text-[#232323]">
                        Product or Brand Name
                    </h1>
                    <div
                        id="product-and-filter"
                        className="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-8 items-start"
                    >
                        <aside className="lg:col-span-1">
                            <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-4 md:p-6">
                                <div className="flex items-center justify-between">
                                    <h2 className="text-lg font-semibold text-[#232323]">
                                        Filter
                                    </h2>
                                    <div className="inline-flex h-9 w-9 items-center justify-center  text-[#0079C2]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width={16} height={16} viewBox="0 0 16 16"><path fill="currentColor" d="M6 1a3 3 0 0 0-2.83 2H0v2h3.17a3.001 3.001 0 0 0 5.66 0H16V3H8.83A3 3 0 0 0 6 1M5 4a1 1 0 1 1 2 0a1 1 0 0 1-2 0m5 5a3 3 0 0 0-2.83 2H0v2h7.17a3.001 3.001 0 0 0 5.66 0H16v-2h-3.17A3 3 0 0 0 10 9m-1 3a1 1 0 1 1 2 0a1 1 0 0 1-2 0"></path></svg>
                                    </div>
                                </div>

                                <div className="mt-4">
                                    <div className="relative">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(event) => setSearchQuery(event.target.value)}
                                            placeholder="Search products..."
                                            className="block w-full rounded-full border border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm text-[#232323] outline-none focus:border-[#0079C2]"
                                        />
                                        <button
                                            type="button"
                                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#0079C2]"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                className="h-4 w-4"
                                            >
                                                <path
                                                    fill="currentColor"
                                                    d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16a6.471 6.471 0 0 0 4.23-1.57l.27.28v.79L19 20.49L20.49 19Zm-6 0A4.5 4.5 0 1 1 14 9.5A4.505 4.505 0 0 1 9.5 14Z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div className="mt-6 space-y-4">
                                    {filterCategories.map((category) => {
                                        const isExpanded = expandedCategories.includes(category.id);

                                        return (
                                            <div
                                                key={category.id}
                                                className="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0"
                                            >
                                                <button
                                                    type="button"
                                                    onClick={() => handleToggleCategory(category.id)}
                                                    className="flex w-full items-center justify-between text-left text-sm font-medium text-[#232323]"
                                                >
                                                    <span>{category.name}</span>
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24"
                                                        className={`h-4 w-4 text-gray-500 transition-transform ${isExpanded ? 'rotate-180' : ''}`}
                                                    >
                                                        <path
                                                            fill="currentColor"
                                                            d="M7.41 8.58L12 13.17l4.59-4.59L18 10l-6 6l-6-6z"
                                                        />
                                                    </svg>
                                                </button>

                                                {isExpanded && (
                                                    <div className="mt-3 space-y-2">
                                                        {category.children.map((child) => {
                                                            const isChecked = selectedSubCategories.includes(
                                                                child.id,
                                                            );

                                                            return (
                                                                <label
                                                                    key={child.id}
                                                                    className="flex cursor-pointer items-center gap-2 text-sm text-gray-700"
                                                                >
                                                                    <input
                                                                        type="checkbox"
                                                                        className="h-4 w-4 rounded border-gray-300 text-[#0079C2]"
                                                                        checked={isChecked}
                                                                        onChange={() => handleToggleSubCategory(
                                                                            child.id,
                                                                        )}
                                                                    />
                                                                    <span>{child.name}</span>
                                                                </label>
                                                            );
                                                        })}
                                                    </div>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </aside>

                        <section className="lg:col-span-4">
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p className="text-sm text-gray-600">
                                    Showing{' '}
                                    <span className="font-semibold">
                                        {totalProducts === 0 ? 0 : 1}
                                    </span>
                                    -
                                    <span className="font-semibold">
                                        {Math.min(visibleCount, totalProducts)}
                                    </span>
                                    {' '}
                                    of{' '}
                                    <span className="font-semibold">{totalProducts}</span>
                                </p>

                                <div className="flex items-center gap-3">
                                    <span className="text-sm text-gray-600">
                                        Sort By
                                    </span>
                                    <div className="relative">
                                        <select
                                            value={sortBy}
                                            onChange={(event) => setSortBy(event.target.value)}
                                            className="appearance-none rounded-full border border-gray-300 bg-white py-2 pl-4 pr-10 text-sm text-[#232323] outline-none focus:border-[#0079C2]"
                                        >
                                            <option value="featured">Featured</option>
                                            <option value="price-asc">Price: Low to High</option>
                                            <option value="price-desc">Price: High to Low</option>
                                            <option value="name-asc">Name A-Z</option>
                                        </select>
                                        <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                className="h-4 w-4"
                                            >
                                                <path
                                                    fill="currentColor"
                                                    d="M7 10l5 5l5-5z"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {isInitialLoading ? (
                                <div className="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    {Array.from({ length: 8 }).map((_, index) => (
                                        <div
                                            key={index}
                                            className="animate-pulse rounded-xl border border-gray-200 bg-white p-4"
                                        >
                                            <div className="mb-4 h-40 rounded-lg bg-gray-200" />
                                            <div className="mb-2 h-3 w-2/3 rounded bg-gray-200" />
                                            <div className="mb-2 h-3 w-5/6 rounded bg-gray-200" />
                                            <div className="mt-4 h-8 w-1/2 rounded bg-gray-200" />
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <>
                                    {visibleProducts.length === 0 ? (
                                        <p className="mt-6 text-sm text-gray-600">
                                            No products match your filters.
                                        </p>
                                    ) : (
                                        <>
                                            <div className="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                                {visibleProducts.map((product) => (
                                                    <ProductListingCard
                                                        key={product.id}
                                                        image={product.image}
                                                        name={product.name}
                                                        brand={product.brand}
                                                        series={product.series}
                                                        badge={product.badge}
                                                        priceLabel={formatPrice(product.price)}
                                                    />
                                                ))}
                                            </div>

                                            <div className="mt-8 flex flex-col items-center justify-center space-y-3">
                                                <p className="text-sm text-gray-600 text-center">
                                                    Showing{' '}
                                                    <span className="font-semibold">
                                                        {Math.min(visibleCount, totalProducts)}
                                                    </span>
                                                    {' '}
                                                    of{' '}
                                                    <span className="font-semibold">
                                                        {totalProducts}
                                                    </span>
                                                </p>
                                                {visibleCount < totalProducts && (
                                                    <button
                                                        type="button"
                                                        onClick={handleLoadMore}
                                                        disabled={isLoadingMore}
                                                        className="inline-flex items-center justify-center rounded-lg bg-[#0079C2] px-10 py-3 text-sm font-semibold text-white hover:bg-[#005a91] disabled:opacity-60"
                                                    >
                                                        {isLoadingMore ? 'Loading...' : 'More Product'}
                                                    </button>
                                                )}
                                            </div>
                                        </>
                                    )}
                                </>
                            )}
                        </section>
                    </div>
                </section>
                <section className=''>
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
