import React from 'react';

export default function AboutArticleCard({
  image,
  title,
  description,
  buttonLabel = 'Learn More',
  buttonHref = '#',
  additionnal
}) {
  return (
    <div className="flex flex-col w-full max-w-4xl overflow-hidden rounded-xl bg-white shadow-sm h-[700px] md:h-[800px] lg:h-[900px]">
      {/* Atas: Image as Background */}
      <div
        className="w-full bg-gray-100 flex items-center justify-center relative bg-center bg-no-repeat bg-cover"
        style={{
          backgroundImage: `url(${image})`,
          height: '45%',
        }}
        aria-label={title}
        role="img"
      />
      {/* Bawah: Text */}
      <div className="flex flex-col space-y-4 px-6 py-6 md:px-10 md:py-8 bg-[#0079C2] flex-1" style={{ minHeight: '40%' }}>
        <h2 className="font-inter text-2xl md:text-3xl font-semibold text-white">
          {title}
        </h2>
        <p className="font-nunito-sans text-base md:text-lg text-white ">
          {description}
        </p>
        <div className="py-4">
          <a
            href={buttonHref}
            className="w-full items-center justify-center rounded-lg bg-white px-6 py-3 text-sm font-semibold text-[#0079C2] transition hover:bg-gray-100"
          >
            {buttonLabel}
          </a>
        </div>
        {additionnal && (
          <div className="additional">
            <p className='font-inter text-xl text-white'>Already an STS customer?</p>
            <span className='font-poppins text-sm text-white'> <a href="#" className='underline'>Register</a> or an online account and discover the <a href="" className='underline'>benefits.</a>  </span>
          </div>
        )}
      </div>
    </div>
  );
}

