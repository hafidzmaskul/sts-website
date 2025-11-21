import React, { useEffect, useRef } from 'react';

const SwiperSlider = ({
    news = [],
    topText = 'DISCOVER',
    bottomText = 'LATEST NEWS',
}) => {
    const swiperContainerRef = useRef(null);
    const swiperDirectionRef = useRef(1);

    useEffect(() => {
        let swiperInstance = null;
        let styleEl = null;
        let autoplayInterval = null;

        // Dynamically import Swiper JS & CSS
        if (typeof window !== 'undefined') {
            import('swiper/bundle').then((SwiperModule) => {
                import('swiper/css/bundle');
                const Swiper = SwiperModule.default;

                if (swiperContainerRef.current) {
                    swiperInstance = new Swiper(swiperContainerRef.current, {
                        loop: false,
                        grabCursor: true,
                        slidesPerView: 5,
                        spaceBetween: 16,
                        speed: 800,
                        centeredSlides: true,
                        on: {
                            reachEnd() {
                                swiperDirectionRef.current = -1;
                            },
                            reachBeginning() {
                                swiperDirectionRef.current = 1;
                            },
                        },
                    });

                    // Start with the third item centered so the left side isn't empty
                    if (swiperInstance.slides.length > 3) {
                        swiperInstance.slideTo(2, 0);
                    }

                    autoplayInterval = setInterval(() => {
                        if (!swiperInstance) {
                            return;
                        }
                        const direction = swiperDirectionRef.current;

                        if (direction === 1) {
                            if (swiperInstance.isEnd) {
                                swiperDirectionRef.current = -1;
                                swiperInstance.slidePrev();
                            } else {
                                swiperInstance.slideNext();
                            }
                        } else {
                            if (swiperInstance.isBeginning) {
                                swiperDirectionRef.current = 1;
                                swiperInstance.slideNext();
                            } else {
                                swiperInstance.slidePrev();
                            }
                        }
                    }, 5000);
                }
            });

            // Inline style for slider shadow masking
            styleEl = document.createElement('style');
            styleEl.innerHTML = `
                    .slider {
                        width: 100%;
                        height: 40vw;
                        overflow: hidden;
                        perspective: 100px;
                        position: relative;
                    }
                    .swiper-container {
                        width: 100%;
                        height: 100%;
                    }
                    .swiper-slide {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        transition: transform 0.3s ease;
                    }
                    .swiper-slide-active {
                        transform: translateY(-6px);
                    }
                    .swiper-slide:hover {
                        transform: translateY(-6px);
                    }
                    .slide-wrapper {
                        position: relative;
                        width: 100%;
                        height: 100%;
                        border-radius: 32px;
                        overflow: hidden;
                    }
                    .snapper {
                        width: 100%;
                        height: 100%;
                        background-size: cover;
                        background-position: center;
                        border-radius: inherit;
                    }
                    .slide-overlay {
                        position: absolute;
                        inset: 0;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        padding: 1.5rem;
                        text-align: center;
                        background: linear-gradient(180deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.85));
                        color: #fff;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }
                    .swiper-slide-active .slide-overlay {
                        opacity: 1;
                    }
                    .swiper-slide:hover .slide-overlay {
                        opacity: 1;
                    }
                    .slide-overlay-title {
                        font-size: 1.4rem;
                        font-weight: 700;
                        margin-bottom: 0.5rem;
                    }
                    .slide-overlay-text {
                        font-size: 0.95rem;
                        margin-bottom: 1rem;
                        max-width: 80%;
                    }
                    .slide-overlay-button {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0.6rem 1.4rem;
                        border-radius: 9999px;
                        border: none;
                        cursor: pointer;
                        background: #ffffff;
                        color: #111827;
                        font-weight: 600;
                        font-size: 0.9rem;
                        box-shadow: 0 10px 15px rgba(0,0,0,0.25);
                        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    .slide-overlay-button:hover {
                        background: #f3f4f6;
                        transform: translateY(-1px);
                        box-shadow: 0 12px 20px rgba(0,0,0,0.3);
                    }
                    .slider-text-overlay {
                        position: absolute;
                        inset: 0;
                        z-index: 2;
                        pointer-events: none;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        font-family: inherit;
                        font-weight: bold;
                        color: #fff;
                    }
                    .slider-text-overlay-inner {
                        display: flex;
                        align-items: center;
                        gap: 1.5rem;
                        width: 100%;
                        margin-inline: auto;
                        justify-content: space-between;
                        padding-inline: 1rem;
                    }
                    @media (min-width: 768px) {
                        .slider-text-overlay-inner {
                            gap: 2.5rem;
                            padding-inline: 3rem;
                        }
                    }
                    @media (min-width: 1024px) {
                        .slider-text-overlay-inner {
                            padding-inline: 4rem;
                        }
                    }
                    .slider-text-main {
                        font-size: 2.5vw;
                    }
                    .slider-text-side {
                        font-size: 0.9vw;
                        opacity: 0.8;
                        padding-inline: 1.5rem;
                        letter-spacing: 0.08em;
                    }
                    .slider-text-overlay::before {
                        content: '';
                        position: absolute;
                        width: 500%;
                        height: 500%;
                        background-color: #302F2F;
                        border-radius: 50%;
                        left: 50%;
                        transform: translateX(-50%);
                        z-index: -1;
                    }
                    .slider-text-overlay.top {
                        justify-content: flex-start;
                        align-items: center;
                        padding-top: 5rem;
                        background-image: url('/your-image.png');
                        background-repeat: no-repeat;
                        background-position: center 1rem;
                        background-size: 10px auto;
                        text-align: center;
                    }
                    .slider-text-overlay.top::before {
                        top: -470%;
                    }
                    .slider-text-overlay.bottom {
                        justify-content: flex-end;
                        align-items: center;
                        padding-bottom: 4rem;
                    }
                    .slider-text-overlay.bottom::before {
                        bottom: -470%;
                    }
                `;
            document.head.appendChild(styleEl);
        }

        return () => {
            if (swiperInstance && swiperInstance.destroy) {
                swiperInstance.destroy();
            }
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
            if (styleEl) {
                document.head.removeChild(styleEl);
            }
        };
    }, []);

    return (
        <section className="slider">
            {/* Text overlay di atas slider */}
            <div className="slider-text-overlay text-white top ">
                <div className="slider-text-overlay-inner">
                    <span className="slider-text-side">Lorem ipsum</span>
                    <span className="slider-text-main">{topText}</span>
                    <span className="slider-text-side">Lorem ipsum</span>
                </div>
            </div>
            <div className="slider-text-overlay bottom text-white">
                <div className="slider-text-overlay-inner">
                    <span className="slider-text-side">Lorem ipsum</span>
                    <span className="slider-text-main">{bottomText}</span>
                    <span className="slider-text-side">Lorem ipsum</span>
                </div>
            </div>

            <div className="swiper-container" ref={swiperContainerRef}>
                <div className="swiper-wrapper">
                    {news.map((item, idx) => {
                        const image =
                            item.image_url ?? 'https://placehold.co/600x400?text=No+Image';
                        const description =
                            item.meta_description ?? item.content ?? '';

                        return (
                            <div className="swiper-slide" key={idx}>
                            <div className="slide-wrapper">
                                <div
                                    className="snapper"
                                    style={{ backgroundImage: `url('${image}')` }}
                                />
                                <div className="slide-overlay">
                                    <div className="slide-overlay-title">
                                        {item.title}
                                    </div>
                                    <div className="slide-overlay-text">
                                        {description}
                                    </div>
                                    <a
                                        href={`/news/${item.slug ?? ''}`}
                                        className="slide-overlay-button"
                                    >
                                        Read more
                                    </a>
                                </div>
                            </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
};

export default SwiperSlider;
