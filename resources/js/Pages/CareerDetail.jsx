import React, { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import { getMaxWords } from '../helpers/text';

const JOB_DESCRIPTIONS = [
    "Lead, motivate, and manage the sales team to achieve individual and team sales targets.",
    "Develop and implement effective sales strategies and action plans.",
    "Monitor and analyse sales performance metrics and provide regular reports to management.",
    "Conduct regular training sessions to enhance the skills and knowledge of the sales team.",
    "Ensure excellent customer service and maintain strong relationships with clients.",
    "Handle customer inquiries and resolve any issues or complaints promptly.",
    "Coordinate with marketing and finance departments to ensure alignment of sales strategies.",
    "Stay updated on industry trends and competitor activities.",
    "Oversee the daily operations of the showroom and ensure it is well-maintained and presentable.",
];

const JOB_REQUIREMENTS = [
    "Fluency in English",
    "Open to all majors",
    "Exhibit strong work ethics, disciplined commitment, and exceptional leadership abilities",
    "Fresh graduates are welcome to apply",
];

const JOB_BENEFITS = [
    "Monthly bonus",
    "Seasonal bonus",
    "Meal, Traffic, and Accommodation Allowance",
    "Exceptional growth opportunities and career advancement within the company",
    "A supportive and collaborative work environment",
    "6-month program. Outstanding participants may join our esteemed team upon program completion, with exceptional performers able to shorten the duration to just 3 months.",
];

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
        // Overwrite responsibilities/requirements
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
        // Overwrite responsibilities/requirements
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
        // Overwrite responsibilities/requirements
    },
];

export default function CareerDetail({ job = null, jobs = [] }) {
    // Overwrite responsibilities/requirements for all jobs in fallbackJobs if needed
    const preparedJobs = useMemo(() => {
        return (Array.isArray(jobs) && jobs.length > 0
            ? jobs
            : fallbackJobs.map(j => ({
                ...j,
                responsibilities: JOB_DESCRIPTIONS,
                requirements: JOB_REQUIREMENTS,
                benefits: JOB_BENEFITS,
            }))
        );
    }, [jobs]);

    const selectedJob = useMemo(() => {
        if (job) {
            return {
                ...job,
                responsibilities: JOB_DESCRIPTIONS,
                requirements: JOB_REQUIREMENTS,
                benefits: JOB_BENEFITS,
            };
        }

        if (preparedJobs.length === 0) {
            return null;
        }

        return preparedJobs[0];
    }, [job, preparedJobs]);

    const [shareFeedback, setShareFeedback] = useState('');
    const [formFeedback, setFormFeedback] = useState('');
    const [formData, setFormData] = useState({
        fullName: '',
        email: '',
        phone: '',
        attachment: null,
        message: '',
        consent: false,
    });

    const handleShare = async () => {
        if (!selectedJob || typeof navigator === 'undefined') {
            return;
        }

        const url = typeof window !== 'undefined' ? window.location.href : '';
        const text = selectedJob.summary ?? selectedJob.description ?? selectedJob.title;

        try {
            if (navigator.share) {
                await navigator.share({
                    title: selectedJob.title,
                    text,
                    url,
                });
                setShareFeedback('Shared successfully.');
                return;
            }

            if (navigator.clipboard) {
                await navigator.clipboard.writeText(`${selectedJob.title} - ${url}`);
                setShareFeedback('Link copied to clipboard.');
            }
        } catch (error) {
            console.error(error);
            setShareFeedback('Unable to share right now.');
        }
    };

    const handleInputChange = (event) => {
        const { name, value, type, checked, files } = event.target;

        setFormData((prev) => ({
            ...prev,
            [name]:
                type === 'checkbox'
                    ? checked
                    : type === 'file'
                        ? files?.[0] ?? null
                        : value,
        }));
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        setFormFeedback('Thanks for submitting your interest. Our team will reach out soon.');
    };

    return (
        <>
            <Head title={`${selectedJob?.title ?? 'Career'} - AbsolutelyHR`} />
            <div className="bg-[#302F2F]" data-aos="fade-in">
            <Header />
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

                <main className="container mx-auto px-10 md:px-10 py-16 space-y-8" data-aos="fade-up">
                    <section className="mb-20">
                        <div
                            className="p-6 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] h-full"
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
                                <div className="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 className="text-2xl md:text-3xl font-bold text-black leading-tight">
                                            {selectedJob?.title ?? 'Select a role'}
                                        </h3>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={handleShare}
                                        className="inline-flex items-center  px-4 py-3 rounded-full  text-black transition"
                                        style={{
                                            background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)',
                                        }}
                                    >
                                        Share
                                    </button>
                                </div>
                                {shareFeedback && (
                                    <p className="text-xs text-black/70 mt-2 text-right">{shareFeedback}</p>
                                )}
                                <div
                                    className=" rounded-4xl  flex flex-col flex-1 mt-10"
                                    style={{
                                        boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                        background:
                                            'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                    }}
                                >
                                    <div className=" p-3 flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-wide">
                                        {selectedJob?.location && (
                                            <span className="px-3 py-1 rounded-full bg-[#E3E3E3] text-black">
                                                {selectedJob.location}
                                            </span>
                                        )}
                                        {selectedJob?.type && (
                                            <span className="px-3 py-1 rounded-full bg-[#E3E3E3] text-black">
                                                {selectedJob.type}
                                            </span>
                                        )}
                                        {selectedJob?.level && (
                                            <span className="px-3 py-1 rounded-full bg-[#E3E3E3] text-black">
                                                {selectedJob.level}
                                            </span>
                                        )}
                                        {selectedJob?.department && (
                                            <span className="px-3 py-1 rounded-full bg-[#E3E3E3] text-black">
                                                {selectedJob.department}
                                            </span>
                                        )}
                                    </div>
                                </div>

                                <p className="mt-6 text-black/80 text-sm leading-relaxed">
                                    {selectedJob?.description ??
                                        'Select a position to read the full job description and requirements.'}
                                </p>

                                <div className="mt-6 space-y-3">
                                    <h4 className="text-black font-semibold text-sm uppercase tracking-wide">
                                        Job Descriptions
                                    </h4>
                                    <ul className="list-disc list-inside space-y-2 text-black/80 text-sm">
                                        {JOB_DESCRIPTIONS.map((item, index) => (
                                            <li key={`desc-${index}`}>{item}</li>
                                        ))}
                                    </ul>
                                </div>

                                <div className="mt-6 space-y-3">
                                    <h4 className="text-black font-semibold text-sm uppercase tracking-wide">
                                        Job Requirements
                                    </h4>
                                    <ul className="list-disc list-inside space-y-2 text-black/80 text-sm">
                                        {JOB_REQUIREMENTS.map((item, index) => (
                                            <li key={`req-${index}`}>{item}</li>
                                        ))}
                                    </ul>
                                </div>

                                <div className="mt-6 space-y-3">
                                    <h4 className="text-black font-semibold text-sm uppercase tracking-wide">
                                        Benefit
                                    </h4>
                                    <ul className="list-disc list-inside space-y-2 text-black/80 text-sm">
                                        {JOB_BENEFITS.map((item, index) => (
                                            <li key={`benefit-${index}`}>{item}</li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div
                            className="p-6 backdrop-blur-[70px] rounded-2xl mt-20 shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] h-full"
                            style={{
                                background:
                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                            }}
                        >
                            <div
                                className="p-6 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] h-full"
                                style={{
                                    background:
                                        'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                            >


                            <div className="flex items-start justify-between gap-3 mb-4">
                                <h3 className="text-xl font-bold text-black leading-tight">Apply Now</h3>

                            </div>
                            <form className="space-y-4" onSubmit={handleSubmit}>
                                <div className="flex flex-col gap-2">
                                    <input
                                        id="fullName"
                                        name="fullName"
                                        type="text"
                                        required
                                        value={formData.fullName}
                                        onChange={handleInputChange}
                                        className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3 focus:outline-none "
                                        placeholder="Full Name"
                                        style={{
                                            background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                        }}
                                    />
                                </div>
                                <div className="grid md:grid-cols-2 gap-4">
                                    <div className="flex flex-col gap-2">
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            required
                                            value={formData.email}
                                            onChange={handleInputChange}
                                            className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3 focus:outline-none "
                                            placeholder="Email"
                                            style={{
                                                background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                            }}
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <input
                                            id="phone"
                                            name="phone"
                                            type="tel"
                                            required
                                            value={formData.phone}
                                            onChange={handleInputChange}
                                            className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3 focus:outline-none "
                                            placeholder="Mobile Phone"
                                            style={{
                                                background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                            }}
                                        />
                                    </div>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <input
                                        id="attachment"
                                        name="attachment"
                                        type="file"
                                        onChange={handleInputChange}
                                        className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3 file:mr-3 file:rounded-lg file:border-0 file:text-black file:px-3 file:py-2  "
                                        style={{
                                            background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                        }}
                                    />
                                </div>
                                <div className="flex flex-col gap-2 md:col-span-1">
                                    <textarea
                                        id="message"
                                        name="message"
                                        rows={3}
                                        value={formData.message}
                                        onChange={handleInputChange}
                                        className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3  "
                                        placeholder="Message"
                                        style={{
                                            background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                        }}
                                    />
                                </div>

                                <div className="flex items-center justify-end gap-3">
                                    <input
                                        id="consent"
                                        name="consent"
                                        type="checkbox"
                                        required
                                        checked={formData.consent}
                                        onChange={handleInputChange}
                                        className="h-4 w-4 rounded border-white/40 text-[#FFED2E] focus:ring-[#FFED2E]"
                                        style={{
                                            background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                        }}
                                    />
                                    {/* Label intentionally removed as per spec */}
                                </div>

                                <div className="flex justify-end" >
                                    <button
                                        type="submit"
                                        className="inline-flex items-center justify-center gap-2 bg-[#FFED2E] text-black px-8 py-3 rounded-xl font-semibold shadow hover:bg-white transition"
                                        style={{
                                            background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)'
                                        }}
                                    >
                                        Submit
                                    </button>
                                </div>

                                {formFeedback && (
                                    <p className="text-sm text-black">{formFeedback}</p>
                                )}
                            </form>
                            </div>
                        </div>
                    </section>

                    <section className="mt-6">
                        <H1 text="Other Positions" color="white" className="uppercase text-left mb-6" />
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {preparedJobs
                                .filter((item) => item.id !== selectedJob?.id)
                                .slice(0, 3)
                                .map((item) => (
                                    <div
                                        key={item.id}
                                        className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col"
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
                                            <div className="flex items-start justify-between gap-3">
                                                <h3 className="text-xl font-bold text-white leading-tight">
                                                    {item.title}
                                                </h3>
                                                <span className="text-[11px] uppercase tracking-wide bg-[#FFED2E] text-black px-3 py-1 rounded-full font-semibold">
                                                    {item.category}
                                                </span>
                                            </div>
                                            <p className="mt-4 text-sm text-white/80 leading-relaxed flex-1">
                                                {getMaxWords(item.summary ?? item.description, 40)}
                                            </p>
                                            <a
                                                href={`/career/${item.id}`}
                                                className="mt-6 inline-flex items-center justify-center gap-2 bg-white text-black px-6 py-2 rounded-lg font-semibold shadow hover:bg-[#FFED2E] transition"
                                            >
                                                See Detail
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeWidth="2"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    className="lucide lucide-arrow-up-right"
                                                >
                                                    <path d="M7 7h10v10" />
                                                    <path d="M7 17 17 7" />
                                                </svg>
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
