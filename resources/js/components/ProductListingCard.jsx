import React, { useState } from 'react';
import LoginModal from './LoginModal';

export default function ProductListingCard({
    image,
    name,
    brand,
    series,
    badge,
    priceLabel,
    slug,
    showPricing,
}) {
    const [isLoginModalOpen, setIsLoginModalOpen] = useState(false);

    return (
        <>
            <LoginModal
                isOpen={isLoginModalOpen}
                onClose={() => setIsLoginModalOpen(false)}
            />
            <article className="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white relative">
                {/* Badge now positioned relative to card, not image */}
                {badge && (
                    <span className="absolute left-0 top-0 inline-flex items-center rounded-br-lg  bg-[#0079C2] px-3 py-2 text-xs font-light text-white  z-10">
                        {badge}
                    </span>
                )}
                <div className="p-3 pb-0">
                    <div>
                    <a href={`/products/${slug}`} >
                        <img
                            src={image}
                            alt={name}
                            className="h-48 w-full rounded-lg object-contain"
                            loading="lazy"
                        />
                        </a>
                    </div>
                </div>
                <div className="flex flex-1 flex-col gap-1 px-3 pb-4 pt-4">
                    <span className="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">
                        {brand}
                    </span>
                     <a href={`/products/${slug}`} >
                        <span className="text-sm font-semibold text-[#232323] mb-2">
                            {name}
                        </span>
                    <span className="text-xs text-gray-500">
                        STS: {series}
                    </span>
                    <span className="text-xs text-gray-500">
                        Model : {series}
                    </span>
                    </a>

                    <div className="">
                        <button
                            type="button"
                            className="mt-2 text-xs mb-3 font-medium item-start"
                        >
                            More Options Available
                        </button>
                    </div>

                    {showPricing ? (
                        <button
                            type="button"
                            onClick={() => setIsLoginModalOpen(true)}
                            className="w-full text-center rounded-lg border border-[#0079C2] bg-[#F0F2F3] px-3 py-2 text-xs font-semibold text-[#0079C2] hover:bg-[#E1E6E8] transition-colors"
                        >
                            Login to View Price
                        </button>
                    ) : (
                        <div className="mt-auto">
                            <span className="text-lg font-bold text-[#232323]">
                                {priceLabel}
                            </span>
                        </div>
                    )}

                    
                </div>
            </article>
        </>
    );
}

