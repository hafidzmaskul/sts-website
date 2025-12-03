import React from 'react';

export default function CategoryCard({ title, image, showFooter, isDimmed }) {
  const baseHeight = showFooter ? 'h-[380px]' : 'h-[340px]';
  const dimClasses = isDimmed ? 'scale-[0.95] opacity-60 blur-[1.5px]' : 'scale-100 opacity-100';

  return (
    <article
      className={`relative  overflow-hidden rounded-xl bg-white  transition-all duration-500 ${baseHeight} ${dimClasses}`}
    >
        <div className="p-8">
        <img
          src={image}
          alt={title}
          className=" object-contain "
          loading="lazy"
        />
        </div>

      {showFooter ? (
        <div className="absolute inset-x-0 bottom-0 flex h-[20%] items-center bg-[#4D4D4D] px-4">
            <div className="">

          <span className="text-lg font-semibold text-white">{title}</span> <br />
          <span className="text-xs font-inter font-light text-white">200 product</span>
            </div>
        </div>
      ) : (
        <div className="absolute bottom-4 left-4 right-4 text-white drop-shadow-lg">
          <p className="text-lg font-semibold">{title}</p>
        </div>
      )}
    </article>
  );
}
