import React from 'react';

const formatPrice = (price) => {
  if (price === null || price === undefined) {
    return '';
  }

  if (typeof price === 'number') {
    return new Intl.NumberFormat('en-GB', {
      style: 'currency',
      currency: 'GBP',
      maximumFractionDigits: 0,
    }).format(price);
  }

  return price;
};

// Fungsi untuk menentukan warna badge berdasarkan nilai badge
const getBadgeBgColor = (badge) => {
  if (typeof badge === 'string') {
    if (badge.toLowerCase() === 'new') {
      return '#01AD5A';
    } else if (badge.toLowerCase() === 'sales') {
      return '#F5813F';
    }
  }
  return '#0F172A'; // default
};

export default function ProductCard({
  title,
  price,
  image,
  badge = 'New',
}) {
  const badgeBgColor = getBadgeBgColor(badge);

  return (
    <article className="group relative h-full overflow-hidden rounded-3xl bg-white px-5  pb-6 pt-5 transition-transform duration-500 hover:-translate-y-2">
      <div
        className="absolute left-5 top-5 z-10 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white"
        style={{ backgroundColor: badgeBgColor }}
      >
        {badge}
      </div>

      <button
        type="button"
        className="absolute right-5 top-5 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full transition-colors duration-300 group"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width={24}
          height={24}
          viewBox="0 0 24 24"
          className="block group-hover:hidden"
        >
          <path
            fill="none"
            stroke="#0079C2"
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M19.5 12.572L12 20l-7.5-7.428A5 5 0 1 1 12 6.006a5 5 0 1 1 7.5 6.572"
          />
        </svg>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width={24}
          height={24}
          viewBox="0 0 24 24"
          className="hidden group-hover:block"
        >
          <path
            fill="#0079C2"
            d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037.033l.034-.03a6 6 0 0 1 4.733-1.44l.246.036a6 6 0 0 1 3.364 10.008l-.18.185l-.048.041l-7.45 7.379a1 1 0 0 1-1.313.082l-.094-.082l-7.493-7.422A6 6 0 0 1 6.979 3.074"
          />
        </svg>
      </button>

      <div className="relative overflow-hidden rounded-[22px] ">
        <img
          src={image}
          alt={title}
          className="aspect-[4/5] h-full w-full object-contain transition duration-700 ease-out group-hover:scale-105"
          loading="lazy"
        />
      </div>

      <div className="mt-5 flex items-start justify-between gap-3">
        <div className="">
          <p className="text-base font-normal ">{title}</p>
          <p className="text-base font-bold ">{formatPrice(price)}</p>
        </div>
        <button
          type="button"
          className="inline-flex items-center gap-2 rounded-lg bg-[#EEF2F7] px-4 py-4 text-sm font-semibold text-[#1E1E1E] transition-colors duration-300 hover:bg-[#0079C2] hover:text-white"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width={18}
            height={18}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.6"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            <path d="M17 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-8 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-1.5-7H19l-1 5H9" />
            <path d="m2.5 2.5l2 1L6 14h10.5" />
            <path d="M5 6h16l-2 7H9" />
          </svg>
        </button>
      </div>
    </article>
  );
}
