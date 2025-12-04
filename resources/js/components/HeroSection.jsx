import React from 'react';

export default function HeroSection({
  bgUrl,
  title,
  text,
  textColor = 'text-white',
  textSize = 'text-5xl md:text-8xl'
}) {
  return (
    <section
      id="hero"
      className="h-[66vh]"
      style={{
        backgroundImage: `url('${bgUrl}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }}
    >
      <div className="container mx-auto px-6 md:px-10 lg:px-20 h-full flex flex-col justify-center items-start py-10 space-y-8">
        <h1 className={`font-inter font-semibold  ${textSize} mb-4 ${textColor}  w-3/5`}>
          {title}
        </h1>
        {text && (
          <p className={`${textColor} text-lg  w-3/5`}>
            {text}
          </p>
        )}
      </div>
    </section>
  );
}

