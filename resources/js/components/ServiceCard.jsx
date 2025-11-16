import React from 'react';

export default function ServiceCard({ index, isLoading, uuid }) {
  return (
    <div
      className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col"
      style={{
        background:
          'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
      }}
      data-uuid={uuid}
    >
      {isLoading ? (
        <>
          <div className="w-full h-48 mb-3 rounded-2xl bg-gray-200/70 animate-pulse" />
          <div
            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
            style={{
              boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
              background:
                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
            }}
          >
            <div className="h-5 w-2/3 mb-3 rounded bg-gray-200/80 animate-pulse" />
            <div className="space-y-2 mb-6 flex-1">
              <div className="h-3 w-full rounded bg-gray-200/70 animate-pulse" />
              <div className="h-3 w-5/6 rounded bg-gray-200/70 animate-pulse" />
              <div className="h-3 w-4/6 rounded bg-gray-200/70 animate-pulse" />
            </div>
            <div className="flex justify-center">
              <div className="h-9 w-24 rounded-lg bg-gray-200/80 animate-pulse" />
            </div>
          </div>
        </>
      ) : (
        <>
          <img
            src="assets/our-service.jpg"
            alt={`Service ${index}`}
            className="w-full h-48 mb-3 object-cover rounded-2xl"
          />
          <div
            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
            style={{
              boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
              background:
                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
            }}
          >
            <h2 className="text-lg md:text-xl text-black font-semibold mb-2">
              Service Title {index}
            </h2>
            <p className="text-black text-xs md:text-sm mb-6 flex-1">
              Every business needs good HR support, but as an SME you do not need the costs and
              overheads of an internal HR department.We offer a 24 hour advice for our Edinburgh
              clients. Our staff will be able to listen and provide immediate support whatever the
              scale of your problem.
            </p>
            <div className="flex justify-center">
              <a
                href={`/service/${uuid || ''}`}
                className="bg-white text-black px-6 py-2 rounded-lg font-semibold shadow hover:bg-gray-200 transition inline-flex items-center justify-center"
              >
                Next
              </a>
            </div>
          </div>
        </>
      )}
    </div>
  );
}

