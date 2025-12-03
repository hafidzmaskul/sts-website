import React, { useEffect, useRef } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';
import ProductCard from './ProductCard';
import 'swiper/css';

export default function FeaturedProductsSection({
  products,
  title,
  titleSize = 'text-2xl',
  slidesPerView,
  sectionId = 'product',
}) {
  const productSwiperRef = useRef(null);

  const maxSlidesPerView = Math.max(1, slidesPerView);
  const shouldLoopProducts = products.length > maxSlidesPerView;

  useEffect(() => {
    if (productSwiperRef.current) {
      productSwiperRef.current.slideTo(0);
    }
  }, [products.length]);

  const goToNextProduct = () => {
    productSwiperRef.current?.slideNext();
  };

  const goToPreviousProduct = () => {
    productSwiperRef.current?.slidePrev();
  };

  return (
    <>
      <div className="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div className="flex items-center gap-3">
          <h1 className={`font-inter font-semibold ${titleSize}`}>{title}</h1>
        </div>
        <div className="flex items-center gap-2 overflow-x-auto">
          <button
            type="button"
            onClick={goToPreviousProduct}
            className="inline-flex h-11 w-11 items-center justify-center border border-[#D5D9DF] text-[#1E1E1E] transition hover:bg-[#0079C2] hover:text-[#fff]"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path
                  fill="none"
                  stroke="currentColor"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="m6 8l-4 4l4 4m-4-4h20"
                />
              </svg>
            </svg>
          </button>
          <button
            type="button"
            onClick={goToNextProduct}
            className="inline-flex h-11 w-11 items-center justify-center border border-[#D5D9DF] text-[#1E1E1E] transition hover:bg-[#0079C2] hover:text-[#fff]"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width={24} height={24} viewBox="0 0 24 24">
              <path
                fill="none"
                stroke="currentColor"
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="m18 8l4 4l-4 4M2 12h20"
              />
            </svg>
          </button>
        </div>
      </div>

      <Swiper
        modules={[Autoplay]}
        onSwiper={(swiperInstance) => {
          productSwiperRef.current = swiperInstance;
        }}
        loop={shouldLoopProducts}
        slidesPerView={maxSlidesPerView}
        spaceBetween={18}
        speed={650}
        autoplay={{
          delay: 4200,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }}
        breakpoints={{
          0: {
            slidesPerView: Math.min(maxSlidesPerView, 2),
          },
          768: {
            slidesPerView: Math.min(maxSlidesPerView, 3),
          },
          1024: {
            slidesPerView: Math.min(maxSlidesPerView, slidesPerView),
          },
        }}
        className="!pb-4"
      >
        {products.map((product, index) => (
          <SwiperSlide key={product.id ?? index} className="!h-auto">
            <ProductCard
              title={product.title}
              price={product.price}
              image={product.image}
              badge={product.badge}
            />
          </SwiperSlide>
        ))}
      </Swiper>
    </>
  );
}
