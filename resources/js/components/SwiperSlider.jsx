import React, { useEffect, useRef } from 'react';
import H1 from './H1';

const SwiperSlider = ({ topText = 'TOP TEXT', bottomText = 'BOTTOM TEXT' }) => {
    const swiperContainerRef = useRef(null);

    useEffect(() => {
        let swiperInstance = null;
        let styleEl = null;

        // Dynamically import Swiper JS & CSS
        if (typeof window !== 'undefined') {
            import('swiper/bundle').then((SwiperModule) => {
                import('swiper/css/bundle');
                const Swiper = SwiperModule.default;

                if (swiperContainerRef.current) {
                    swiperInstance = new Swiper(swiperContainerRef.current, {
                        loop: true,
                        grabCursor: true,
                        slidesPerView: 5,
                        spaceBetween: 30,
                        speed: 800
                    });
                }
            });

            // Inline style for slider shadow masking
            styleEl = document.createElement('style');
            styleEl.innerHTML = `
        .slider {
          width: 100%;
          height: 20vw;
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
          padding: 30px;
        }
        .snapper {
          width: 100%;
          height: 100%;
          background-size: cover;
          background-position: center;
        }
        .slider-text-overlay {
          position: absolute;
          width: 500%;
          height: 500%;
          background-color: #302F2F;
          z-index: 2;
          border-radius: 50%;
          left: 50%;
          transform: translateX(-50%);
          pointer-events: none;
          display: flex;
          justify-content: center;
          align-items: center;
          font-size: 2.5vw;
          font-family: inherit;
          font-weight: bold;
          color: #fff;
          text-shadow: 0 2px 8px #222;
        }
        .slider-text-overlay.top {
          top: -480%;
          background-image: url('/your-image.png');
          background-repeat: no-repeat;
          background-position: center top;
          background-size: 150px auto;
          text-align: center;
          padding-top: 170px; /* To move text below image */
        }
        .slider-text-overlay.bottom {
          bottom: -480%;
        }
      `;
            document.head.appendChild(styleEl);
        }

        return () => {
            if (swiperInstance && swiperInstance.destroy) {
                swiperInstance.destroy();
            }
            if (styleEl) {
                document.head.removeChild(styleEl);
            }
        };
    }, []);

    // Images array
    const images = [
        '/294-1200x800.jpg',
        '/294-1200x800.jpg',
        '/294-1200x800.jpg',
        '/294-1200x800.jpg',
        '/294-1200x800.jpg',
        '/294-1200x800.jpg',
    ];

    return (
        <section className="slider">
            {/* Text overlay di atas slider */}
            <div className="slider-text-overlay text-white top ">
                {topText}
            </div>
            <div className="slider-text-overlay bottom text-white">
                {bottomText}
            </div>

            <div className="swiper-container" ref={swiperContainerRef}>
                <div className="swiper-wrapper">
                    {images.map((img, idx) => (
                        <div className="swiper-slide" key={idx}>
                            <div className="snapper" style={{ backgroundImage: `url('${img}')` }} />
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
};

export default SwiperSlider;
