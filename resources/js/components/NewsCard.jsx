import React from 'react';

export default function NewsCard({ index, isLoading }) {
  return (
    <div className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col bg-white/5">
      {isLoading ? (
        <>
          <div className="w-full h-48 mb-3 rounded-2xl bg-gray-200/20 animate-pulse" />
          <div className="px-2 rounded-2xl pt-4 pb-3 flex flex-col flex-1">
            <div className="h-5 w-2/3 mb-3 rounded bg-gray-200/40 animate-pulse" />
            <div className="space-y-2 mb-6 flex-1">
              <div className="h-3 w-full rounded bg-gray-200/30 animate-pulse" />
              <div className="h-3 w-5/6 rounded bg-gray-200/30 animate-pulse" />
              <div className="h-3 w-4/6 rounded bg-gray-200/30 animate-pulse" />
            </div>
            <div className="flex justify-center">
              <div className="h-9 w-24 rounded-lg bg-gray-200/40 animate-pulse" />
            </div>
          </div>
        </>
      ) : (
        <>
          <img
            src="assets/news-sample.jpg"
            alt={`News ${index}`}
            className="w-full h-48 mb-3 object-cover rounded-2xl"
          />
          <div className="px-2 rounded-2xl pt-4 pb-3 flex flex-col flex-1">
            <h2 className="text-lg md:text-xl text-black font-semibold mb-2">
              News Title {index}
            </h2>
            <p className="text-black text-sm mb-6 flex-1">
              Stay updated with the latest HR insights, legal changes, and practical tips to help
              your organisation manage people with confidence.
            </p>
            <div className="flex justify-center">
              <button className="bg-white text-black px-6 py-2 rounded-lg font-semibold shadow hover:bg-gray-200 transition">
                Read more
              </button>
            </div>
          </div>
        </>
      )}
    </div>
  );
}

