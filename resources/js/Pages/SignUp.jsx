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

export default function BecomeCustomer() {
    const [data, setData] = useState({
        account: '',
        email: '',
        first_name: '',
        last_name: '',
        job_title: '',
    });

    const [errors, setErrors] = useState({});
    const [processing, setProcessing] = useState(false);

    // Stepper state for Sign In Form
    const [currentStep, setCurrentStep] = useState(1);
    const [completedSteps, setCompletedSteps] = useState([]);
    const [formData, setFormData] = useState({
        company_name: '',
        company_registration_no: '',
        trading_name: '',
        company_address: '',
        vat_registration_no: '',
        telephone_no: '',
        fax_no: '',
        company_activities: '',
        purchasing_contact: '',
        purchasing_telephone: '',
        purchasing_email: '',
        bank_name_address: '',
        sort_code: '',
        account_number: '',
        trade_ref1_name_address: '',
        trade_ref1_telephone: '',
        trade_ref1_email: '',
        trade_ref2_name_address: '',
        trade_ref2_telephone: '',
        trade_ref2_email: '',
        credit_limit: '',
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});
        try {
            const response = await fetch('http://127.0.0.1:8000/api/sign-up', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            const result = await response.json();

            if (response.ok) {
                toast('Registration submitted successfully.', 'success');
                setData({
                    account: '',
                    email: '',
                    first_name: '',
                    last_name: '',
                    job_title: '',
                });
            } else {
                if (result.errors) {
                    setErrors(result.errors);
                    // Optionally, tampilkan error global:
                    if (result.message) toast(result.message, 'error');
                } else if (result.message) {
                    toast(result.message, 'error');
                } else {
                    toast('Registration failed. Please check your input.', 'error');
                }
            }
        } catch (err) {
            toast('Something went wrong. Please try again later.', 'error');
        } finally {
            setProcessing(false);
        }
    };

    // Stepper functions
    const steps = [
        { number: 1, title: 'Company Information', desc: 'Basic Company Details' },
        { number: 2, title: 'Contact Information', desc: 'Key personnel contacts' },
        { number: 3, title: 'Financial & References', desc: 'Banking and trade references' },
    ];

    const handleNext = () => {
        if (currentStep < 3) {
            setCompletedSteps([...completedSteps, currentStep]);
            setCurrentStep(currentStep + 1);
        }
    };

    const handlePrevious = () => {
        if (currentStep > 1) {
            setCurrentStep(currentStep - 1);
            setCompletedSteps(completedSteps.filter(step => step !== currentStep - 1));
        }
    };

    const handleFormDataChange = (field, value) => {
        setFormData({ ...formData, [field]: value });
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

    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Become Customer" />
            <Header />

            <main className="">
                <section className="bg-[#F0F2F3] py-10 px-10 container mx-auto my-20">
                    <div className="text-center">
                        <h1 className='text-[#002856] font-bold font-inter text-2xl'>STS | Sign In or Register for an Online Account</h1>
                    </div>
                    <div className="flex flex-col mx-auto mt-8 text-left max-w-4xl">
                        {/* Sign In Form with Stepper */}
                        <form className="w-full">
                            <h2 className="text-2xl font-bold text-[#002856] mb-4">Sign In</h2>
                            <span>*Please enter information in required fields. </span>

                            {/* Stepper Indicator */}
                            <div className="mt-10 mb-8">
                                <div className="flex items-start justify-between">
                                    {steps.map((step, index) => {
                                        const status = getStepStatus(step.number);
                                        const isLast = index === steps.length - 1;

                                        return (
                                            <React.Fragment key={step.number}>
                                                <div className="flex flex-col items-center" style={{ flex: isLast ? '0 0 auto' : '1 1 0' }}>
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

                            <div className="mt-10 bg-white p-10 space-y-6 overflow-auto">
                                {/* Step 1: Company Information */}
                                {currentStep === 1 && (
                                    <div className="space-y-6">
                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Company Name <span className="text-red-500">*</span>
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.company_name}
                                                    onChange={e => handleFormDataChange('company_name', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                    required
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Company Registration No:
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.company_registration_no}
                                                    onChange={e => handleFormDataChange('company_registration_no', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Trading Name (If applicable)
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.trading_name}
                                                    onChange={e => handleFormDataChange('trading_name', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Company Address
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.company_address}
                                                    onChange={e => handleFormDataChange('company_address', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    VAT Registration No:
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.vat_registration_no}
                                                    onChange={e => handleFormDataChange('vat_registration_no', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Telephone No
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.telephone_no}
                                                    onChange={e => handleFormDataChange('telephone_no', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Fax No
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.fax_no}
                                                    onChange={e => handleFormDataChange('fax_no', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Brief details of your company's main activities
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.company_activities}
                                                    onChange={e => handleFormDataChange('company_activities', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Step 2: Contact Information */}
                                {currentStep === 2 && (
                                    <div className="space-y-6">
                                        <div>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Purchasing Contact
                                            </label>
                                            <input
                                                type="text"
                                                value={formData.purchasing_contact}
                                                onChange={e => handleFormDataChange('purchasing_contact', e.target.value)}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            />
                                        </div>

                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Telephone No
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.purchasing_telephone}
                                                    onChange={e => handleFormDataChange('purchasing_telephone', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Email
                                                </label>
                                                <input
                                                    type="email"
                                                    value={formData.purchasing_email}
                                                    onChange={e => handleFormDataChange('purchasing_email', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Step 3: Financial & References */}
                                {currentStep === 3 && (
                                    <div className="space-y-6">
                                        <div>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Bank Name, Address
                                            </label>
                                            <input
                                                type="text"
                                                value={formData.bank_name_address}
                                                onChange={e => handleFormDataChange('bank_name_address', e.target.value)}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            />
                                        </div>

                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Sort Code
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.sort_code}
                                                    onChange={e => handleFormDataChange('sort_code', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Account Number
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.account_number}
                                                    onChange={e => handleFormDataChange('account_number', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Name & Address of Trade Reference 1
                                            </label>
                                            <input
                                                type="text"
                                                value={formData.trade_ref1_name_address}
                                                onChange={e => handleFormDataChange('trade_ref1_name_address', e.target.value)}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            />
                                        </div>
                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Telephone No
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.trade_ref1_telephone}
                                                    onChange={e => handleFormDataChange('trade_ref1_telephone', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Email
                                                </label>
                                                <input
                                                    type="email"
                                                    value={formData.trade_ref1_email}
                                                    onChange={e => handleFormDataChange('trade_ref1_email', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Name & Address of Trade Reference 2
                                            </label>
                                            <input
                                                type="text"
                                                value={formData.trade_ref2_name_address}
                                                onChange={e => handleFormDataChange('trade_ref2_name_address', e.target.value)}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            />
                                        </div>
                                        <div className="flex flex-col md:flex-row md:space-x-8">
                                            <div className="flex-1">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Telephone No
                                                </label>
                                                <input
                                                    type="text"
                                                    value={formData.trade_ref2_telephone}
                                                    onChange={e => handleFormDataChange('trade_ref2_telephone', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                            <div className="flex-1 mt-6 md:mt-0">
                                                <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                    Email
                                                </label>
                                                <input
                                                    type="email"
                                                    value={formData.trade_ref2_email}
                                                    onChange={e => handleFormDataChange('trade_ref2_email', e.target.value)}
                                                    className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                />
                                            </div>
                                        </div>

                                        <div className="text-xs text-[#000] font-poppins mt-2">
                                            * Please Note: References must reflect your required credit limit.
                                        </div>

                                        <div>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Credit Limit Required
                                            </label>
                                            <input
                                                type="text"
                                                value={formData.credit_limit}
                                                onChange={e => handleFormDataChange('credit_limit', e.target.value)}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            />
                                        </div>
                                    </div>
                                )}

                                {/* Navigation Buttons */}
                                <div className="flex justify-between mt-8 pt-6 border-t border-[#E8E7E7]">
                                    <button
                                        type="button"
                                        onClick={handlePrevious}
                                        disabled={currentStep === 1}
                                        className={`px-8 py-2 rounded font-medium ${
                                            currentStep === 1
                                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                : 'bg-[#0079C2] text-white hover:bg-[#005b8c]'
                                        }`}
                                    >
                                        Previous
                                    </button>
                                    <button
                                        type="button"
                                        onClick={handleNext}
                                        disabled={currentStep === 3}
                                        className={`px-8 py-2 rounded font-medium ${
                                            currentStep === 3
                                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                : 'bg-[#0079C2] text-white hover:bg-[#005b8c]'
                                        }`}
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>

                        {/* Registration Form - Hidden */}
                        {false && (
                            <form onSubmit={handleSubmit} className="flex-1">
                                <h2 className="text-2xl font-bold text-[#002856] mb-4">Register</h2>
                                <span>*Please enter information in required fields.</span>

                                <div className="mt-10 p-10 space-y-6 md:max-h-none md:h-auto overflow-auto bg-white">
                                    <div className="flex flex-col md:flex-row md:space-x-8">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Account <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.account}
                                                onChange={e => setData({ ...data, account: e.target.value })}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                            {errors.account && <div className="text-red-500 text-xs mt-1">{errors.account}</div>}
                                        </div>
                                        <div className="flex-1 mt-6 md:mt-0">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Email Address <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="email"
                                                value={data.email}
                                                onChange={e => setData({ ...data, email: e.target.value })}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                            {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
                                        </div>
                                    </div>
                                    <div className="flex flex-col md:flex-row gap-4 text-xs font-poppins text-[#000000]">
                                        <span>
                                            Don't have an account number?&nbsp;
                                            <a href="#" className="text-[#0079C2] underline">Become a customer.</a>
                                        </span>
                                        <span className="ml-0 md:ml-4 text-[#707070]">
                                            This will become your username.
                                        </span>
                                    </div>

                                    <div className="flex flex-col md:flex-row md:space-x-8">
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                First Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.first_name}
                                                onChange={e => setData({ ...data, first_name: e.target.value })}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                            {errors.first_name && <div className="text-red-500 text-xs mt-1">{errors.first_name}</div>}
                                        </div>
                                        <div className="flex-1">
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Last Name <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.last_name}
                                                onChange={e => setData({ ...data, last_name: e.target.value })}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                                required
                                            />
                                            {errors.last_name && <div className="text-red-500 text-xs mt-1">{errors.last_name}</div>}
                                        </div>
                                    </div>
                                    <div className="flex flex-col md:flex-row md:space-x-8">
                                        <div className='flex-1 mt-6 md:mt-0'>
                                            <label className="block mb-2 text-xs font-normal font-poppins text-[#000000]">
                                                Select Job Title
                                            </label>
                                            <select
                                                value={data.job_title}
                                                onChange={e => setData({ ...data, job_title: e.target.value })}
                                                className="w-full border-0 border-b border-[#000000] text-[#000000] font-poppins text-xs font-normal px-0 py-2 bg-transparent focus:outline-none focus:border-b-2 focus:border-[#000000]"
                                            >
                                                <option value="">Select Job Title</option>
                                                <option value="manager">Manager</option>
                                                <option value="technician">Technician</option>
                                                <option value="engineer">Engineer</option>
                                                <option value="purchasing">Purchasing</option>
                                                <option value="other">Other</option>
                                            </select>
                                            {errors.job_title && <div className="text-red-500 text-xs mt-1">{errors.job_title}</div>}
                                        </div>
                                    </div>

                                    <div className="flex justify-start mt-8">
                                        <button type="submit" disabled={processing} className="bg-[#0079C2] text-white px-20 py-2 rounded font-medium hover:bg-[#005b8c] flex items-center justify-center gap-2">
                                            {processing ? 'Registering...' : 'Register Now'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        )}
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
