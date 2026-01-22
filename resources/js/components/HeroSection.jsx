import React from 'react';

export default function HeroSection({
  bgUrl,
  title,
  text,
  textColor = 'text-white',
  textSize = 'text-4xl sm:text-5xl md:text-7xl lg:text-8xl',
  textAlign = 'start' // opsi baru, default 'start'
}) {
  // mapping prop agar sesuai dengan class flex Tailwind
  const alignClass =
    textAlign === 'end'
      ? 'items-end text-right'
      : 'items-start text-left';

  return (
    <section
      id="hero"
      className="h-[30vh] sm:h-[35vh] md:h-[40vh]"
      style={{
        backgroundImage: `url('${bgUrl}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      <div
        className={`container mx-auto px-4 sm:px-6 md:px-10 lg:px-10 h-full flex flex-col justify-center ${alignClass} py-7 sm:py-10`}
      >
        <h1
          className={`font-inter font-semibold ${textSize} mb-3 sm:mb-4 ${textColor} w-full sm:w-4/5 md:w-3/5`}
        >
          {title}
        </h1>
        {text && (
          <p
            className={`${textColor} text-base sm:text-lg w-full sm:w-4/6 md:w-2/6`}
          >
            {text}
          </p>
        )}
      </div>
    </section>
  );
}
