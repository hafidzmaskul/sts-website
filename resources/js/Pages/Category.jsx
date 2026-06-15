import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import ProductListingCard from '../components/ProductListingCard';
import FeaturedProductsSection from '../components/FeaturedProductsSection';
import { formatPrice } from '../helpers/currency';
import 'swiper/css';

const hasSelectedDescendants = (category, selectedSubCategories) => {
    if (!category.children || !category.children.length) return false;
    return category.children.some(child => 
        selectedSubCategories.includes(child.id) || hasSelectedDescendants(child, selectedSubCategories)
    );
};

const allDescendantsSelected = (category, selectedSubCategories) => {
    if (!category.children || !category.children.length) return false;
    return category.children.every(child => 
        selectedSubCategories.includes(child.id) && 
        (!child.children?.length || allDescendantsSelected(child, selectedSubCategories))
    );
};

const RecursiveSidebarCategoryItem = ({
    category,
    expandedCategories,
    selectedCategories,
    selectedSubCategories,
    onToggleExpand,
    onToggleSelect,
    onToggleSubSelect,
    level = 0
}) => {
    if (!category || !category.id) return null;

    const isExpanded = expandedCategories.includes(category.id);
    const isChecked = level === 0 
        ? selectedCategories.includes(category.id)
        : selectedSubCategories.includes(category.id);

    const isPartialChecked = level === 0 
        ? (hasSelectedDescendants(category, selectedSubCategories) && !allDescendantsSelected(category, selectedSubCategories))
        : false;

    return (
        <div className={`pb-3 ${level === 0 ? 'border-b border-gray-200 last:border-b-0 last:pb-0' : 'mt-2'}`}>
            <div className="flex items-center justify-between">
                <label className={`flex items-center gap-2 font-medium text-[#232323] ${level === 0 ? 'text-sm' : 'text-sm text-gray-700 font-normal cursor-pointer'}`}>
                    <input
                        type="checkbox"
                        className="h-4 w-4 rounded border-gray-300 text-[#0079C2]"
                        checked={isChecked}
                        ref={el => {
                            if (el && level === 0) {
                                el.indeterminate = isPartialChecked;
                            }
                        }}
                        onChange={() => {
                            if (level === 0) onToggleSelect(category.id);
                            else onToggleSubSelect(category.id);
                        }}
                    />
                    <span>{category.name}</span>
                </label>
                {category.children && category.children.length > 0 && (
                    <button
                        type="button"
                        onClick={() => onToggleExpand(category.id)}
                        className="ml-2"
                    >
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
                )}
            </div>
            {isExpanded && category.children && category.children.length > 0 && (
                <div className="ml-4 border-l border-gray-200 pl-3">
                    {category.children.map(child => (
                        <RecursiveSidebarCategoryItem
                            key={child.id}
                            category={child}
                            expandedCategories={expandedCategories}
                            selectedCategories={selectedCategories}
                            selectedSubCategories={selectedSubCategories}
                            onToggleExpand={onToggleExpand}
                            onToggleSelect={onToggleSelect}
                            onToggleSubSelect={onToggleSubSelect}
                            level={level + 1}
                        />
                    ))}
                </div>
            )}
        </div>
    );
};

const sliderImages = [
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
    '/assets/dummmy/f58bbfe80051d14bcd96790ff72c7c32a3edff31.jpg',
];

const PRODUCT_PAGE_SIZE = 28;

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

const transformProduct = (product, index = 0) => {
    if (!product || typeof product !== 'object') {
        return null;
    }

    const imagePath = product.images?.[0]?.image_path;
    const image = imagePath
        ? (imagePath.startsWith('/') ? imagePath : `/storage/${imagePath}`)
        : sliderImages[index % sliderImages.length];

    // Get price using priority: calculated_price -> special_price -> base_price
    const rawPrice = getProductPrice(product);
    let productPrice = 0;
    if (rawPrice) {
        const cleanedPrice = String(rawPrice).replace(/[^\d.-]/g, '');
        const parsedPrice = parseFloat(cleanedPrice);
        if (!Number.isNaN(parsedPrice) && isFinite(parsedPrice)) {
            productPrice = parsedPrice;
        }
    }

    const badge = index % 3 === 0 ? 'New' : index % 3 === 1 ? 'Best Seller' : 'Limited';

    const categoryIds = Array.isArray(product.categories)
        ? product.categories.map(cat => cat?.id).filter(Boolean)
        : [];

    return {
        id: product.id,
        title: product.title || '',
        name: product.title || '',
        slug: product.slug || '',
        price: productPrice,
        image,
        brand_name: product.brand_name || 'STS',
        brand: product.brand_name || 'STS',
        series: product.series || `Model ${product.slug || product.id}`,
        categoryIds,
        badge,
        is_sign_up_for_pricing: product.is_sign_up_for_pricing,
    };
};

export default function Category({ products = [], baseProducts = [], productCategory = [], logged, currentCategory }) {
    console.log(products)
    const allProducts = useMemo(() => {
        const sourceProducts = Array.isArray(products) && products.length > 0
            ? products
            : (Array.isArray(baseProducts) ? baseProducts : []);

        const transformedProducts = sourceProducts
            .map((product, index) => transformProduct(product, index))
            .filter(Boolean);

        return transformedProducts;
    }, [products, baseProducts]);

    const [searchQuery, setSearchQuery] = useState('');
    const [debouncedSearchQuery, setDebouncedSearchQuery] = useState('');
    const [sortBy, setSortBy] = useState('featured');
    const [expandedCategories, setExpandedCategories] = useState(() =>
        Array.isArray(productCategory)
            ? productCategory.map((category) => category?.id).filter(Boolean)
            : []
    );
    const [selectedSubCategories, setSelectedSubCategories] = useState([]);
    const [selectedCategories, setSelectedCategories] = useState([]);
    const [visibleCount, setVisibleCount] = useState(PRODUCT_PAGE_SIZE);
    const [isInitialLoading, setIsInitialLoading] = useState(true);
    const [isLoadingMore, setIsLoadingMore] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => {
            setDebouncedSearchQuery(searchQuery);
        }, 300);

        return () => clearTimeout(timer);
    }, [searchQuery]);

    useEffect(() => {
        const findPathToCategory = (categories, targetId, path = []) => {
            for (const cat of categories) {
                if (cat.id === targetId) return [...path, cat.id];
                if (cat.children && cat.children.length) {
                    const foundPath = findPathToCategory(cat.children, targetId, [...path, cat.id]);
                    if (foundPath) return foundPath;
                }
            }
            return null;
        };

        if (currentCategory) {
            // Check if it's a subcategory by checking if it has a parent_id
            if (currentCategory.parent_id) {
                setSelectedSubCategories([currentCategory.id]);
                // Automatically expand all parents up to this subcategory
                if (Array.isArray(productCategory)) {
                    const path = findPathToCategory(productCategory, currentCategory.id);
                    if (path && path.length > 1) {
                        const parentsToExpand = path.slice(0, -1);
                        setExpandedCategories(prev => {
                            const newSet = new Set([...prev, ...parentsToExpand]);
                            return Array.from(newSet);
                        });
                    }
                }
            } else {
                setSelectedCategories([currentCategory.id]);
            }
        }
    }, [productCategory, currentCategory]);

    const featuredProducts = useMemo(
        () =>
            allProducts.slice(0, 8).map((product) => ({
                id: product.id,
                title: product.title || product.name,
                price: product.price,
                image: product.image,
                badge: product.badge,
                slug: product.slug
            })),
        [allProducts],
    );

    const baseSlidesPerView = 5;
    const sliderProducts =
        allProducts.length > baseSlidesPerView
            ? allProducts
            : [...allProducts, ...allProducts];
    const loopEnabled = sliderProducts.length > baseSlidesPerView;

    useEffect(() => {
        const timeout = setTimeout(() => {
            setIsInitialLoading(false);
        }, 400);

        return () => clearTimeout(timeout);
    }, []);

    // FILTERING PRODUCTS
    const filteredProducts = useMemo(() => {
        let productsResult = allProducts;

        // Search
        if (debouncedSearchQuery.trim() !== '') {
            const query = debouncedSearchQuery.trim().toLowerCase();
            productsResult = productsResult.filter((product) => {
                const name = String(product.name || product.title || '').toLowerCase();
                const brand = String(product.brand || product.brand_name || '').toLowerCase();
                const series = String(product.series || '').toLowerCase();
                return name.includes(query) || brand.includes(query) || series.includes(query);
            });
        }

        // Category filtering (parent + child) - Optimized with Set for O(1) lookups
        if (selectedCategories.length > 0 || selectedSubCategories.length > 0) {
            const parentSet = new Set(selectedCategories);
            const childSet = new Set(selectedSubCategories);

            productsResult = productsResult.filter((product) => {
                const productCategoryIds = product.categoryIds || [];

                if (productCategoryIds.length === 0) {
                    return false;
                }

                const matchesParent = parentSet.size === 0 ||
                    productCategoryIds.some(catId => parentSet.has(catId));

                const matchesChild = childSet.size === 0 ||
                    productCategoryIds.some(subCatId => childSet.has(subCatId));

                return matchesParent && matchesChild;
            });
        }

        // Sort
        if (productsResult.length === 0) {
            return [];
        }

        const sorted = [...productsResult];
        if (sortBy === 'price-asc') {
            sorted.sort((a, b) => (a.price || 0) - (b.price || 0));
        } else if (sortBy === 'price-desc') {
            sorted.sort((a, b) => (b.price || 0) - (a.price || 0));
        } else if (sortBy === 'name-asc') {
            sorted.sort((a, b) => {
                const nameA = String(a.name || a.title || '');
                const nameB = String(b.name || b.title || '');
                return nameA.localeCompare(nameB);
            });
        }

        return sorted;
    }, [allProducts, debouncedSearchQuery, selectedCategories, selectedSubCategories, sortBy]);

    useEffect(() => {
        setVisibleCount(PRODUCT_PAGE_SIZE);
    }, [debouncedSearchQuery, selectedCategories, selectedSubCategories, sortBy]);

    const totalProducts = filteredProducts.length;
    const visibleProducts = filteredProducts.slice(0, visibleCount);

    // Expand/collapse logic
    const handleToggleCategoryExpand = useCallback((categoryId) => {
        setExpandedCategories((current) =>
            current.includes(categoryId)
                ? current.filter((id) => id !== categoryId)
                : [...current, categoryId]
        );
    }, []);

    // Select parent (category) logic
    const handleToggleCategorySelect = useCallback((categoryId) => {
        setSelectedCategories((selected) =>
            selected.includes(categoryId)
                ? selected.filter((id) => id !== categoryId)
                : [...selected, categoryId]
        );
    }, []);

    // Select child (subcategory) logic
    const handleToggleSubCategorySelect = useCallback((subCategoryId) => {
        setSelectedSubCategories((selected) =>
            selected.includes(subCategoryId)
                ? selected.filter((id) => id !== subCategoryId)
                : [...selected, subCategoryId]
        );
    }, []);

    const handleLoadMore = useCallback(() => {
        if (visibleCount >= totalProducts) {
            return;
        }

        setIsLoadingMore(true);

        setTimeout(() => {
            setVisibleCount((current) =>
                Math.min(current + PRODUCT_PAGE_SIZE, totalProducts),
            );
            setIsLoadingMore(false);
        }, 500);
    }, [visibleCount, totalProducts]);

    // Determine if all children are checked for a parent
    const isCategoryFullyChecked = useCallback((category) => {
        if (!category?.children || !Array.isArray(category.children) || category.children.length === 0) {
            return false;
        }
        return category.children.every((child) => child?.id && selectedSubCategories.includes(child.id));
    }, [selectedSubCategories]);

    // Determine partial check for parent
    const isCategoryPartiallyChecked = useCallback((category) => {
        if (!category?.children || !Array.isArray(category.children) || category.children.length === 0) {
            return false;
        }
        return (
            category.children.some((child) => child?.id && selectedSubCategories.includes(child.id)) &&
            !isCategoryFullyChecked(category)
        );
    }, [selectedSubCategories, isCategoryFullyChecked]);

    return (
        <div className="min-h-screen flex flex-col">
            <Head>
                <title>{currentCategory?.seo_title || currentCategory?.name || 'Category'}</title>
                <meta name="description" content={currentCategory?.seo_description || `View products in ${currentCategory?.name} category`} />
                {currentCategory?.seo_keywords && <meta name="keywords" content={currentCategory.seo_keywords} />}
            </Head>            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <h1 className="font-bebas-neue text-4xl md:text-5xl mb-6 text-[#232323]">
                    STS Exclusive brand
                </h1>
                <section id="img-slider" className="py-10">
                    <Swiper
                        modules={[Autoplay]}
                        spaceBetween={28} // tambahkan jarak di antara slide produk
                        slidesPerView={1}
                        loop={loopEnabled}
                        autoplay={{
                            delay: 2500,
                            disableOnInteraction: false,
                        }}
                        breakpoints={{
                            640: {
                                slidesPerView: Math.min(3, baseSlidesPerView),
                                spaceBetween: 28, // jarak antar slide untuk breakpoint kecil
                            },
                            1024: {
                                slidesPerView: baseSlidesPerView,
                                spaceBetween: 32, // tambahkan jarak lebih besar pada layar besar
                            },
                        }}
                        grabCursor
                    >
                        {sliderProducts.map((product, index) => {
                            const imageUrl = product.image
                                ?? sliderImages[index % sliderImages.length];
                            const title = product.title || product.name || 'Product';

                            return (
                                <SwiperSlide key={`${product.slug ?? product.id}-${index}`}>
                                    <div className="overflow-hidden mb-4"> {/* Tambahkan margin bawah antar produk */}
                                        <Link
                                            // href={route('products.detail', product.slug)}
                                            className="block"
                                        >
                                            <img
                                                src={imageUrl}
                                                alt={title}
                                                className="w-full h-56 object-cover rounded-lg" // tambahkan rounding jika ingin tampilan lebih soft
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
                        className="mt-6 grid grid-cols-1 xl:grid-cols-5 gap-8 items-start"
                    >
                        <aside className="xl:col-span-1">
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
                                    {Array.isArray(productCategory) && productCategory.length > 0 ? (
                                        productCategory.map((category) => (
                                            <RecursiveSidebarCategoryItem
                                                key={category.id}
                                                category={category}
                                                expandedCategories={expandedCategories}
                                                selectedCategories={selectedCategories}
                                                selectedSubCategories={selectedSubCategories}
                                                onToggleExpand={handleToggleCategoryExpand}
                                                onToggleSelect={handleToggleCategorySelect}
                                                onToggleSubSelect={handleToggleSubCategorySelect}
                                            />
                                        ))
                                    ) : (
                                        <p className="text-sm text-gray-500">No categories available</p>
                                    )}
                                </div>
                            </div>
                        </aside>

                        <section className="xl:col-span-4">
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
                                <div className="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
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
                                            <div className="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                                                {visibleProducts.map((product) => (

                                                    <ProductListingCard
                                                        key={product.id}
                                                        image={product.image}
                                                        name={product.title}
                                                        brand={product.brand_name}
                                                        series={product.series}
                                                        badge={product.badge}
                                                        priceLabel={formatPrice(product.price)}
                                                        slug={product.slug}
                                                        showPricing={product.is_sign_up_for_pricing && !logged}
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
