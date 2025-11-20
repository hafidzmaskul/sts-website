import React, { useEffect } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function AboutUs({ teamMembers }) {
    const members = teamMembers ?? [];
    console.log(members);

    useEffect(() => { }, []);

    return (
        <>
            <Head title="About Us - AbsolutelyHR" />
            <div className="" data-aos="fade-in">
                <div
                    className="relative flex flex-col rounded-b-4xl sm:rounded-b[100px] lg:rounded-b-[100px] overflow-visible"
                    style={{
                        backgroundImage: 'url(/assets/bg.png)',
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                    }}
                >
                    <Header />
                    <div className="w-full flex flex-col items-center text-center mt-20">
                        <H1 text="About Us" color="black" />
                        <p className="text-roboto text-xs md:text-base font-normal mb-10 md:mb-30 mt-10 max-w-md">
                            Learn more about our company, our mission, and the people who make
                            it all possible. We are committed to delivering exceptional HR
                            solutions that empower teams and organizations to thrive.
                        </p>
                    </div>

                    {/* Mobile image is in div, desktop is absolute */}
                    <div className=" md:hidden w-full flex justify-center relative z-10 mt-4 mb-8">
                        <div className="w-[80vw] max-w-xs rounded-4xl overflow-hidden">
                            <img
                                src="/assets/aboutimg.jpg"
                                alt=""
                                className="w-full h-auto rounded-4xl"
                            />
                        </div>
                    </div>
                    <img
                        src="/assets/aboutimg.jpg"
                        alt=""
                        className="
                        hidden md:block
                        absolute left-1/2 transform -translate-x-1/2
                        md:w-3/3 w-[60vw] max-w-lg
                        z-10
                        rounded-4xl
                    "
                        style={{
                            bottom: '-40%',
                        }}
                    />
                </div>
                <img
                    src="/assets/gradient-about.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-200 left-20 w-3/4 max-w-lg "
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />


                <img
                    src="/assets/gradient-about2.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-400 right-0 w-1/4 max-w-lg "
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />
                <main className="flex-1 mt-10 md:mt-30 container mx-auto px-10  md:px-20 py-12 text-white" data-aos="fade-up">
                    <section className="text-center" data-aos="fade-up">

                        <h1 className='font-montserrat mt-5 md:mt-30 font-bold text-3xl md:text-5xl text-white mb-10'>
                            Absolutely Human Resources Limited support clients in their Human Resource and Employment Law needs.
                        </h1>
                        <p className="font-roboto font-normal text-xs md:text-base mb-10">
                            We deal with any Human Resource issues, leaving our clients to run their business worry free. From supporting when there is a single issue to regular monthly help, we can arrange a package to suit your business and its requirements. We can undertake all aspects of HR management from our initial, free, HR “health check” through to the provision, creation and personal delivery of Contracts of Employment and a bespoke Company Handbook.
                        </p>
                        <p className="font-roboto font-normal text-xs md:text-base mb-10">
                            Absolutely Human Resources Limited can become your trusted HR partner, delivering HR Consultancy services as and when required. Small and medium sized enterprises can benefit greatly from this support when they do not need a full time HR professional on the staff. You only get one chance to get it right and the costs are very high for companies when things go wrong! The average Employment Tribunal case costs circa £10,000 win, lose or draw.
                            Absolutely Human Resources ensure that our clients are regularly updated with the current, frequent and ongoing changes to UK Employment Law.
                            Our monthly supported clients also receive our monthly HR and Employment Law update newsletter. Emailed directly to your inbox it contains a summary of any changes each month.
                        </p>
                    </section>
                    <section id="why-choose-us" className="mt-20" data-aos="fade-up">
                        <H1 text={'WHY CHOOSE US'} color="white" />
                        <div className="mt-10 space-y-8">
                            {/* Row 1: left smaller, right larger */}
                            <div className="flex flex-col md:flex-row gap-6">
                                <div className="md:flex-[1]">
                                    <div
                                        className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col h-full"
                                        style={{
                                            background:
                                                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                        }}
                                    >
                                        <div
                                            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
                                            style={{
                                                boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                                background:
                                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                            }}
                                        >
                                            <img src="/assets/experience.svg" className='w-20 -ml-5' alt="" />
                                            <h2 className="text-lg md:text-xl font-inter text-white font-bold mb-2 uppercase">
                                                Experienced Employees
                                            </h2>
                                            <p className="text-white font-montserrat text-sm flex-1">
                                                We have been involved in HR and Recruitment since 1996.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="md:flex-[2]">
                                    <div
                                        className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col h-full"
                                        style={{
                                            background:
                                                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                        }}
                                    >
                                        <div
                                            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
                                            style={{
                                                boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                                background:
                                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                            }}
                                        >
                                            <img src="/assets/range.svg" className='w-20 -ml-5' alt="" />
                                            <h2 className="text-lg md:text-xl font-inter text-white font-bold mb-2 uppercase">
                                                Range of Business Sectors
                                            </h2>
                                            <p className="text-white font-montserrat text-sm flex-1">
                                                We cover all market sectors. A complete A-Z of the business world.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Row 2: right smaller, left larger */}
                            <div className="flex flex-col md:flex-row gap-6">
                                <div className="md:flex-[2] order-2 md:order-1">
                                    <div
                                        className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col h-full"
                                        style={{
                                            background:
                                                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                        }}
                                    >
                                        <div
                                            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
                                            style={{
                                                boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                                background:
                                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                            }}
                                        >
                                            <img src="/assets/dedicate.svg" className='w-20 -ml-5' alt="" />
                                            <h2 className="text-lg md:text-xl font-inter text-white font-bold mb-2 uppercase">
                                                Dedicated Experts
                                            </h2>
                                            <p className="text-white font-montserrat text-sm flex-1">
                                                We can arrange a package to suit your business and its requirements.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="md:flex-[1] order-1 md:order-2">
                                    <div
                                        className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col h-full"
                                        style={{
                                            background:
                                                'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                        }}
                                    >
                                        <div
                                            className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
                                            style={{
                                                boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                                background:
                                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                            }}
                                        >
                                            <img src="/assets/help.svg" className='w-20 -ml-5' alt="" />
                                            <h2 className="text-lg uppercase md:text-xl font-inter text-white font-bold mb-2">
                                                24/7 Helpline
                                            </h2>
                                            <p className="text-white font-montserrat text-sm flex-1">
                                                We will be on-hand whenever we are needed.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    {members.length > 0 && (
                        <section id="our-team" className="mt-30 mb-30" data-aos="fade-up">
                            <H1 text="OUR TEAM" color="white" />

                            <div className="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                                {members.map((member) => {
                                    const imageSrc = member.image && member.image.startsWith('http')
                                        ? member.image
                                        : member.image
                                            ? `/storage/${member.image}`
                                            : '/assets/placeholder-team.png';

                                    return (
                                        <div
                                            key={member.id}
                                            className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col"
                                            style={{
                                                background:
                                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                            }}
                                        >
                                            <div className="w-full h-48 mb-3 rounded-2xl overflow-hidden bg-white/40 flex items-center justify-center">
                                                <img
                                                    src={imageSrc}
                                                    alt={member.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>

                                            <div
                                                className="px-6 rounded-2xl pt-6 pb-4 flex flex-col flex-1"
                                                style={{
                                                    boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                                    background:
                                                        'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                                }}
                                            >
                                                <h3 className="text-lg md:text-xl font-inter  text-black font-bold uppercase">
                                                    {member.name}
                                                </h3>
                                                {member.job_title && (
                                                    <p className=" md:text-sm font-inter  font-bold text-base text-black/70 mb-4 uppercase">
                                                        {member.job_title}
                                                    </p>
                                                )}
                                                {member.email && (
                                                    <a
                                                        href={`mailto:${member.email}`}
                                                        className="flex items-center text-xs hover:underline mb-3 mr-4 font-inter font-semibold"
                                                        title={`Email: ${member.email}`}
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-mail-icon lucide-mail mr-3" ><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" /><rect x="2" y="4" width="20" height="16" rx="2" /></svg>
                                                        {member.email}
                                                    </a>
                                                )}
                                                {member.phone && (
                                                    <span className="flex items-center text-xs mb-3 font-inter font-semibold">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-phone-call-icon lucide-phone-call mr-3"><path d="M13 2a9 9 0 0 1 9 9" /><path d="M13 6a5 5 0 0 1 5 5" /><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" /></svg>
                                                        {member.phone}
                                                    </span>
                                                )}
                                                {member.description && (
                                                    <p className="text-white font-monserat font-normal leading-none text-sm   tracking-wider mb-4 flex-1">
                                                        {member.description}
                                                    </p>
                                                )}


                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </section>
                    )}
                </main>

                <img
                    src="/assets/gradient-about3.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 bottom-100 left-0 w-1/4 max-w-lg "
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />

                <Footer />
            </div>
        </>
    );
}
