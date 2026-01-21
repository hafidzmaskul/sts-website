import React, { useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import { Head } from '@inertiajs/react';

// Custom Toastr Implementation
function toast(message, type = 'success') {
    // Remove existing toast if present
    const existing = document.getElementById('custom-toast-notification');
    if (existing) {
        existing.remove();
    }

    let bgColor = '#4BB543'; // success
    if (type === 'error') {
        bgColor = '#dc2626';
    } else if (type === 'info') {
        bgColor = '#2563eb';
    } else if (type === 'warning') {
        bgColor = '#f59e42';
    }

    const toastDiv = document.createElement('div');
    toastDiv.id = 'custom-toast-notification';
    toastDiv.style.position = 'fixed';
    toastDiv.style.top = '32px';
    toastDiv.style.right = '32px';
    toastDiv.style.zIndex = 10000;
    toastDiv.style.background = bgColor;
    toastDiv.style.color = '#fff';
    toastDiv.style.padding = '14px 32px';
    toastDiv.style.borderRadius = '6px';
    toastDiv.style.boxShadow = '0 2px 12px rgba(0,0,0,0.14)';
    toastDiv.style.fontSize = '16px';
    toastDiv.style.fontWeight = 'bold';
    toastDiv.style.opacity = '0.96';
    toastDiv.textContent = message;

    document.body.appendChild(toastDiv);

    setTimeout(() => {
        toastDiv.style.transition = 'opacity 0.3s';
        toastDiv.style.opacity = '0';
        setTimeout(() => {
            if (toastDiv.parentNode) toastDiv.parentNode.removeChild(toastDiv);
        }, 300);
    }, 2800);
}

const jobTitles = [
    '',
    'Financial Director',
    'Purchasing Manager',
    'Engineering Lead',
    'Technician',
    'Manager',
    'Other',
];

export default function BecomeCustomer() {
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        job_title: '',
        name: '',
        registration_number: '',
        trading_name: '',
        vat_number: '',
        address: '',
        trading_address: '',
        phone: '',
        // fax: '', // REMOVED
        activities_description: '',
        purchasing_contact_name: '',
        purchasing_contact_phone: '',
        purchasing_contact_email: '',
        accounts_contact_name: '',
        accounts_contact_phone: '',
        accounts_contact_email: '',
        bank_name: '',
        bank_address: '',
        bank_sort_code: '',
        bank_account_number: '',
        trade_ref_1_details: '',
        trade_ref_1_phone: '',
        trade_ref_1_email: '',
        trade_ref_2_details: '',
        trade_ref_2_phone: '',
        trade_ref_2_email: '',
        requested_credit_limit: '',
    });

    const [currentStep, setCurrentStep] = useState(1);
    const [completedSteps, setCompletedSteps] = useState([]);
    const [processing, setProcessing] = useState(false);
    const [errors, setErrors] = useState({});

    const steps = [
        { number: 1, title: 'Company Information', desc: 'Basic Company Details' },
        { number: 2, title: 'Contact Information', desc: 'Key personnel contacts' },
        // { number: 3, title: 'Financial & References', desc: 'Banking and trade references' },
    ];

    const handleChange = (field, value) => {
        setFormData(prev => ({
            ...prev,
            [field]: value,
        }));
    };

    const getStepStatus = (stepNumber) => {
        if (completedSteps.includes(stepNumber)) {
            return 'completed';
        }
        if (currentStep === stepNumber) {
            return 'active';
        }
        return 'default';
    };

    const handleNext = () => {
        if (currentStep < 3) {
            setCompletedSteps(prev => (prev.includes(currentStep) ? prev : [...prev, currentStep]));
            setCurrentStep(currentStep + 1);
        }
    };

    const handlePrevious = () => {
        if (currentStep > 1) {
            setCurrentStep(currentStep - 1);
            setCompletedSteps(prev => prev.filter(num => num !== currentStep - 1));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});
        // Compose payload as the API expects
        // Also include role: "credit facilities account"
        const payload = {
            ...formData,
            role: 'credit facilities account',
            requested_credit_limit: formData.requested_credit_limit ? parseInt(formData.requested_credit_limit, 10) : 0,
        };

        try {
            const response = await fetch('/api/sign-up', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            const result = await response.json();
            if (response.ok) {
                toast('Registration submitted successfully.', 'success');
                setFormData({
                    first_name: '',
                    last_name: '',
                    email: '',
                    job_title: '',
                    name: '',
                    registration_number: '',
                    trading_name: '',
                    vat_number: '',
                    address: '',
                    trading_address: '',
                    phone: '',
                    // fax: '', // REMOVED
                    activities_description: '',
                    purchasing_contact_name: '',
                    purchasing_contact_phone: '',
                    purchasing_contact_email: '',
                    accounts_contact_name: '',
                    accounts_contact_phone: '',
                    accounts_contact_email: '',
                    bank_name: '',
                    bank_address: '',
                    bank_sort_code: '',
                    bank_account_number: '',
                    trade_ref_1_details: '',
                    trade_ref_1_phone: '',
                    trade_ref_1_email: '',
                    trade_ref_2_details: '',
                    trade_ref_2_phone: '',
                    trade_ref_2_email: '',
                    requested_credit_limit: '',
                });
                setCurrentStep(1);
                setCompletedSteps([]);
            } else {
                if (result.errors) setErrors(result.errors);
                toast(result.message || 'Registration failed. Please check your input.', 'error');
            }
        } catch (err) {
            toast('Something went wrong. Please try again later.', 'error');
        } finally {
            setProcessing(false);
        }
    };

    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Become Customer" />
            <Header />

            <main>
                <section className="bg-[#F0F2F3] py-10 px-10 container mx-auto my-20">
                    <div className="text-center">
                        <h1 className='text-[#002856] font-bold font-inter text-2xl'>STS | Become a Customer</h1>
                    </div>
                    <div className="flex flex-col mx-auto mt-8 text-left max-w-4xl">
                        <form className="w-full" onSubmit={handleSubmit} autoComplete="off">
                            {/* Stepper Indicator */}
                            <div className="mt-10 mb-8">
                                <div className="flex item-center justify-between">
                                    {steps.map((step, index) => {
                                        const status = getStepStatus(step.number);
                                        const isLast = index === steps.length - 1;
                                        return (
                                            <React.Fragment key={step.number}>
                                                <div className="flex flex-col items-center" style={{ flex: '1 1 auto' }}>
                                                    <div
                                                        className={`w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold text-sm transition-colors ${
                                                            status === 'active'
                                                                ? 'border-[#0079C2] bg-white text-[#0079C2]'
                                                                : status === 'completed'
                                                                ? 'border-[#0079C2] bg-[#0079C2] text-white'
                                                                : 'border-[#757575] bg-white text-[#757575]'
                                                        }`}
                                                    >
                                                        {status === 'completed' ? (
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        ) : (
                                                            step.number
                                                        )}
                                                    </div>
                                                    <div className="mt-2 text-center max-w-[150px]">
                                                        <div className={`text-xs font-bold ${status === 'active' ? 'text-[#0079C2]' : status === 'completed' ? 'text-[#0079C2]' : 'text-[#757575]'}`}>
                                                            {step.title}
                                                        </div>
                                                        <div className={`text-xs mt-1 ${status === 'active' ? 'text-[#0079C2]' : status === 'completed' ? 'text-[#0079C2]' : 'text-[#757575]'}`}>
                                                            {step.desc}
                                                        </div>
                                                    </div>
                                                </div>
                                                {!isLast && (
                                                    <div
                                                        className={`flex-1 h-0.5 mx-2 mt-5 ${
                                                            completedSteps.includes(step.number)
                                                                ? 'bg-[#0079C2]'
                                                                : currentStep > step.number
                                                                ? 'bg-[#0079C2]'
                                                                : 'bg-[#757575]'
                                                        }`}
                                                    />
                                                )}
                                            </React.Fragment>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className="mt-10 bg-white p-8 space-y-10 overflow-auto rounded-lg border border-[#f0f0f0]">
                                {/* Step 1: Company Information */}
                                {currentStep === 1 && (
                                    <div className="space-y-6 animate-fade-in">
                                        <h2 className="font-bold text-lg text-[#002856] mb-0">Company Information</h2>

                                        {/* Company Name, Company Registration No: */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Company Name <span className="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.name}
                                                    onChange={e => handleChange('name', e.target.value)}
                                                    required
                                                />
                                                {errors.name && <div className="text-xs text-red-500 mt-1">{errors.name}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Company Registration No:
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.registration_number}
                                                    onChange={e => handleChange('registration_number', e.target.value)}
                                                />
                                                {errors.registration_number && <div className="text-xs text-red-500 mt-1">{errors.registration_number}</div>}
                                            </div>
                                        </div>

                                        {/* Trading Name (If applicable), VAT Registration No (inline) */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Trading Name (If applicable)
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.trading_name}
                                                    onChange={e => handleChange('trading_name', e.target.value)}
                                                />
                                                {errors.trading_name && <div className="text-xs text-red-500 mt-1">{errors.trading_name}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    VAT Registration No
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.vat_number}
                                                    onChange={e => handleChange('vat_number', e.target.value)}
                                                />
                                                {errors.vat_number && <div className="text-xs text-red-500 mt-1">{errors.vat_number}</div>}
                                            </div>
                                        </div>

                                        {/* Company Address, Trading Address (inline) */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Company Address
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.address}
                                                    onChange={e => handleChange('address', e.target.value)}
                                                />
                                                {errors.address && <div className="text-xs text-red-500 mt-1">{errors.address}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Trading Name (If applicable)
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.trading_address}
                                                    onChange={e => handleChange('trading_address', e.target.value)}
                                                />
                                                {errors.trading_address && <div className="text-xs text-red-500 mt-1">{errors.trading_address}</div>}
                                            </div>
                                        </div>

                                        {/* Telephone No (removed Fax No input) */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                    Telephone No
                                                </label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.phone}
                                                    onChange={e => handleChange('phone', e.target.value)}
                                                />
                                                {errors.phone && <div className="text-xs text-red-500 mt-1">{errors.phone}</div>}
                                            </div>
                                            {/* Fax input removed */}
                                        </div>

                                        {/* Main Activities (not inline) */}
                                        <div>
                                            <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                Brief details of your company's main activities
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                value={formData.activities_description}
                                                onChange={e => handleChange('activities_description', e.target.value)}
                                            />
                                            {errors.activities_description && <div className="text-xs text-red-500 mt-1">{errors.activities_description}</div>}
                                        </div>

                                        {/* Next Button */}
                                        <div className="flex justify-between mt-6 pt-6 border-t border-[#E8E7E7]">
                                            <div></div>
                                            <button
                                                type="button"
                                                onClick={handleNext}
                                                className="px-8 py-2 rounded font-medium bg-[#0079C2] text-white hover:bg-[#005b8c]"
                                            >Next</button>
                                        </div>
                                    </div>
                                )}

                                {/* Step 2: Contact Information */}
                                {currentStep === 2 && (
                                    <div className="space-y-6 animate-fade-in">
                                        <h2 className="font-bold text-lg text-[#002856] mb-0">Contact Information</h2>

                                        {/* First/Last Name, Email, Job Title */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">First Name <span className="text-red-500">*</span></label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.first_name}
                                                    onChange={e => handleChange('first_name', e.target.value)}
                                                    required
                                                />
                                                {errors.first_name && <div className="text-xs text-red-500 mt-1">{errors.first_name}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Last Name <span className="text-red-500">*</span></label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.last_name}
                                                    onChange={e => handleChange('last_name', e.target.value)}
                                                    required
                                                />
                                                {errors.last_name && <div className="text-xs text-red-500 mt-1">{errors.last_name}</div>}
                                            </div>
                                        </div>
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Email <span className="text-red-500">*</span></label>
                                                <input
                                                    type="email"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.email}
                                                    onChange={e => handleChange('email', e.target.value)}
                                                    required
                                                />
                                                {errors.email && <div className="text-xs text-red-500 mt-1">{errors.email}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Job Title</label>
                                                <select
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.job_title}
                                                    onChange={e => handleChange('job_title', e.target.value)}
                                                >
                                                    {jobTitles.map((jt, idx) => <option value={jt} key={idx}>{jt ? jt : 'Select Job Title'}</option>)}
                                                </select>
                                                {errors.job_title && <div className="text-xs text-red-500 mt-1">{errors.job_title}</div>}
                                            </div>
                                        </div>

                                        {/* Purchasing Contact (not inline) */}
                                        <div>
                                            <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                Purchasing Contact
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                value={formData.purchasing_contact_name}
                                                onChange={e => handleChange('purchasing_contact_name', e.target.value)}
                                            />
                                            {errors.purchasing_contact_name && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_name}</div>}
                                        </div>

                                        {/* Telephone No, Email (inline) */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Telephone No</label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.purchasing_contact_phone}
                                                    onChange={e => handleChange('purchasing_contact_phone', e.target.value)}
                                                />
                                                {errors.purchasing_contact_phone && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_phone}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Email</label>
                                                <input
                                                    type="email"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.purchasing_contact_email}
                                                    onChange={e => handleChange('purchasing_contact_email', e.target.value)}
                                                />
                                                {errors.purchasing_contact_email && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_email}</div>}
                                            </div>
                                        </div>

                                        {/* Accounts Contact (not inline) */}
                                        <div>
                                            <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">
                                                Accounts Contact
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                value={formData.accounts_contact_name}
                                                onChange={e => handleChange('accounts_contact_name', e.target.value)}
                                            />
                                            {errors.accounts_contact_name && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_name}</div>}
                                        </div>

                                        {/* Telephone No, Email (inline) */}
                                        <div className="flex flex-col md:flex-row gap-6">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Telephone No</label>
                                                <input
                                                    type="text"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.accounts_contact_phone}
                                                    onChange={e => handleChange('accounts_contact_phone', e.target.value)}
                                                />
                                                {errors.accounts_contact_phone && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_phone}</div>}
                                            </div>
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-poppins font-normal text-[#000]">Email</label>
                                                <input
                                                    type="email"
                                                    className="w-full border-0 border-b border-[#000] text-[#000] font-poppins text-xs px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000]"
                                                    value={formData.accounts_contact_email}
                                                    onChange={e => handleChange('accounts_contact_email', e.target.value)}
                                                />
                                                {errors.accounts_contact_email && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_email}</div>}
                                            </div>
                                        </div>

                                        {/* Buttons: Previous and Submit */}
                                        <div className="flex justify-between mt-6 pt-6 border-t border-[#E8E7E7]">
                                            <button
                                                type="button"
                                                onClick={handlePrevious}
                                                className="px-8 py-2 rounded font-medium bg-[#0079C2] text-white hover:bg-[#005b8c]"
                                            >Previous</button>
                                            <button
                                                type="submit"
                                                disabled={processing}
                                                className={`px-8 py-2 rounded font-medium text-white ${processing ? 'bg-[#90C0E4] cursor-not-allowed' : 'bg-[#0079C2] hover:bg-[#005b8c]'}`}
                                            >
                                                {processing ? 'Submitting...' : 'Submit'}
                                            </button>
                                        </div>
                                    </div>
                                )}

                                {/* Step 3: Financial & References */}
                                {currentStep === 3 && (
                                    <div className="space-y-6 animate-fade-in text-center py-10">
                                        <h2 className="font-bold text-lg text-[#002856] mb-0">Financial & References</h2>
                                        <p className="text-base text-[#002856] mt-4">
                                            Form for step 3 has been removed.
                                        </p>
                                        <div className="flex justify-center mt-8 pt-6 border-t border-[#E8E7E7] gap-4">
                                            <button
                                                type="button"
                                                onClick={handlePrevious}
                                                className="px-8 py-2 rounded font-medium bg-[#0079C2] text-white hover:bg-[#005b8c]"
                                            >Previous</button>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </form>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
