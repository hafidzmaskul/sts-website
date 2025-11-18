import React from 'react';
import { Link } from '@inertiajs/react';

export default function ExploreButton({ children, href, onClick }) {
  const baseClasses =
    'group flex items-center justify-center gap-3 rounded-full bg-[#FFED2E] text-[#302F2F] px-6 py-2 text-base font-semibold text-monserat shadow transition-transform duration-200 hover:-translate-y-0.5 hover:shadow-lg';

  const content = (
    <>
      <div className="bg-[#302F2F] rounded-full p-2 transition-transform duration-200 group-hover:translate-x-1">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="10"
          height="10"
          viewBox="0 0 512 512"
          className="text-[#FFED2E] transition-transform duration-200 group-hover:translate-x-0.5"
        >
          <path
            fill="currentColor"
            d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h370.7L297.3 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"
          />
        </svg>
      </div>
      <span className="transition-colors duration-200 group-hover:text-[#1f1f1f]">
        {children}
      </span>
    </>
  );

  if (href) {
    return (
      <Link href={href} className={baseClasses}>
        {content}
      </Link>
    );
  }

  return (
    <button
      type="button"
      onClick={onClick}
      className={baseClasses}
    >
      {content}
    </button>
  );
}
