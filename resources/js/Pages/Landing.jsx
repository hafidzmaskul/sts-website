import React, { useEffect, useMemo, useRef, useState } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import CircularCarousel from '../components/CircularCarousel';
import SwiperSlider from '../components/SwiperSlider';
// PERUBAHAN: Impor komponen ScrollActiveList yang baru
import ScrollActiveList from '../components/ScrollActiveList';
import ExploreButton from '../components/ExploreButton';
import { getMaxCharacters, getMaxWords } from '../helpers/text';

export default function Landing({ sliderImage, services = [], testimonials = [], news = [] }) {
    const [currentSlide, setCurrentSlide] = useState(0);
    const carouselRef = useRef(null);
    const [, setActiveServiceIndex] = useState(0);
    const testimonialsRef = useRef(null);
    const testimonialsDraggingRef = useRef(false);
    const testimonialsStartXRef = useRef(0);
    const testimonialsScrollLeftRef = useRef(0);
    const loopedTestimonials = useMemo(
        () => (testimonials.length > 0 ? [...testimonials, ...testimonials] : []),
        [testimonials],
    );

    const slides = [
        {
            id: 1,
            text: "Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere.",
            buttonText: "Lorem Ipsum",
            years: "+29 YEARS",
            experience: "EXPERIENCE"
        },
        {
            id: 2,
            text: "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            buttonText: "Learn More",
            years: "+500 PROJECTS",
            experience: "COMPLETED"
        },
        {
            id: 3,
            text: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
            buttonText: "Get Started",
            years: "+1000 CLIENTS",
            experience: "SATISFIED"
        }
    ];
    const carouselItems = services.map((service) => ({
        title: service.name,
        text: getMaxCharacters(service.short_description ?? service.content ?? '', 500),
        image: service.image_url,
        buttonText: 'Read More',
        slug: service.slug,
    }));

    // Auto-play functionality
    useEffect(() => {
        const interval = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % slides.length);
        }, 5000);
        return () => clearInterval(interval);
    }, [slides.length]);

    const goToSlide = (index) => {
        setCurrentSlide(index);
    };

    // Touch handlers for mobile swipe
    const [touchStart, setTouchStart] = useState(0);
    const [touchEnd, setTouchEnd] = useState(0);

    // Optionally, you can retain swipe support for UX
    const handleTouchStart = (e) => {
        setTouchStart(e.targetTouches[0].clientX);
    };

    const handleTouchMove = (e) => {
        setTouchEnd(e.target.clientX);
    };

    const handleTouchEnd = () => {
        if (!touchStart || !touchEnd) return;
        const distance = touchStart - touchEnd;
        const isLeftSwipe = distance > 50;
        const isRightSwipe = distance < -50;
        if (isLeftSwipe) {
            setCurrentSlide((prev) => (prev + 1) % slides.length);
        }
        if (isRightSwipe) {
            setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
        }
    };

    useEffect(() => {
        if (carouselItems.length > 0) {
            setActiveServiceIndex(0);
            carouselRef.current?.scrollTo?.(0);
        }
    }, [carouselItems.length]);

    useEffect(() => {
        const container = testimonialsRef.current;
        if (!container || loopedTestimonials.length === 0) {
            return undefined;
        }

        const tick = () => {
            if (!container || testimonialsDraggingRef.current) {
                return;
            }

            const singleSetWidth = container.scrollWidth / 2;
            if (singleSetWidth <= 0) {
                return;
            }

            const step = 1.5;
            const nextScroll = container.scrollLeft + step;

            container.scrollLeft = nextScroll >= singleSetWidth
                ? nextScroll - singleSetWidth
                : nextScroll;
        };

        const interval = setInterval(tick, 20);

        return () => clearInterval(interval);
    }, [loopedTestimonials.length]);

    const handleTestimonialsMouseDown = (event) => {
        const container = testimonialsRef.current;
        if (!container) {
            return;
        }

        testimonialsDraggingRef.current = true;
        testimonialsStartXRef.current = event.pageX - container.offsetLeft;
        testimonialsScrollLeftRef.current = container.scrollLeft;

        const handleMouseMove = (e) => {
            if (!testimonialsDraggingRef.current) {
                return;
            }
            const x = e.pageX - container.offsetLeft;
            const walk = (x - testimonialsStartXRef.current) * 1.25;
            container.scrollLeft = testimonialsScrollLeftRef.current - walk;
        };

        const handleMouseUp = () => {
            testimonialsDraggingRef.current = false;
            window.removeEventListener('mousemove', handleMouseMove);
            window.removeEventListener('mouseup', handleMouseUp);
        };

        window.addEventListener('mousemove', handleMouseMove);
        window.addEventListener('mouseup', handleMouseUp);
    };

    return (
        <>
            <Head title="Home - AbsolutelyHR" />
            <div className="min-h-dvh bg-[#302F2F] mb-40" data-aos="fade-in">
                <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>

                    <div className=" relative overflow-visible">


                        <div className="mx-auto container px-10 md:px-10">
                            <Header />
                            <H1 text="Schedule Your Strategy Session" color="302F2F" className='mt-10 md:mt-20 capitalize' />

                            {/* Custom Carousel */}
                            <div
                                className="mt-10 swipper relative overflow-hidden"
                                onTouchStart={handleTouchStart}
                                onTouchMove={handleTouchMove}
                                onTouchEnd={handleTouchEnd}
                            >
                                <div
                                    className="flex transition-transform duration-500 ease-in-out"
                                    style={{ transform: `translateX(-${currentSlide * 100}%)` }}
                                >
                                    {slides.map((slide) => (
                                        <div
                                            key={slide.id}
                                            className="w-full flex-shrink-0 flex flex-col md:flex-row items-center justify-between gap-8"
                                        >
                                            {/* Left - Text and Button */}
                                            <div className="flex flex-col items-start md:w-1/3 w-full">
                                                <p className="text-[#302F2F] font-inter text-base font-regular">
                                                    {slide.text}
                                                </p>
                                                <button className="mt-2 font-inter px-7 py-2 text-[#302F2F] font-regular rounded-full border-1 border-[#302F2F] transition-colors hover:bg-[#302F2F] hover:text-white">
                                                    {slide.buttonText}
                                                </button>
                                            </div>
                                            {/* Right - YEARS / EXPERIENCE */}
                                            <div className="flex flex-col items-end md:w-1/3 w-full">
                                                <span className="font-inter font-bold text-base lg:text-2xl text-[#302F2F] leading-[1]">
                                                    {slide.years}
                                                </span>
                                                <span className="font-inter font-normal text-base lg:text-base text-[#302F2F] leading-[1]">
                                                    {slide.experience}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* Dots Indicator */}
                                <div className="flex justify-center gap-2 mt-4">
                                    {slides.map((_, index) => (
                                        <button
                                            key={index}
                                            onClick={() => goToSlide(index)}
                                            className={`w-2 h-2 rounded-full transition-all ${currentSlide === index
                                                ? 'bg-[#302F2F] w-8'
                                                : 'bg-gray-400'
                                                }`}
                                        />
                                    ))}
                                </div>
                            </div>

                            {/* Half-circle with CEO photo, made bigger and moved up into the middle of the text */}
                            <div className="relative w-full flex justify-center" style={{ marginTop: '-80px' }}>
                                {/* Bigger Half-circle background */}
                                {/* Desktop (md and up) - half-circle and CEO photo, hidden on mobile */}
                                <div className="hidden md:flex w-[420px] h-[210px] md:w-[520px] md:h-[260px] bg-[#302F2F] rounded-t-full rounded-b-none mx-auto relative z-10 items-end justify-center">
                                    {/* CEO Photo menempel di bagian bawah */}
                                    <img
                                        src="/assets/ceo.svg"
                                        alt="CEO"
                                        className="w-[420px] h-[420px] md:w-[360px] md:h-[360px] object-cover z-20"
                                        style={{
                                            position: "absolute",
                                            left: "50%",
                                            transform: "translateX(-50%)",
                                        }}
                                    />
                                </div>
                                {/* Mobile (only on mobile view, not absolute, full image only) */}
                                <div className="flex md:hidden w-full justify-center my-6 mt-30">
                                    <img
                                        src="/assets/ceo.svg"
                                        alt="CEO"
                                        className="w-[220px] h-[220px] object-cover"
                                        style={{
                                            borderRadius: "50%",
                                            background: "#302F2F"
                                        }}
                                    />
                                </div>
                                {/* Tombol-tombol di bawah layer CEO, lebih ke atas sedikit */}
                                <div
                                    className="absolute z-30 flex gap-4 rounded-xl py-2 px-3 left-1/2 -translate-x-1/2"
                                    style={{
                                        bottom: '10px',
                                        background: 'linear-gradient(110.97deg, rgba(255, 255, 255, 0.5) -4.87%, rgba(255, 255, 255, 0) 103.95%)',
                                        backdropFilter: 'blur(50px)'


                                    }}
                                >

                                    <button className="text-sm px-10 py-2 rounded-xl bg-[#FFED2E] text-[#302F2F] font-inter font-semibold border  transition-colors shadow-lg">
                                        Start Here
                                    </button>
                                    <button className="text-sm px-10 py-2 rounded-xl  text-white font-inter font-semibold border border-white  transition-colors shadow-lg"
                                        style={{
                                            background: 'linear-gradient(110.97deg, rgba(255, 255, 255, 0.5) -4.87%, rgba(255, 255, 255, 0) 103.95%)'
                                        }}>
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <main className="flex-1">

                    <img
                        src="/assets/gradient-landing.svg"
                        alt=""
                        className="pointer-events-none select-none absolute -z-10   right-0 w-1/4 max-w-lg opacity-60"
                        style={{
                            // Example: appear only in top right, not covering full area
                            objectFit: "contain",
                        }}
                        aria-hidden="true"
                    />
                    <h2 className='font-montserrat font-bold py-10 text-white text-center text-3xl capitlize leading-none' data-aos="fade-up"> Some Fact About Absolutely Human Resources </h2>
                    <div className="container mx-auto px-4 sm:px-6 lg:px-10">

                        <div
                            className="flex flex-col md:flex-row justify-between items-stretch gap-6 md:gap-0 px-4 md:px-20 py-5 mx-auto rounded-xl shadow-lg border-1 border-white"
                            data-aos="zoom-in"
                            style={{ background: '#ffffff4d' }}
                        >
                            {/* Item 1 */}
                            <div className="flex flex-col items-center md:items-start w-full md:w-auto">
                                <span className="font-montserrat font-bold text-2xl md:text-5xl text-white leading-none">30+</span>
                                <span className="font-montserrat font-semibold text-white text-sm md:text-base mt-1 leading-tight text-center md:text-left">Years of Business</span>
                            </div>
                            {/* Separator */}
                            <div className="hidden md:block h-12 border-l-2 border-[#fff] opacity-60 mx-8"></div>
                            <div className="block md:hidden w-10/12 mx-auto h-px border-t-2 border-[#fff] opacity-60 my-2"></div>
                            {/* Item 2 */}
                            <div className="flex flex-col items-center md:items-start w-full md:w-auto">
                                <span className="font-montserrat font-bold text-2xl md:text-5xl text-white leading-none">521+</span>
                                <span className="font-montserrat font-semibold text-white text-sm md:text-base mt-1 leading-tight text-center md:text-left">Happy Clients</span>
                            </div>
                            {/* Separator */}
                            <div className="hidden md:block h-12 border-l-2 border-[#fff] opacity-60 mx-8"></div>
                            <div className="block md:hidden w-10/12 mx-auto h-px border-t-2 border-[#fff] opacity-60 my-2"></div>
                            {/* Item 3 */}
                            <div className="flex flex-col items-center md:items-start w-full md:w-auto">
                                <span className="font-montserrat font-bold text-2xl md:text-5xl text-white leading-none">15+</span>
                                <span className="font-montserrat font-semibold text-white text-sm md:text-base mt-1 leading-tight text-center md:text-left">Business Sector's Expertise</span>
                            </div>

                        </div>

                    </div>
                    {/* our sevice */}
                    <div className="container mx-auto px-10" data-aos="zoom-out">
                        <div className="flex flex-col md:flex-row gap-20 mt-10 md:mt-20">
                            {/* Decorative partial background image */}
                            <img
                                src="/assets/gradient-service1.png"
                                alt=""
                                className="pointer-events-none select-none absolute -z-10 top-20 left-30 w-3/5 max-w-xl opacity-60"
                                style={{
                                    // Example: appear only in top right, not covering full area
                                    objectFit: "contain",
                                }}
                                aria-hidden="true"
                            />

                            {/* Kiri: Text */}
                            <div className="md:w-1/2">
                                <H1 text="Our SERVICE" color="white" className="text-start uppercase mb-10" />
                                <p className="font-inter text-white font-regular text-sm md:text-base mt-20">
                                    At Absolutely HR, we provide practical, reliable and people-focused HR support to help small and growing businesses thrive. We work closely with you to understand your needs, resolve challenges quickly and ensure your workforce is protected, supported and compliant. <br />
                                    <br/>
                                    Whether you need help navigating employment issues, improving workplace culture, or strengthening your HR processes, our team is here to guide you with clear advice and hands-on support. From day-to-day HR tasks to more complex matters, we make HR simple, effective and stress-free. <br />
                                    <br/>
                                    Employee Relations · HR Compliance · HR Consultancy · Workplace Improvement

                                </p>
                            </div>
                            {/* Kanan: Gambar */}
                            <div className="md:w-1/2 flex justify-center mt-8 md:mt-0 relative">
                                {/* Kotak dengan teks, sekarang benar-benar di atas gambar dan sedikit keluar ke kiri */}
                                <img
                                    src="/assets/our-service.jpg"
                                    alt="Our Services"
                                    className="rounded-4xl object-contain relative z-10"
                                />
                                <div
                                    className="absolute left-0 bottom-0 md:-left-20 md:bottom-10 z-20 bg-[#FFED2E] bg-opacity-80 rounded-2xl px-5 py-7 flex flex-col items-start shadow-lg"
                                    style={{
                                        transform: "translateY(10%)" // Agak ke atas (lebih sedikit dari 30%)
                                    }}
                                >
                                    <span className="font-inter text-[#302F2F] font-bold text-xs md:text-xs mb-1">
                                        We deal with any human resource issues, leaving our clients to run their business worry free.
                                    </span>

                                </div>
                            </div>
                        </div>
                    </div>
                    {/* Circular Carousel Section - desktop */}
                    <div className="relative w-full h-[600px] justify-center items-center overflow-hidden hidden lg:flex" data-aos="fade-up">
                        <CircularCarousel ref={carouselRef} onSelect={setActiveServiceIndex}>
                            {carouselItems.map((item, index) => (
                                <div
                                    key={index}
                                    className="carousel-card"
                                >
                                    <div
                                        className="carousel-card-image"
                                        style={{
                                            backgroundImage: `url(${item.image})`,
                                        }}
                                    ></div>
                                    <div className="carousel-card-overlay">
                                        <h3 className="carousel-card-title text-inter font-bold text-lg text-center">{item.title}</h3>
                                        <p className="carousel-card-text text-[#302F2F] leading-none tracking-normal text-xs font-normal text-monserat text-center">
                                            {getMaxWords(item.text, 20)}
                                        </p>
                                        <div className="w-full flex justify-center mt-2">
                                            <a
                                                href={`/service/${item.slug ?? ''}`}
                                                className="carousel-card-button px-3 py-2 rounded-xl text-xs inline-flex items-center justify-center"
                                            >
                                                {item.buttonText}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </CircularCarousel>
                    </div>
                    {/* Linear carousel for tablet & mobile */}
                    <div className="w-full overflow-x-auto py-10 px-6 flex lg:hidden" data-aos="fade-up">
                        <div className="flex gap-6 min-w-full">
                            {carouselItems.map((item, index) => (
                                <div
                                    key={index}
                                    className="group relative flex-shrink-0 w-64 rounded-3xl overflow-hidden bg-white/90 transform transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl"
                                >
                                    <div
                                        className="h-40 bg-cover bg-center"
                                        style={{ backgroundImage: `url(${item.image})` }}
                                    />
                                    <div className="p-4">
                                        <h3 className="text-inter font-bold text-base text-[#302F2F] text-center mb-2">
                                            {item.title}
                                        </h3>
                                        <p className="text-[#4B5563] text-xs font-normal text-monserat text-center">
                                            {item.text}
                                        </p>
                                    </div>
                                    {/* Hover overlay */}
                                    <div className="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center px-4 text-center">
                                        <h3 className="text-white text-sm font-bold mb-2">
                                            {item.title}
                                        </h3>
                                        <p className="text-white/90 text-xs mb-3">
                                            {item.text}
                                        </p>
                                        <a
                                            href={`/service/${item.slug ?? ''}`}
                                            className="carousel-card-button px-3 py-2 rounded-xl text-xs inline-flex items-center justify-center"
                                        >
                                            {item.buttonText}
                                        </a>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <div className="text-center" data-aos="fade-up">
                        <H1 text="OUR SERVICES" color="white" className="text-center uppercase mb-10" />
                        <p className='text-inter font-normal text-sm md:text-base text-white'>Lorem ipsum dolor sit amet consectetur adipiscing elit. <br />
                            Quisque faucibus ex sapien vitae pellentesque sem placerat.</p>
                        <div className="flex justify-center mt-6">
                            <ExploreButton href="/services">
                                EXPLORE ALL SERVICES
                            </ExploreButton>
                        </div>
                    </div>
                    <img
                        src="/assets/gradient-landing3.svg"
                        alt=""
                        className="pointer-events-none select-none absolute -z-10  left-0 w-1/4 max-w-lg opacity-60"
                        style={{
                            // Example: appear only in top right, not covering full area
                            objectFit: "contain",
                        }}
                        aria-hidden="true"
                    />
                    <div className="container mx-auto px-10 md:px-20 py-20 mt-20" data-aos="fade-up">

                        <div className="flex flex-col md:flex-row justify-center gap-12 my-16 ">
                            {/* Kiri: Text */}
                            <div className="md:w-1/2 w-full flex justify-center items-center">
                                <H1 text="Over 29 Years of Experience in HR & Recruitment" className='text-start leading-10' color="white" />
                            </div>

                            {/* PERUBAHAN: Ganti bagian list lama dengan komponen ScrollActiveList */}
                            <ScrollActiveList />
                        </div>
                    </div>

                    <div className="py-20">
                        <div className="" data-aos="fade-up">

                            <H1 text="TESTIMONIALS" color="white" className="text-center uppercase mb-10" />
                        </div>
                        <div
                            className="w-full overflow-hidden py-20 cursor-grab active:cursor-grabbing"
                            data-aos="fade-up"
                            ref={testimonialsRef}
                            onMouseDown={handleTestimonialsMouseDown}
                        >
                            <div className="flex gap-12 ">
                                {loopedTestimonials.map((testimonial, idx) => (
                                    <div
                                        key={testimonial.id ? `${testimonial.id}-${idx}` : `testimonial-${idx}`}
                                        className="relative rounded-2xl min-w-[90px] max-w-[230px] p-4 md:rounded-3xl md:min-w-[420px] md:max-w-md md:p-10 flex-shrink-0"
                                        style={{
                                            background: 'linear-gradient(110.97deg, rgba(255, 255, 255, 0.5) -4.87%, rgba(255, 255, 255, 0) 103.95%)',
                                        }}
                                    >
                                        {/* Avatar anchored on left top, half above card */}
                                        <div className="absolute -top-10 left-8">
                                            <div className="w-20 h-20 rounded-full  shadow-lg overflow-hidden bg-gray-200">
                                                <img
                                                    src={testimonial.image ? `/storage/${testimonial.image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(testimonial.name ?? 'User')}`}
                                                    alt={testimonial.name ?? 'User avatar'}
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>
                                        </div>
                                        {/* Card Content */}
                                        <div className="pt-16">
                                            <p className="text-base text-white font-inter font-normal mb-4">
                                                {testimonial.description}
                                            </p>
                                            <h4 className="font-bold  font-inter text-lg text-white mb-2">
                                                {testimonial.name}
                                            </h4>
                                            <span className="block  font-inter text-sm text-white mb-4">
                                                {testimonial.job_title}
                                            </span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Swiper slider: desktop only */}
                    <img
                        src="/assets/gradient-landing-4.svg"
                        alt=""
                        className="pointer-events-none select-none  hidden md:block absolute bottom-150 z-10 mb-30 right-30 w-2/6 max-w-2xl "
                        style={{
                            // Example: appear only in top right, not covering full area
                            objectFit: "contain",
                        }}
                        aria-hidden="true"
                    />
                    <div data-aos="fade-up" className="hidden lg:block">
                        <SwiperSlider news={news} />
                    </div>
                    {/* Simple horizontal slider for tablet & mobile */}
                    <div data-aos="fade-up" className="block lg:hidden">
                        <div className="w-full overflow-x-auto py-10 px-6">
                            <div className="flex gap-6">
                                {news.map((item) => {
                                    const image =
                                        item.image_url ??
                                        'https://placehold.co/600x400?text=No+Image';

                                    return (
                                        <div
                                            key={item.id}
                                            className="group relative flex-shrink-0 w-64 h-40 rounded-3xl overflow-hidden bg-cover bg-center transform transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl"
                                            style={{ backgroundImage: `url('${image}')` }}
                                        >
                                            <div className="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center px-4 text-center">
                                                <h3 className="text-white text-sm font-bold mb-2">
                                                    {item.title}
                                                </h3>
                                                <p className="text-white/90 text-xs mb-3">
                                                    {item.meta_description ?? item.content ?? ''}
                                                </p>
                                                <a
                                                    href={`/news/${item.slug ?? ''}`}
                                                    className="carousel-card-button px-3 py-2 rounded-xl text-xs inline-flex items-center justify-center"
                                                >
                                                    Read more
                                                </a>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                    <div className="flex justify-center py-3 mb-5">

                        <ExploreButton href="/news">
                            EXPLORE ALL NEWS
                        </ExploreButton>
                    </div>

                </main>
                <Footer />
            </div>
        </>
    );
}
