import React, { useMemo, useState } from 'react';
import axios from 'axios';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import { getMaxWords } from '../helpers/text';

const fallbackJobs = [
    {
        id: 1,
        slug: 'senior-hr-consultant',
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
        slug: 'people-operations-lead',
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
        slug: 'talent-acquisition-partner',
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
    const preparedJobs = useMemo(
        () => (Array.isArray(jobs) && jobs.length > 0 ? jobs : fallbackJobs),
        [jobs],
    );

    const selectedJob = useMemo(
        () => job ?? (preparedJobs.length > 0 ? preparedJobs[0] : null),
        [job, preparedJobs],
    );

    const otherJobs = useMemo(() => {
        if (!selectedJob) {
            return preparedJobs;
        }

        return preparedJobs.filter((item) => item.id !== selectedJob.id);
    }, [preparedJobs, selectedJob]);

    const stripHtml = (value) => (typeof value === 'string' ? value.replace(/<[^>]+>/g, '') : '');

    const [shareFeedback, setShareFeedback] = useState('');
    const [formFeedback, setFormFeedback] = useState('');
    const [formErrors, setFormErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState({
        full_name: '',
        email: '',
        mobile_phone: '',
        resume: null,
        message: '',
        consent: false,
    });
    const fieldError = (field) => {
        const value = formErrors[field];
        if (!value) {
            return '';
        }

        return Array.isArray(value) ? value.join(' ') : value;
    };

    const handleShare = async () => {
        if (!selectedJob || typeof navigator === 'undefined') {
            return;
        }

        const url = typeof window !== 'undefined' ? window.location.href : '';
        const text = stripHtml(selectedJob.description ?? selectedJob.summary ?? selectedJob.title);

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

    const handleSubmit = async (event) => {
        event.preventDefault();
        if (!selectedJob) {
            setFormFeedback('No job selected. Please choose a position.');
            return;
        }

        setIsSubmitting(true);
        setFormFeedback('');
        setFormErrors({});

        const payload = new FormData();
        payload.append('career_id', selectedJob.id);
        payload.append('full_name', formData.full_name);
        payload.append('email', formData.email);
        payload.append('mobile_phone', formData.mobile_phone);
        payload.append('message', formData.message);
        if (formData.resume) {
            payload.append('resume', formData.resume);
        }

        try {
            const response = await axios.post('/api/careers/apply', payload, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });

            setFormFeedback(response.data?.message ?? 'Application submitted successfully.');
            setFormData({
                full_name: '',
                email: '',
                mobile_phone: '',
                resume: null,
                message: '',
                consent: false,
            });
        } catch (error) {
            if (error.response?.status === 422) {
                setFormErrors(error.response?.data?.errors ?? {});
                setFormFeedback(error.response?.data?.message ?? 'Validation failed.');
            } else {
                setFormFeedback('An error occurred while submitting. Please try again.');
            }
        } finally {
            setIsSubmitting(false);
        }
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
                                <div className="mt-6 space-y-3">
                                    <h4 className="text-black font-semibold text-sm uppercase tracking-wide">
                                        Role Description
                                    </h4>
                                    <div
                                        className="prose prose-sm prose-invert max-w-none text-black/80 leading-relaxed"
                                        dangerouslySetInnerHTML={{
                                            __html: selectedJob?.description
                                                ?? 'Details coming soon.',
                                        }}
                                    />
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
                            <form className="space-y-4" onSubmit={handleSubmit} encType="multipart/form-data">
                                <div className="flex flex-col gap-2">
                                    <input
                                        id="full_name"
                                        name="full_name"
                                        type="text"
                                        required
                                        value={formData.full_name}
                                        onChange={handleInputChange}
                                         className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                        placeholder="Full Name"
                                        style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                                    />
                                    {fieldError('full_name') && (
                                        <p className="text-sm text-red-600">{fieldError('full_name')}</p>
                                    )}
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
                                             className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                            placeholder="Email"
                                            style={{
                                                background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                            }}
                                        />
                                        {fieldError('email') && (
                                            <p className="text-sm text-red-600">{fieldError('email')}</p>
                                        )}
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <input
                                            id="mobile_phone"
                                            name="mobile_phone"
                                            type="tel"
                                            required
                                            value={formData.mobile_phone}
                                            onChange={handleInputChange}
                                             className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                            placeholder="Mobile Phone"
                                            style={{
                                                background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)'
                                            }}
                                        />
                                        {fieldError('mobile_phone') && (
                                            <p className="text-sm text-red-600">{fieldError('mobile_phone')}</p>
                                        )}
                                    </div>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <input
                                        id="resume"
                                        name="resume"
                                        type="file"
                                        required
                                        onChange={handleInputChange}
                                        className="w-full rounded-xl border border-white/20 text-black placeholder-black px-4 py-3 file:mr-3 file:rounded-lg file:border-0 file:text-black file:px-3 file:py-2  "
                                        style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                                    />
                                    {fieldError('resume') && (
                                        <p className="text-sm text-red-600">{fieldError('resume')}</p>
                                    )}
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
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
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
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                                    />
                                    {/* Label intentionally removed as per spec */}
                                </div>

                                <div className="flex justify-end" >
                                    <button
                                        type="submit"
                                        disabled={isSubmitting}
                                        className="inline-flex items-center justify-center gap-2 bg-[#FFED2E] text-black px-8 py-3 rounded-xl font-semibold shadow hover:bg-white transition"
                                        style={{
                                            background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)'
                                        }}
                                    >
                                        {isSubmitting ? 'Submitting...' : 'Submit'}
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
                            {otherJobs
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
                                                    {item.department ?? item.category ?? 'Career'}
                                                </span>
                                            </div>
                                            <p className="mt-4 text-sm text-white/80 leading-relaxed flex-1">
                                                {getMaxWords(stripHtml(item.description ?? item.summary ?? ''), 40)}
                                            </p>
                                            <a
                                                href={`/career/${item.slug ?? item.id}`}
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
