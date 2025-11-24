import React, { useMemo } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import { getMaxWords } from '../helpers/text';

const fallbackJobs = [
    {
        id: 1,
        title: 'Senior HR Consultant',
        category: 'HR Consulting',
        summary: 'Lead retainer clients on complex employee relations cases, coach managers through sensitive changes, and deliver compliance audits. You will map processes, create policies, and prepare training that keeps teams confident during transitions. This role mixes strategic advisory with hands-on delivery, so you will spend time in workshops, producing toolkits, and presenting recommendations to stakeholders across the region.',
        description: 'You will partner directly with business owners to deliver pragmatic HR advice and bespoke documentation that keeps them compliant and confident. From disciplinary and grievance investigations to restructuring programmes, you will design people plans that balance empathy and risk. You will also facilitate learning sessions for managers, keep our knowledge base sharp, and ensure our playbooks stay current with the latest employment law changes.',
        location: 'London, United Kingdom',
        type: 'Full Time',
        level: 'Senior',
        department: 'Client Advisory',
        responsibilities: [
            'Diagnose client HR risks and design remediation plans with measurable milestones.',
            'Facilitate workshops and clinics for line managers on people leadership topics.',
            'Create toolkits, policies, and templates that are practical and on-brand.',
        ],
        requirements: [
            'Proven experience leading complex ER cases end to end.',
            'Up-to-date knowledge of UK employment law and best practice.',
            'Strong stakeholder management and presentation skills.',
        ],
    },
    {
        id: 2,
        title: 'People Operations Lead',
        category: 'People Operations',
        summary: 'Own the people ops engine that powers onboarding, payroll inputs, benefits, and HRIS hygiene. You will streamline workflows, remove friction for employees, and ensure data accuracy for reporting. The role needs someone who loves process, automation, and partnering closely with finance to keep everything running smoothly and on time each month.',
        description: 'You will design and maintain the operating rhythm for our people team. From pre-boarding through offboarding, you will refine checklists, automate approvals, and keep documentation consistent. You will be the bridge between HR and finance to reconcile payroll changes, and you will monitor service levels so colleagues feel supported at every touchpoint.',
        location: 'Hybrid - Manchester',
        type: 'Full Time',
        level: 'Mid-Senior',
        department: 'Operations',
        responsibilities: [
            'Standardise onboarding/offboarding flows and automate reminders.',
            'Maintain HRIS data quality and drive adoption across the team.',
            'Coordinate payroll inputs and benefit updates with finance partners.',
        ],
        requirements: [
            'Hands-on HR operations experience in a scaling organisation.',
            'Fluency with HRIS platforms and process automation tools.',
            'A continuous improvement mindset with strong documentation skills.',
        ],
    },
    {
        id: 3,
        title: 'Talent Acquisition Partner',
        category: 'Talent',
        summary: 'Shape the candidate experience from first touch to offer acceptance. You will run end-to-end searches, build diverse pipelines, and coach hiring managers on structured interviews. Storytelling is key: you will translate our value proposition into outreach, events, and content that attracts the right people for each role.',
        description: 'As a Talent Acquisition Partner you will own searches across consulting, operations, and product. You will design scorecards, run inclusive processes, and keep candidates informed at every stage. Beyond filling roles, you will contribute to employer brand campaigns, source at events, and experiment with new channels to reach niche profiles.',
        location: 'Remote, GMT ±2',
        type: 'Contract to Permanent',
        level: 'Mid',
        department: 'Talent',
        responsibilities: [
            'Define role scorecards and interview plans with hiring managers.',
            'Source proactively through events, referrals, and targeted outreach.',
            'Run debriefs, manage offers, and ensure an equitable candidate journey.',
        ],
        requirements: [
            'Experience running full-cycle recruitment in professional services.',
            'Comfort with sourcing tools and employer brand storytelling.',
            'Ability to manage multiple searches with pace and quality.',
        ],
    },
];

export default function Career({ jobs = [] }) {
    const preparedJobs = useMemo(
        () => (Array.isArray(jobs) && jobs.length > 0 ? jobs : fallbackJobs),
        [jobs],
    );

    return (
        <>
            <Head title="Career - AbsolutelyHR" />
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
                    <H1 text="CAREER" color="white" className="uppercase text-center mb-20" />
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
                                        <div className="items-start gap-3 w-1/2">
                                            <h3 className="text-xl font-bold text-white leading-tight">
                                                {job.title}
                                            </h3>
                                            <div className="flex space-x-2 mb-2">
                                                <div className="flex items-center">
                                                    <span className="text-[11px] uppercase tracking-wide bg-[#E3E3E3] text-black px-2 py-1 rounded-full font-semibold text-center">
                                                        JUNIOR
                                                    </span>
                                                    <span className="text-black mx-1 flex items-center justify-center h-full">.</span>
                                                    <span className="text-[11px] uppercase tracking-wide bg-[#E3E3E3] text-black px-2 py-1 rounded-full font-semibold text-center">
                                                        Remote
                                                    </span>
                                                </div>
                                            </div>
                                            <div className="flex">
                                                <span className="text-[11px] uppercase tracking-wide bg-[#E3E3E3] text-black px-6 py-1 rounded-full font-semibold  text-center">
                                                    {job.category}
                                                </span>
                                            </div>
                                        </div>
                                        <p className="mt-4 text-sm text-white/80 leading-relaxed flex-1">
                                            {getMaxWords(job.summary ?? job.description, 50)}
                                        </p>
                                        <a
                                            href={`/career/${job.id}`}
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
