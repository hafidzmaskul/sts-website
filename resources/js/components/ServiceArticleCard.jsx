import React from 'react';

export default function ServiceArticleCard({
    image,
    title,
    text,
    buttonLabel = 'Learn More',
    buttonHref = '#',
    additionnal
}) {
    // Membatasi text hanya 50 karakter
    const limitedText = text && text.length > 200 ? text.slice(0, 200) + '...' : text;

    return (
        <div className="flex flex-col h-full">
            {/* Atas: Image as Background */}
            <img
                src={image}
                alt={title}
                className="flex-1 w-full object-cover bg-gray-100"
            />
            {/* Bawah: Text */}
            <div className="flex-1 flex flex-col space-y-4 px-4 py-6  md:py-8 ">
                <h2 className="font-inter text-2xl md:text-3xl font-bold ">
                    {title}
                </h2>
                <p className="font-inter text-base md:text-md font-medium  ">
                    {limitedText}
                </p>
            </div>
            <div className="px-4 py-4  md:py-6">
                <a
                    href={buttonHref}
                    className=" rounded-lg bg-[#0079C2] px-20 py-3 text-sm font-semibold text-[white] transition"
                >
                    {buttonLabel}
                </a>
            </div>
        </div>
    );
}
