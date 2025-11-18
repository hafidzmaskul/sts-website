import React, { useState, useEffect } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import CircularCarousel from '../components/CircularCarousel';
import SwiperSlider from '../components/SwiperSlider';
// PERUBAHAN: Impor komponen ScrollActiveList yang baru
import ScrollActiveList from '../components/ScrollActiveList';
import ExploreButton from '../components/ExploreButton';

export default function Landing({ sliderImage }) {
    const [currentSlide, setCurrentSlide] = useState(0);

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
    const carouselItems = [
        {
            title: "Strategic Planning",
            text: "Comprehensive business strategy development and implementation planning.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        },
        {
            title: "HR Consulting",
            text: "Expert human resources consulting for optimal workforce management.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        },
        {
            title: "Talent Acquisition",
            text: "Strategic recruitment and talent acquisition solutions for your business.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        },
        {
            title: "Leadership Development",
            text: "Customized leadership training programs to enhance management skills.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        },
        {
            title: "Performance Management",
            text: "Effective performance evaluation and improvement systems.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        },
        {
            title: "Organizational Design",
            text: "Optimize your organizational structure for maximum efficiency.",
            image: "/assets/our-service.jpg",
            buttonText: "Next"
        }
    ];


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

    return (
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
                <div className="container mx-auto px-10" data-aos="fade-up">
                    <div className="flex flex-col md:flex-row gap-20 mt-10 md:mt-20">
                        {/* Kiri: Text */}
                        <div className="md:w-1/2">
                            <H1 text="Our SERVICE" color="white" className="text-start uppercase mb-10" />
                            <p className="font-inter text-white font-regular text-sm md:text-base mt-20">
                                Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                Quisque faucibus ex sapien vitae pellentesque sem placerat.
                                In id cursus mi pretium tellus duis convallis. Tempus leo eu
                                aenean sed diam urna tempor. Pulvinar vivamus fringilla
                                lacus nec metus bibendum egestas. Iaculis massa nisl
                                malesuada lacinia integer nunc posuere.
                            </p>
                        </div>
                        {/* Kanan: Gambar */}
                        <div className="md:w-1/2 flex justify-center mt-8 md:mt-0">
                            <img
                                src="/assets/our-service.jpg"
                                alt="Our Services"
                                className="rounded-4xl object-contain"
                            />
                        </div>
                    </div>
                </div>
                {/* Circular Carousel Section */}
                <div className="relative w-full h-[600px] flex justify-center items-center overflow-hidden" data-aos="fade-up">
                    <CircularCarousel>
                        {carouselItems.map((item, index) => (
                            <div key={index} className="carousel-card">
                                <div
                                    className="carousel-card-image"
                                    style={{
                                        backgroundImage: `url(${item.image})`,
                                    }}
                                ></div>
                                <div className="carousel-card-overlay">
                                    <h3 className="carousel-card-title text-inter font-bold text-lg text-center">{item.title}</h3>
                                    <p className="carousel-card-text text-[#302F2F] leading-none tracking-normal text-xs font-normal text-monserat text-center">{item.text}</p>
                                    <div className="w-full flex justify-center mt-2">
                                        <button className="carousel-card-button px-3 py-2 rounded-xl text-xs">{item.buttonText}</button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </CircularCarousel>
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
                <div className="container mx-auto px-10 md:px-20" data-aos="fade-up">
                    <div className="flex flex-col md:flex-row justify-center gap-12 my-16 ">
                        {/* Kiri: Text */}
                        <div className="md:w-1/2 w-full flex flex-col justify-center">
                            <H1 text="Over 29 Years of Experience in HR & Recruitment" color="white" />
                        </div>
                        {/* PERUBAHAN: Ganti bagian list lama dengan komponen ScrollActiveList */}
                        <ScrollActiveList />
                    </div>
                </div>

                <div className="">
                <H1 text="TESTIMONIALS" color="white" className="text-center uppercase mb-10" data-aos="fade-up" />
                <div className="w-full overflow-x-auto py-20" data-aos="fade-up">
                    <div className="flex gap-12 ">
                        {[1, 2, 3,4,5,6,7,8,9].map((testimonial, idx) => (
                            <div
                                key={idx}
                                className="relative -60 rounded-3xl  min-w-[420px] max-w-md flex-shrink-0 p-10"
                                style={{
                                    background: 'linear-gradient(110.97deg, rgba(255, 255, 255, 0.5) -4.87%, rgba(255, 255, 255, 0) 103.95%)',
                                }}
                            >
                                {/* Avatar anchored on left top, half above card */}
                                <div className="absolute -top-10 left-8">
                                    <div className="w-20 h-20 rounded-full  shadow-lg overflow-hidden bg-gray-200">
                                        <img
                                            src={`https://randomuser.me/api/portraits/men/${10 + idx}.jpg`}
                                            alt="User avatar"
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                </div>
                                {/* Card Content */}
                                <div className="pt-16">

                                    <p className="text-base text-white font-inter font-normal mb-4">
                                        “Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris egestas, elit non blandit ultricies, erat nunc cursus odio, at pharetra neque tellus id erat.”

                                    </p>
                                    <h4 className="font-bold  font-inter text-lg text-white mb-2">John Doe</h4>
                                    <span className="block  font-inter text-sm text-white mb-4">CEO, Example Company</span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                </div>


                <div data-aos="fade-up">
                    <SwiperSlider />
                </div>
            </main>
            <Footer />
        </div>
    );
}
