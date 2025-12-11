import React from 'react';

export default function HeroSection({
  bgUrl,
  title,
  text,
  textColor = 'text-white',
  textSize = 'text-5xl md:text-8xl',
  textAlign = 'start' // opsi baru, default 'start'
}) {
  // mapping prop agar sesuai dengan class flex Tailwind
  const alignClass = textAlign === 'end' ? 'items-end text-right' : 'items-start text-left';

  return (
    <section
      id="hero"
      className="h-[40vh]"
      style={{
        backgroundImage: `url('${bgUrl}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      <div className={`container mx-auto px-5 md:px-10 lg:px-10 h-full flex flex-col justify-center ${alignClass} py-10 `}>
        <h1 className={`font-inter font-semibold ${textSize} mb-4 ${textColor} w-3/5`}>
          {title}
        </h1>
        {text && (
          <p className={`${textColor} text-lg w-2/6`}>
            {text}
          </p>
        )}
      </div>
    </section>
  );
}

