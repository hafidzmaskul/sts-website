import React, { useState } from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import { Head } from '@inertiajs/react';

// Custom Toastr Implementation
function toast(message, type = 'success') {
    const existing = document.getElementById('custom-toast-notification');
    if (existing) existing.remove();

    let bgColor = '#4BB543';
    if (type === 'error') bgColor = '#dc2626';
    else if (type === 'info') bgColor = '#2563eb';
    else if (type === 'warning') bgColor = '#f59e42';

    const toastDiv = document.createElement('div');
    toastDiv.id = 'custom-toast-notification';
    toastDiv.style.position = 'fixed';
    toastDiv.style.top = '24px';
    toastDiv.style.right = '24px';
    toastDiv.style.zIndex = 10000;
    toastDiv.style.background = bgColor;
    toastDiv.style.color = '#fff';
    toastDiv.style.padding = '12px 20px';
    toastDiv.style.borderRadius = '5px';
    toastDiv.style.boxShadow = '0 2px 10px rgba(0,0,0,0.12)';
    toastDiv.style.fontSize = '15px';
    toastDiv.style.fontWeight = '500';
    toastDiv.style.opacity = '0.98';
    toastDiv.textContent = message;
    document.body.appendChild(toastDiv);

    setTimeout(() => {
        toastDiv.style.transition = 'opacity 0.3s';
        toastDiv.style.opacity = '0';
        setTimeout(() => {
            if (toastDiv.parentNode) toastDiv.parentNode.removeChild(toastDiv);
        }, 300);
    }, 2600);
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
        // No longer 3 steps
    ];

    const handleChange = (field, value) => {
        setFormData(prev => ({
            ...prev,
            [field]: value,
        }));
    };

    const getStepStatus = (stepNumber) => {
        if (completedSteps.includes(stepNumber)) return 'completed';
        if (currentStep === stepNumber) return 'active';
        return 'default';
    };

    const handleNext = () => {
        if (currentStep < 2) {
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
        <div className="min-h-screen flex flex-col bg-[#F0F2F3]">
            <Head title="Become Customer" />
            <Header />
            <main className="flex-grow">
                <section className="py-6 px-2 sm:px-4 md:px-0 container mx-auto flex flex-col items-center">
                    <div className="w-full max-w-xl md:max-w-2xl lg:max-w-3xl xl:max-w-4xl">
                        <div className="text-center pt-10 pb-2">
                            <h1 className="text-[#002856] font-extrabold font-inter text-lg sm:text-xl md:text-2xl lg:text-3xl leading-tight tracking-wider">
                                STS | Become a Customer
                            </h1>
                        </div>
                        {/* Stepper */}
                        <div className="flex items-center justify-between gap-2 mt-8 mb-5">
                            {steps.map((step, index) => {
                                const status = getStepStatus(step.number);
                                const isLast = index === steps.length - 1;
                                return (
                                    <React.Fragment key={step.number}>
                                        <div className="flex flex-col items-center flex-1 min-w-0">
                                            <div
                                                className={`w-9 h-9 md:w-10 md:h-10 rounded-full border-2 flex items-center justify-center font-bold text-base md:text-lg transition-colors
                                                    ${
                                                        status === 'active'
                                                            ? 'border-[#0079C2] bg-white text-[#0079C2]'
                                                            : status === 'completed'
                                                            ? 'border-[#0079C2] bg-[#0079C2] text-white'
                                                            : 'border-[#C9CBD0] bg-white text-[#989898]'
                                                    }`}
                                            >
                                                {status === 'completed' ? (
                                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                ) : (
                                                    step.number
                                                )}
                                            </div>
                                            <div className="mt-2 text-center px-1">
                                                <div className={`text-xs md:text-sm font-bold ${status === 'active' || status === 'completed' ? 'text-[#0079C2]' : 'text-[#9CA3AF]'}`}>{step.title}</div>
                                                <div className={`text-xs md:text-xs mt-1 ${status === 'active' || status === 'completed' ? 'text-[#0079C2]' : 'text-[#9CA3AF]'}`}>{step.desc}</div>
                                            </div>
                                        </div>
                                        {!isLast && (
                                            <div
                                                className={`flex-1 h-0.5 mx-1 sm:mx-2 mt-4
                                                    ${
                                                        completedSteps.includes(step.number)
                                                        ? 'bg-[#0079C2]'
                                                        : currentStep > step.number
                                                        ? 'bg-[#0079C2]'
                                                        : 'bg-[#C9CBD0]'
                                                    }`}
                                            ></div>
                                        )}
                                    </React.Fragment>
                                );
                            })}
                        </div>
                        {/* End Stepper */}

                        <form onSubmit={handleSubmit} autoComplete="off"
                              className="mt-3 bg-white p-4 xs:p-6 sm:p-7 md:p-8 space-y-8 rounded-2xl shadow-sm border border-[#F3F7F9]"
                        >
                            {/* Step 1 */}
                            {currentStep === 1 && (
                                <div className="space-y-5 animate-fade-in">
                                    <h2 className="font-bold text-base md:text-lg text-[#002856] mb-1">Company Information</h2>

                                    {/* Company Name / Registration No */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Company Name <span className="text-red-500 font-bold">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.name}
                                                onChange={e => handleChange('name', e.target.value)}
                                                required
                                            />
                                            {errors.name && <div className="text-xs text-red-500 mt-1">{errors.name}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Company Registration No
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.registration_number}
                                                onChange={e => handleChange('registration_number', e.target.value)}
                                            />
                                            {errors.registration_number && <div className="text-xs text-red-500 mt-1">{errors.registration_number}</div>}
                                        </div>
                                    </div>
                                    {/* Trading Name / VAT */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Trading Name (If applicable)
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.trading_name}
                                                onChange={e => handleChange('trading_name', e.target.value)}
                                            />
                                            {errors.trading_name && <div className="text-xs text-red-500 mt-1">{errors.trading_name}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                VAT Registration No
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.vat_number}
                                                onChange={e => handleChange('vat_number', e.target.value)}
                                            />
                                            {errors.vat_number && <div className="text-xs text-red-500 mt-1">{errors.vat_number}</div>}
                                        </div>
                                    </div>
                                    {/* Company Address / Trading Address */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Company Address
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.address}
                                                onChange={e => handleChange('address', e.target.value)}
                                            />
                                            {errors.address && <div className="text-xs text-red-500 mt-1">{errors.address}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Trading Address (If different)
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.trading_address}
                                                onChange={e => handleChange('trading_address', e.target.value)}
                                            />
                                            {errors.trading_address && <div className="text-xs text-red-500 mt-1">{errors.trading_address}</div>}
                                        </div>
                                    </div>
                                    {/* Phone */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">
                                                Telephone No
                                            </label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.phone}
                                                onChange={e => handleChange('phone', e.target.value)}
                                            />
                                            {errors.phone && <div className="text-xs text-red-500 mt-1">{errors.phone}</div>}
                                        </div>
                                    </div>
                                    {/* Activities */}
                                    <div>
                                        <label className="block mb-1 text-xs font-medium text-[#002856]">
                                            Brief details of your company's main activities
                                        </label>
                                        <textarea
                                            className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm mb-0 outline-none focus:ring-2 focus:ring-[#0079C2] transition resize-none"
                                            value={formData.activities_description}
                                            onChange={e => handleChange('activities_description', e.target.value)}
                                            rows={2}
                                        />
                                        {errors.activities_description && <div className="text-xs text-red-500 mt-1">{errors.activities_description}</div>}
                                    </div>
                                    {/* Next Button */}
                                    <div className="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t border-[#E8E7E7]">
                                        <button
                                            type="button"
                                            onClick={handleNext}
                                            className="w-full sm:w-auto px-6 py-2 rounded-md font-semibold bg-[#0079C2] text-white hover:bg-[#005b8c] transition disabled:opacity-60"
                                        >
                                            Next
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* Step 2 */}
                            {currentStep === 2 && (
                                <div className="space-y-5 animate-fade-in">
                                    <h2 className="font-bold text-base md:text-lg text-[#002856] mb-1">Contact Information</h2>
                                    {/* First/Last Name / Email / Job Title */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">First Name <span className="text-red-500">*</span></label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.first_name}
                                                onChange={e => handleChange('first_name', e.target.value)}
                                                autoComplete="off"
                                                required
                                            />
                                            {errors.first_name && <div className="text-xs text-red-500 mt-1">{errors.first_name}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Last Name <span className="text-red-500">*</span></label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.last_name}
                                                onChange={e => handleChange('last_name', e.target.value)}
                                                autoComplete="off"
                                                required
                                            />
                                            {errors.last_name && <div className="text-xs text-red-500 mt-1">{errors.last_name}</div>}
                                        </div>
                                    </div>
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Email <span className="text-red-500">*</span></label>
                                            <input
                                                type="email"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.email}
                                                onChange={e => handleChange('email', e.target.value)}
                                                autoComplete="off"
                                                required
                                            />
                                            {errors.email && <div className="text-xs text-red-500 mt-1">{errors.email}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Job Title</label>
                                            <select
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.job_title}
                                                onChange={e => handleChange('job_title', e.target.value)}
                                            >
                                                {jobTitles.map((jt, idx) => (
                                                    <option value={jt} key={idx}>{jt ? jt : 'Select Job Title'}</option>
                                                ))}
                                            </select>
                                            {errors.job_title && <div className="text-xs text-red-500 mt-1">{errors.job_title}</div>}
                                        </div>
                                    </div>
                                    {/* Purchasing Contact */}
                                    <div>
                                        <label className="block mb-1 text-xs font-medium text-[#002856]">
                                            Purchasing Contact
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                            value={formData.purchasing_contact_name}
                                            onChange={e => handleChange('purchasing_contact_name', e.target.value)}
                                        />
                                        {errors.purchasing_contact_name && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_name}</div>}
                                    </div>
                                    {/* Purchasing Phone / Email */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Telephone No</label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.purchasing_contact_phone}
                                                onChange={e => handleChange('purchasing_contact_phone', e.target.value)}
                                            />
                                            {errors.purchasing_contact_phone && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_phone}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Email</label>
                                            <input
                                                type="email"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.purchasing_contact_email}
                                                onChange={e => handleChange('purchasing_contact_email', e.target.value)}
                                            />
                                            {errors.purchasing_contact_email && <div className="text-xs text-red-500 mt-1">{errors.purchasing_contact_email}</div>}
                                        </div>
                                    </div>
                                    {/* Accounts Contact */}
                                    <div>
                                        <label className="block mb-1 text-xs font-medium text-[#002856]">
                                            Accounts Contact
                                        </label>
                                        <input
                                            type="text"
                                            className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                            value={formData.accounts_contact_name}
                                            onChange={e => handleChange('accounts_contact_name', e.target.value)}
                                        />
                                        {errors.accounts_contact_name && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_name}</div>}
                                    </div>
                                    {/* Accounts Phone / Email */}
                                    <div className="flex flex-col sm:flex-row gap-4">
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Telephone No</label>
                                            <input
                                                type="text"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.accounts_contact_phone}
                                                onChange={e => handleChange('accounts_contact_phone', e.target.value)}
                                            />
                                            {errors.accounts_contact_phone && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_phone}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-1 text-xs font-medium text-[#002856]">Email</label>
                                            <input
                                                type="email"
                                                className="w-full border border-[#D1D5DB] rounded-md px-3 py-2 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#0079C2] transition"
                                                value={formData.accounts_contact_email}
                                                onChange={e => handleChange('accounts_contact_email', e.target.value)}
                                            />
                                            {errors.accounts_contact_email && <div className="text-xs text-red-500 mt-1">{errors.accounts_contact_email}</div>}
                                        </div>
                                    </div>
                                    {/* Buttons */}
                                    <div className="flex flex-col-reverse sm:flex-row justify-between gap-3 mt-6 pt-4 border-t border-[#E8E7E7]">
                                        <button
                                            type="button"
                                            onClick={handlePrevious}
                                            className="w-full sm:w-auto px-6 py-2 rounded-md font-semibold bg-[#0079C2] text-white hover:bg-[#005b8c] transition"
                                        >Previous</button>
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className={`w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white ${processing ? 'bg-[#90C0E4] cursor-not-allowed' : 'bg-[#0079C2] hover:bg-[#005b8c]'}`}
                                        >
                                            {processing ? 'Submitting...' : 'Submit'}
                                        </button>
                                    </div>
                                </div>
                            )}
                        </form>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
