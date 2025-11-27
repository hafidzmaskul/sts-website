import React, { useMemo } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function Career({ jobs = [] }) {
    const preparedJobs = useMemo(
        () => (Array.isArray(jobs) && jobs.length > 0 ? jobs : []),
        [jobs],
    );
    const stripHtml = (value) => (typeof value === 'string' ? value.replace(/<[^>]+>/g, '') : '');

    return (
        <>
            <Head title="Recruitment - Absolutely Human Resources" />
            <div className="bg-[#302F2F]" data-aos="fade-in">
                <div
                    className="py-10 rounded-b-xl md:rounded-b-[200px] overflow-hidden"
                    style={{
                        background:
                            'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                    }}
                >
                    <Header />
                    <div className="container px-10 md:px-20 mx-auto mt-12">


                        <div
                            className="w-full overflow-hidden mx-auto"
                            style={{
                                maxWidth: '100%',
                                maxHeight: '420px',
                                borderRadius: '2rem',
                            }}
                        >
                            <img
                                src="/assets/carerr.jpg"
                                alt="Career opportunities"
                                className="w-full h-full object-cover"
                                style={{
                                    display: 'block',
                                    maxHeight: '420px',
                                    height: '100%',
                                    width: '100%',
                                    objectFit: 'cover',
                                    borderRadius: 'inherit',
                                }}
                            />
                        </div>
                    </div>
                </div>
                <div className="container px-10 md:px-20 mx-auto mt-12 text-center flex flex-col items-center">
                    <H1 text="Recruitment" color="white" className="uppercase text-center mb-20" />
                    <p className="text-sm md:text-base leading-relaxed text-white font-roboto  text-center">
                        Lorem ipsum dolor sit amet consectetur. Bibendum mauris commodo scelerisque id suspendisse viverra integer et sed. Donec diam purus sed velit etiam purus morbi. Id tristique amet lobortis amet eu. Mattis bibendum sagittis vel fermentum nunc tortor. Dis platea egestas quis consequat mauris adipiscing.
                    </p>
                </div>
                <img
                    src="/assets/gradient-service-detail.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-60 left-0 w-1/4 max-w-lg opacity-60"
                    style={{
                        objectFit: 'contain',
                    }}
                    aria-hidden="true"
                />

                <img
                    src="/assets/gradient-service-detail2.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-200 right-0 w-1/4 max-w-lg opacity-60"
                    style={{
                        objectFit: 'contain',
                    }}
                    aria-hidden="true"
                />

                <main className="container mx-auto px-10 md:px-10 py-16 space-y-16" data-aos="fade-up">
                    <section className="space-y-8">

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {preparedJobs.map((job) => (
                                <div
                                    key={job.id}
                                    className="p-5 backdrop-blur-[70px] rounded-4xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col"
                                    style={{
                                        background:
                                            'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                    }}
                                >
                                    <div
                                        className="px-6 rounded-4xl pt-6 pb-4 flex flex-col flex-1"
                                        style={{
                                            boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                            background:
                                                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                        }}
                                    >
                                        <div className="items-start gap-3 w-2/3">
                                            <h3 className="text-xl font-bold text-white leading-tight">
                                                {job.title}
                                            </h3>
                                            <div className="flex flex-wrap  items-center gap-2 mb-2">
                                                {job.level && (
                                                    <span className="text-[11px] uppercase  tracking-wide bg-[#E3E3E3] text-black px-2 py-1 rounded-full font-semibold text-center">
                                                        {job.level}
                                                    </span>
                                                )}
                                                {job.employment_type && (
                                                    <span className="text-[11px] uppercase  tracking-wide bg-[#E3E3E3] text-black px-2 py-1 rounded-full font-semibold text-center">
                                                        {job.employment_type}
                                                    </span>
                                                )}

                                            </div>
                                            <div className="flex flex-wrap gap-2">
                                                {(job.department || job.category) && (
                                                    <span className="text-[11px] uppercase tracking-wide bg-[#E3E3E3] text-black px-2 py-1 rounded-full font-semibold text-center">
                                                        {job.department || job.category}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                        <p
                                            className="mt-4 text-sm text-white/80 leading-relaxed flex-1 rich-text"
                                            dangerouslySetInnerHTML={{ __html: ((job.description ?? job.summary ?? '').slice(0, 350) + ((job.description ?? job.summary ?? '').length > 350 ? '...' : '')) }}
                                        />
                                        <a
                                            href={`/career/${job.slug ?? job.id}`}
                                            className="mt-6 inline-flex items-center justify-center gap-2 text-black px-6 py-1 rounded-full font-semibold shadow hover:bg-[#FFED2E] transition" style={{ background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)' }}
                                        >
                                            See Detail
                                        </a>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                </main>

                <Footer />
            </div>
        </>
    );
}
