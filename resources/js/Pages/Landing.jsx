import React, { useEffect, useMemo, useRef, useState } from 'react';
import { Head } from '@inertiajs/react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import FeaturedProductsSection from '../components/FeaturedProductsSection';
import 'swiper/css';
import axios from 'axios';

export default function Landing({
    banners = [],
    landingPageData = {},
    featured = [],
    categories = [],
    brand = [],
    user
}) {
    console.log(user)
    const [currentBannerIndex, setCurrentBannerIndex] = useState(0);
    const productSwiperRef = useRef(null);
    const shopSwiper1Ref = useRef(null);
    const shopSwiper2Ref = useRef(null);
    const shopSwiper3Ref = useRef(null);

    // Form state
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        subject: 'General Inquiry 1',
        message: ''
    });
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [showSuccessAlert, setShowSuccessAlert] = useState(false);

    useEffect(() => {
        if (banners.length <= 1) {
            return;
        }

        const interval = setInterval(() => {
            setCurrentBannerIndex((prev) => (prev + 1) % banners.length);
        }, 6000);

        return () => clearInterval(interval);
    }, [banners.length]);

    useEffect(() => {
        if (currentBannerIndex > banners.length - 1) {
            setCurrentBannerIndex(0);
        }
    }, [banners.length, currentBannerIndex]);

    const activeBanner = banners[currentBannerIndex] ?? null;
    const heroImage = activeBanner?.file_path
        ? `/storage/${activeBanner.file_path}`
        : 'https://placehold.co/900x700/302F2F/FFFFFF?text=No+Banner';
    const ctaHref = activeBanner?.cta_url ?? '/products';

    const goToSlide = (index) => {
        if (banners.length === 0) {
            return;
        }
        const safeIndex = (index + banners.length) % banners.length;
        setCurrentBannerIndex(safeIndex);
    };

    const goNext = () => goToSlide(currentBannerIndex + 1);
    const goPrevious = () => goToSlide(currentBannerIndex - 1);

    const likedProductIds = useMemo(() => {
        if (!user || !Array.isArray(user?.liked_products)) {
            return [];
        }

        return user.liked_products
            .map((likedProduct) => likedProduct?.id)
            .filter((id) => id !== null && id !== undefined);
    }, [user]);

    // Transform featured products only based on real data (NO FALLBACK PRODUCTS)
    const transformFeaturedProduct = (product, index) => {
        const imagePath = product.images?.[0]?.image_path;
        const image = imagePath
            ? (imagePath.startsWith('/') ? imagePath : `/storage/${imagePath}`)
            : ''; // no fallback

        let basePrice = null;
        if (product.base_price) {
            const cleanedPrice = product.base_price.toString().replace(/[^\d.-]/g, '');
            const parsedPrice = parseFloat(cleanedPrice);
            if (!Number.isNaN(parsedPrice)) {
                basePrice = parsedPrice > 10000 ? parsedPrice : parsedPrice * 1000;
            }
        }

        const badge = index % 3 === 0 ? 'New' : index % 3 === 1 ? 'Sales' : 'Limited';

        // Ambil slug jika ada di original object, kalau tidak coba generate dari title
        let slug = product.slug;
        if (!slug && product.title) {
            slug = product.title
                .toString()
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        } else if (!slug) {
            slug = `product-${product.id ?? index}`;
        }

        const id = product.id ?? index;

        return {
            id,
            title: product.title ?? `Product ${index + 1}`,
            price: basePrice,
            image,
            badge: product.badge ?? badge,
            slug,
            isLiked: likedProductIds.includes(id),
        };
    };

    // If featured is empty, products will be empty array (NO fallback products)
    const products = useMemo(() => {
        if (featured.length > 0) {
            const normalizedProducts = featured.map((product, index) => transformFeaturedProduct(product, index));
            return normalizedProducts;
        }
        return [];
    }, [featured]);

    // Duplicate products if needed for infinite loop
    const visibleSlides = useMemo(() => {
        if (products.length >= 4) {
            return 4;
        }

        if (products.length === 3) {
            return 3;
        }

        if (products.length === 2) {
            return 2;
        }

        return 1;
    }, [products.length]);

    const loopedProducts = useMemo(() => {
        if (products.length === 0) {
            return [];
        }
        // If we don't have enough slides for loop, duplicate them
        const minSlidesForLoop = visibleSlides * 2;
        if (products.length < minSlidesForLoop) {
            const multiplier = Math.ceil(minSlidesForLoop / products.length);
            return Array(multiplier).fill(products).flat();
        }
        return products;
    }, [products, visibleSlides]);

    // Transform categories (NO fallbackCategories at all)
    const getTransformedCategories = () => {
        if (categories && categories.length > 0) {
            return categories.map((cat, idx) => ({
                ...cat,
                image_url: cat.image_url
                    ? cat.image_url
                    : cat.image_path
                    ? (cat.image_path.startsWith('http')
                        ? cat.image_path
                        : `/storage/${cat.image_path}`)
                    : "",
                name: cat.name || `Category ${idx + 1}`,
                products: Array.isArray(cat.products) ? cat.products : [],
                products_count:
                    typeof cat.products_count === 'number'
                        ? cat.products_count
                        : Array.isArray(cat.products)
                        ? cat.products.length
                        : 0,
            }));
        }
        // No fallback, return empty array
        return [];
    };
    const transformedCategories = useMemo(() => getTransformedCategories(), [categories]);

    // Brands section sectionId
    const resolveBrandImage = (img) => {
        // Accept already complete urls or storage paths, otherwise prepend /storage/
        if (!img) {
            return '/assets/dummmy/default-brand.png';
        }
        if (img.startsWith('http') || img.startsWith('/')) {
            return img;
        }
        return `/storage/${img}`;
    };

    // Handle form input changes
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    // Handle form submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);

        try {
            await axios.post('/api/contact-submissions', formData);

            setIsSubmitting(false);
            setShowSuccessAlert(true);
            // Reset form
            setFormData({
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                subject: 'General Inquiry 1',
                message: ''
            });
            // Hide alert after 5 seconds
            setTimeout(() => setShowSuccessAlert(false), 5000);
        } catch (error) {
            setIsSubmitting(false);
            console.error('Form submission error:', error);
            // You might want to show an error message to the user here
        }
    };

    const goToNextShop = () => {
        shopSwiper1Ref.current?.slideNext();
        shopSwiper2Ref.current?.slideNext();
        shopSwiper3Ref.current?.slideNext();
    };

    const goToPreviousShop = () => {
        shopSwiper1Ref.current?.slidePrev();
        shopSwiper2Ref.current?.slidePrev();
        shopSwiper3Ref.current?.slidePrev();
    };

    return (
        <div className="min-h-screen  ">
            <Head title="Home - Absolutely Human Resources" />
            <Header />

            {/* Success Alert */}
            {showSuccessAlert && (
                <div className="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" className="mr-2">
                        <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Your message has been sent successfully!
                </div>
            )}

            <section
                className="relative"
                style={{
                    backgroundImage: "url('/assets/bg-carousel.jpg')",
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                }}
            >
                <div className="container mx-auto px-6 md:px-10 lg:px-20 relative z-10">
                    <div className=" flex flex-col md:flex-row items-center gap-5">
                        <div className="w-full md:w-1/2 space-y-6">
                            <p className='text-white text-2xl font-light'>Welcome to STS</p>
                            <h1 className="font-bebas-neue text-white text-5xl md:text-6xl leading-[1.1]">
                                {activeBanner?.name ?? 'Banner Coming Soon'}
                            </h1>
                            <div className="pt-4 mt-50">
                                <a
                                    href={ctaHref}
                                    className="inline-flex items-center gap-3 rounded-xl bg-[#0079C2] px-8 py-3 font-semibold text-[#fff] transition hover:-translate-y-0.5 "
                                >
                                    Shop Now
                                    <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                                </a>
                            </div>
                        </div>

                        <div className="w-full md:w-1/2 flex justify-end">
                            <img
                                src={heroImage}
                                alt={activeBanner?.name ?? 'Banner image'}
                                className="h-full w-full z-10 object-cover"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </div>
                {banners.length > 1 && (
                    <div className="absolute z-20 bottom-10 flex w-full pointer-events-none">
                        <div className="flex w-full justify-between">
                            <button
                                type="button"
                                onClick={goPrevious}
                                className="pointer-events-auto inline-flex items-end gap-2  border-none  bg-white px-10 py-2 font-semibold text-[#0079C2] transition hover:bg-[#0079C2] hover:text-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m6 8l-4 4l4 4m-4-4h20" /></svg>
                            </button>
                            <button
                                type="button"
                                onClick={goNext}
                                className="pointer-events-auto inline-flex items-start gap-2  border-none  bg-white px-10 py-2 font-semibold text-[#0079C2] transition hover:bg-[#0079C2] hover:text-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                            </button>
                        </div>
                    </div>
                )}
                {/* Carousel Indicators pinned at absolute bottom, below text and image */}
                {banners.length > 1 && (
                    <div
                        className="absolute -z-10 left-0 right-0 bottom-4 flex justify-center gap-4 z-0"
                    >
                        {banners.map((banner, index) => (
                            <button
                                key={banner.id ?? `banner-${index}`}
                                type="button"
                                onClick={() => goToSlide(index)}
                                className={`h-3 w-3 rounded-full border border-[#0079C2]/70 transition ${currentBannerIndex === index
                                    ? 'bg-black'
                                    : 'bg-transparent hover:bg-[#0079C2]/40'
                                    }`}
                                aria-label={`Go to banner ${index + 1}`}
                            />
                        ))}
                    </div>
                )}

            </section>
            <section className='bg-[#0079C2] mt-10'>
                <div className="container px-10 md:px-20 mx-auto">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 py-12">
                        <div className="flex items-center space-x-4">
                            <img src="/assets/box.svg" alt="Discount" width={32} height={32} />
                            <div>
                                <div className="text-white font-semibold text-lg">Discount</div>
                                <div className="text-[#AEE2FF] text-sm">Every week new sales</div>
                            </div>
                        </div>
                        <div className="flex items-center space-x-4">
                            <img src="/assets/delivery-truck.svg" alt="Free Delivery" width={32} height={32} />
                            <div>
                                <div className="text-white font-semibold text-lg">Free Delivery</div>
                                <div className="text-[#AEE2FF] text-sm">100% Free for all orders</div>
                            </div>
                        </div>
                        <div className="flex items-center space-x-4">
                            <img src="/assets/24-hours.svg" alt="Great Support 24/7" width={32} height={32} />
                            <div>
                                <div className="text-white font-semibold text-lg">Great Support 24/7</div>
                                <div className="text-[#AEE2FF] text-sm">We care your experiences</div>
                            </div>
                        </div>
                        <div className="flex items-center space-x-4">
                            <img src="/assets/shield.svg" alt="Secure Payment" width={32} height={32} />
                            <div>
                                <div className="text-white font-semibold text-lg">Secure Payment</div>
                                <div className="text-[#AEE2FF] text-sm">1100% Secure Payment Method</div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
            <section className='container mx-auto md:px-20 px-10 py-10'>

            <FeaturedProductsSection
                products={products}
                title="Featured Products"
                titleSize="text-2xl"
                slidesPerView={4}
                sectionId="product"
            />
            </section>

            {/* CATEGORIES SECTION - Tetap ada, tidak diubah */}


            {/* BRANDS SECTION - sesuai instruksi */}
            <section className="bg-[#0079C2] w-full py-12 mt-10">
                <div className="container mx-auto px-6 md:px-10 lg:px-20">
                    <div className="flex flex-col items-center justify-center space-y-2 mb-8">
                        <h2 className="font-bebas-neue text-white text-4xl tracking-widest text-center">BRANDS</h2>
                        <div className="font-inter text-[#fff] text-md text-center mt-1 max-w-2xl">Lorem ipsum dolor sit amet, consectetur adipiscing elit</div>
                    </div>
                    <div
                        className="w-full"
                    >
                        {Array.isArray(brand) && brand.length > 0 ? (
                            <div className="
                                grid
                                grid-cols-2
                                sm:grid-cols-3
                                md:grid-cols-4
                                lg:grid-cols-6
                                gap-6
                                justify-center
                                items-center
                                "
                            >
                                {brand.map((item) => (
                                    <div key={item.id} className="flex flex-col items-center ">
                                        <div className="w-24 h-24 flex items-center justify-center mb-3  overflow-hidden">
                                            <img
                                                src={resolveBrandImage(item.image)}
                                                alt={item.name}
                                                className="object-contain w-20 h-20"
                                            />
                                        </div>

                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-white/80 py-8 text-center">Belum ada brand yang ditampilkan.</div>
                        )}
                    </div>
                </div>
            </section>



            <section className='container mx-auto px-10 md:px-20 py-20'>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* Card Kiri (Hot Deals) */}
                    <div className="bg-[#DADADA] text-[#0079C2] flex flex-col lg:flex-row items-stretch justify-start relative lg:h-80 overflow-visible">
                        {/* Konten Text */}
                        <div className="flex flex-col justify-center items-end text-right w-full pr-8 py-8 lg:pr-16 lg:py-0">
                            <h2 className="font-bold text-2xl lg:text-4xl mb-3 mt-2 font-inter ">Hot Deals</h2>
                            <p className=" mb-4 text-sm lg:text-base max-w-[375px]">
                            Upgrade your security with our latest deals on access control and surveillance products. Enjoy reliable performance, modern design, and trusted protection—now at special prices for a limited time.
                            </p>
                            <button className="bg-white hover: text-[#0079C2] px-6 py-2 rounded font-semibold transition  self-end flex items-center gap-2">
                                Shop Now
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                            </button>
                            {/* Gambar: Mobile tampil di bawah, desktop offset ke kiri */}
                            <img
                                src="/assets/sound.png"
                                alt="Sound"
                                className="block lg:hidden mt-6 h-44 w-auto object-contain"
                            />
                        </div>
                        <div className="hidden lg:block absolute left-0 lg:-left-16 top-1/2 -translate-y-1/2 z-0">
                            <img
                                src="/assets/sound.png"
                                alt="Sound"
                                className="h-72 object-contain"
                                style={{
                                    transform: 'translateX(-30%)',
                                }}
                            />
                        </div>
                    </div>
                    {/* Card Kanan (Services) */}
                    <div className="bg-[#0079C2] flex flex-col lg:flex-row items-stretch justify-end relative lg:h-80 overflow-visible">
                        {/* Konten Text */}
                        <div className="flex flex-col justify-center items-start text-left w-full pl-8 py-8 lg:pl-10 lg:py-0">
                            <h2 className="font-bold text-2xl lg:text-4xl mb-3 mt-2 font-inter  text-white">Services</h2>
                            <p className="text-white/80 mb-4 text-sm lg:text-base max-w-[375px]">
                            From consultation to installation and ongoing support, we deliver end-to-end security services tailored to your needs. Protect your property with expert solutions designed for reliability, safety, and peace of mind.
                            </p>
                            <button className="bg-white hover:bg-gray-200 text-[#0079C2] px-6 py-2 rounded font-semibold transition self-start flex items-center gap-2">
                                Shop Now
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                            </button>
                            {/* Gambar: Mobile tampil di bawah, desktop offset ke kanan */}
                            <img
                                src="/assets/cctv.png"
                                alt="CCTV"
                                className="block lg:hidden mt-6 h-44 w-auto object-contain"
                            />
                        </div>
                        <div className="hidden lg:block absolute right-2 lg:-right-6 top-1/3 -translate-y-1/2 z-0">
                            <img
                                src="/assets/cctv.png"
                                alt="CCTV"
                                className="h-60 object-contain"
                                style={{
                                    transform: 'translateX(30%) translateY(-20%)',
                                }}
                            />
                        </div>
                    </div>
                </div>

            </section>
            {/* SHOP BY CATEGORY - Loop Categories - Please don't change design, just data and mapping */}
            <section id='shop-by-category' className='bg-[#F3F3F3]'>
                <div className="container md:px-20 px-10 py-20 mx-auto">
                    <div className="mb-6 flex container mx-auto py-10 px-10 nd:px-20 flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div className="space-y-1">
                            <h1 className='font-inter font-bebas-neue font-bold text-5xl'>Shop by Category</h1>
                        </div>
                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                onClick={goToPreviousShop}
                                className="inline-flex h-11 w-11 items-center justify-center  border border-[#D5D9DF] text-[#1E1E1E] transition hover:bg-[#0079C2] hover:text-[#fff]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m6 8l-4 4l4 4m-4-4h20" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                onClick={goToNextShop}
                                className="inline-flex h-11 w-11 items-center justify-center  border border-[#D5D9DF] text-[#1E1E1E] transition hover:bg-[#0079C2] hover:text-[#fff]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m18 8l4 4l-4 4M2 12h20"></path></svg>
                            </button>
                        </div>
                    </div>
                    {/* Loop categories, 3 rows if possible */}
                    {[0, 1, 2].map((row) => {
                        // Get 3 sets of categories for 3 swiper rows
                        // You can also split evenly. Safe so will always render 3 rows if available (else empty)
                        const categoriesRow = transformedCategories.filter((_, idx) => idx % 3 === row);
                        return (
                            <div className="flex mt-20 items-center gap-8" key={row}>
                                <div className="w-2/10 flex justify-center">
                                    {/* category name. Use first slide name or fallback */}
                                    <h1 className='font-bebas-neue font-bold text-4xl -rotate-90 whitespace-nowrap'>
                                        {categoriesRow[0]?.name || "No Category"}
                                    </h1>
                                </div>
                                <div className="w-8/10">
                                    <Swiper
                                        modules={[Autoplay]}
                                        onSwiper={swiperInstance => {
                                            if (row === 0)
                                                shopSwiper1Ref.current = swiperInstance;
                                            else if (row === 1)
                                                shopSwiper2Ref.current = swiperInstance;
                                            else if (row === 2)
                                                shopSwiper3Ref.current = swiperInstance;
                                        }}
                                        loop={true}
                                        slidesPerView={4}
                                        spaceBetween={40}
                                        speed={650}
                                        autoplay={{
                                            delay: 4200,
                                            disableOnInteraction: false,
                                            pauseOnMouseEnter: true,
                                        }}
                                        breakpoints={{
                                            0: {
                                                slidesPerView: 1,
                                                spaceBetween: 20,
                                            },
                                            768: {
                                                slidesPerView: 2,
                                                spaceBetween: 30,
                                            },
                                            1024: {
                                                slidesPerView: 4,
                                                spaceBetween: 40,
                                            },
                                        }}
                                    >
                                        {/* Loop per category in this row */}
                                        {categoriesRow.map((category, idx) => (
                                            <SwiperSlide key={category?.id ?? `catrow${row}-slide${idx}`}>
                                                <div className="rounded-xl bg-white text-white pb-2 hover:text-[#fff] hover:bg-[#0079C2] transition cursor-pointer">
                                                    <img
                                                        src={category?.image_url || "/assets/2b07321eb9e9f6684dfbbafe4438118d7838fa9f.png"}
                                                        className='h-40 py-2 w-full object-contain'
                                                        alt=""
                                                    />
                                                    {/* category name */}
                                                    <span className="font-bebas-neue font-bold px-3 text-2xl ">
                                                        {category?.name || "Unknown"}
                                                    </span>
                                                    <br />
                                                    {/* product count */}
                                                    <span className="font-inter font-light text-xs px-3 ">
                                                        {category?.products_count} product
                                                    </span>
                                                    {/* product name(s) */}

                                                </div>
                                            </SwiperSlide>
                                        ))}
                                    </Swiper>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </section>
            <section className="w-full mt-20  flex flex-col md:flex-row bg-[#0079C2]">
                {/* Left Column */}
                <div className=" flex-1 flex flex-col  px-20 py-10 text-white">
                    <h2 className="text-3xl font-bold mb-6 ">Contact Information</h2>
                    <p className="mb-7  text-lg">Say something to start a live chat!</p>
                    <div className="flex flex-col gap-6 ">
                        <div className='flex'>
                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><g fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}><path fill="currentColor" fillOpacity={0} strokeDasharray={64} strokeDashoffset={64} d="M8 3c0.5 0 2.5 4.5 2.5 5c0 1 -1.5 2 -2 3c-0.5 1 0.5 2 1.5 3c0.39 0.39 2 2 3 1.5c1 -0.5 2 -2 3 -2c0.5 0 5 2 5 2.5c0 2 -1.5 3.5 -3 4c-1.5 0.5 -2.5 0.5 -4.5 0c-2 -0.5 -3.5 -1 -6 -3.5c-2.5 -2.5 -3 -4 -3.5 -6c-0.5 -2 -0.5 -3 0 -4.5c0.5 -1.5 2 -3 4 -3Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.7s" dur="0.5s" values="0;1"></animate><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"></animate></path><path strokeDasharray={4} strokeDashoffset={4} d="M15.76 8.28c-0.5 -0.51 -1.1 -0.93 -1.76 -1.24M15.76 8.28c0.49 0.49 0.9 1.08 1.2 1.72"><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.2s" dur="0.3s" values="4;0"></animate></path><path strokeDasharray={6} strokeDashoffset={6} d="M18.67 5.35c-1 -1 -2.26 -1.73 -3.67 -2.1M18.67 5.35c0.99 1 1.72 2.25 2.08 3.65"><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.4s" dur="0.3s" values="6;0"></animate></path></g></svg>
                            <div className="text-lg font-medium mb-1 ml-10">+1012 3456 789</div>
                        </div>
                        <div className='flex'>
                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" d="M2 20V4h20v16zm10-7l8-5V6l-8 5l-8-5v2z"></path></svg>
                            <div className="text-lg font-medium mb-1 ml-10">demo@gmail.com</div>
                        </div>
                        <div className="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M11.291 21.706L12 21zM12 21l.708.706a1 1 0 0 1-1.417 0l-.006-.007l-.017-.017l-.062-.063a48 48 0 0 1-1.04-1.106a50 50 0 0 1-2.456-2.908c-.892-1.15-1.804-2.45-2.497-3.734C4.535 12.612 4 11.248 4 10c0-4.539 3.592-8 8-8s8 3.461 8 8c0 1.248-.535 2.612-1.213 3.87c-.693 1.286-1.604 2.585-2.497 3.735a50 50 0 0 1-3.496 4.014l-.062.063l-.017.017l-.006.006zm0-8a3 3 0 1 0 0-6a3 3 0 0 0 0 6" clipRule="evenodd"></path></svg>
                            <div className="ml-10">
                                <div className="text-base">132 Dartmouth Street</div>
                                <div className="text-base">Boston, Massachusetts 02156</div>
                                <div className="text-base">United States</div>
                            </div>
                        </div>
                        <div className="flex gap-2">
                            <div className="bg-black rounded-full p-2 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" d="M22.46 6c-.77.35-1.6.58-2.46.69c.88-.53 1.56-1.37 1.88-2.38c-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29c0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15c0 1.49.75 2.81 1.91 3.56c-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.2 4.2 0 0 1-1.93.07a4.28 4.28 0 0 0 4 2.98a8.52 8.52 0 0 1-5.33 1.84q-.51 0-1.02-.06C3.44 20.29 5.7 21 8.12 21C16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56c.84-.6 1.56-1.36 2.14-2.23"></path></svg>
                            </div>
                            <div className="bg-white text-black rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24"><path fill="currentColor" d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4zm9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8A1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5a5 5 0 0 1-5 5a5 5 0 0 1-5-5a5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                {/* Right Column (Form) */}
                <div className="flex-1  px-8 py-10">
                    <form className="space-y-6" id='contact-submission' onSubmit={handleSubmit}>
                        <div className="flex flex-col md:flex-row gap-6">
                            <div className="flex-1">
                                <label className="block mb-2 text-white" htmlFor="first_name">
                                    First Name
                                </label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value={formData.first_name}
                                    onChange={handleInputChange}
                                    className="w-full border-0 border-b-2 border-gray-300 bg-transparent focus:ring-0 focus:border-[#0079C2] outline-none py-2"
                                    autoComplete="off"
                                    required
                                />
                            </div>
                            <div className="flex-1">
                                <label className="block mb-2 text-white" htmlFor="last_name">
                                    Last Name
                                </label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value={formData.last_name}
                                    onChange={handleInputChange}
                                    className="w-full border-0 border-b-2 border-gray-300 bg-transparent focus:ring-0 focus:border-[#0079C2] outline-none py-2"
                                    autoComplete="off"
                                    required
                                />
                            </div>
                        </div>
                        <div className="flex flex-col md:flex-row gap-6">
                            <div className='flex-1'>
                                <label className="block mb-2 text-white" htmlFor="email">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleInputChange}
                                    className="w-full border-0 border-b-2 border-gray-300 bg-transparent focus:ring-0 focus:border-[#0079C2] outline-none py-2"
                                    autoComplete="off"
                                    required
                                />
                            </div>
                            <div className='flex-1'>
                                <label className="block mb-2 text-white" htmlFor="phone">
                                    Phone Number
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value={formData.phone}
                                    onChange={handleInputChange}
                                    className="w-full border-0 border-b-2 border-gray-300 bg-transparent focus:ring-0 focus:border-[#0079C2] outline-none py-2"
                                    autoComplete="off"
                                />
                            </div>
                        </div>
                        <div>
                            <label className="block mb-3 text-white">Select Subject?</label>
                            <div className="flex flex-row gap-3">
                                <label className="inline-flex items-center">
                                    <input
                                        type="radio"
                                        name="subject"
                                        value="General Inquiry 1"
                                        checked={formData.subject === "General Inquiry 1"}
                                        onChange={handleInputChange}
                                        className="form-radio text-[#0079C2] focus:ring-[#0079C2]"
                                    />
                                    <span className="ml-2 text-white">General Inquiry</span>
                                </label>
                                <label className="inline-flex items-center">
                                    <input
                                        type="radio"
                                        name="subject"
                                        value="General Inquiry 2"
                                        checked={formData.subject === "General Inquiry 2"}
                                        onChange={handleInputChange}
                                        className="form-radio text-[#0079C2] focus:ring-[#0079C2]"
                                    />
                                    <span className="ml-2 text-white">General Inquiry</span>
                                </label>
                                <label className="inline-flex items-center">
                                    <input
                                        type="radio"
                                        name="subject"
                                        value="General Inquiry 3"
                                        checked={formData.subject === "General Inquiry 3"}
                                        onChange={handleInputChange}
                                        className="form-radio text-[#0079C2] focus:ring-[#0079C2]"
                                    />
                                    <span className="ml-2 text-white">General Inquiry</span>
                                </label>
                                <label className="inline-flex items-center">
                                    <input
                                        type="radio"
                                        name="subject"
                                        value="General Inquiry 4"
                                        checked={formData.subject === "General Inquiry 4"}
                                        onChange={handleInputChange}
                                        className="form-radio text-[#0079C2] focus:ring-[#0079C2]"
                                    />
                                    <span className="ml-2 text-white">General Inquiry</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label className="block mb-2 text-white" htmlFor="message">
                                Message
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                value={formData.message}
                                onChange={handleInputChange}
                                rows={4}
                                className="w-full border-0 border-b-2 border-gray-300 bg-transparent focus:ring-0 focus:border-[#0079C2] outline-none py-2 resize-none"
                                required
                            ></textarea>
                        </div>
                        <div className="flex justify-end">
                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className=" bg-white text-[#0079C2] text-center w-full  py-3 rounded-lg hover:bg-gray-100 transition mx-auto items-center gap-2"
                            >
                                {isSubmitting ? (
                                    <>
                                        <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </>
                                ) : (
                                    'Send Message'
                                )}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            <Footer landingPageData={landingPageData} />
        </div>
    );
}
