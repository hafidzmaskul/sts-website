import React from 'react';

export default function HeroSection({
  bgUrl,
  title,
  text,
  textColor = 'text-white',
  textSize = 'text-4xl sm:text-5xl md:text-7xl lg:text-8xl',
  textAlign = 'start',
  className = '',
  contentClassName = ''
}) {
  // mapping prop agar sesuai dengan class flex Tailwind
  const alignClass =
    textAlign === 'end'
      ? 'items-start sm:items-end text-left sm:text-right'
      : 'items-start text-left';

  return (
    <section
      id="hero"
      className={`relative min-h-[220px] sm:min-h-[260px] md:min-h-[320px] h-auto py-10 sm:py-12 md:py-16 flex items-center ${className}`}
      style={{
        backgroundImage: `url('${bgUrl}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      {/* Overlay, transparan hitam di atas gambar tapi di bawah konten */}
      <div className="absolute inset-0 bg-black/50 z-0" />
      <div
        className={`container mx-auto px-4 sm:px-6 md:px-10 lg:px-10 h-full flex flex-col justify-center ${alignClass} relative z-10 ${contentClassName}`}
      >
        <h1
          className={`font-inter font-semibold ${textSize} mb-2 sm:mb-4 ${textColor} w-full sm:w-4/5 md:w-3/5 leading-tight`}
        >
          {title}
        </h1>
        {text && (
          <p
            className={`${textColor} text-sm sm:text-base md:text-lg w-full sm:w-5/6 md:w-3/5 lg:w-1/2 mt-1 leading-relaxed`}
          >
            {text}
          </p>
        )}
      </div>
    </section>
  );
}
