import React, { useState, useEffect } from 'react';
import CountrySelect from './CountrySelect';
import Toast from './Toast';



const initialFormState = {
    first_name: '',
    last_name: '',
    company_name: '',
    email: '',
    phone: '',
    country: '',
    postal_code: '',
    project_details: '',
    opt_in: false,
};

export default function QuoteForm({
    title = "Get Quote",
    submitEndpoint = "/api/quotes",
    successMessage = "Thank you! Your quote request has been submitted.",
    showTitle = true,
    className = "",
    formId = "quote"
}) {
    const [form, setForm] = useState(initialFormState);
    const [submitting, setSubmitting] = useState(false);
    const [success, setSuccess] = useState(false);
    const [error, setError] = useState(null);
    const [fieldErrors, setFieldErrors] = useState({});
    const [toast, setToast] = useState({ show: false, message: '', type: 'success' });

    // Standard handleChange
    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value,
        }));
        setFieldErrors((prev) => ({
            ...prev,
            [name]: undefined,
        }));
    };

    // Handler for CountrySelect
    const handleCountryChange = (selected) => {
        let value = '';
        if (typeof selected === 'string') {
            value = selected;
        } else if (selected && selected.value) {
            value = selected.value;
        } else if (selected && selected.target && selected.target.value) {
            value = selected.target.value;
        }
        setForm((prev) => ({
            ...prev,
            country: value,
        }));
        setFieldErrors((prev) => ({
            ...prev,
            country: undefined,
        }));
    };

    // On form submit
    const handleSubmit = async (e) => {
        e.preventDefault();
        setSubmitting(true);
        setError(null);
        setSuccess(false);
        setFieldErrors({});

        try {
            const response = await fetch(submitEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(form),
            });

            const data = await response.json();

            if (!response.ok) {
                if (data && typeof data === 'object' && data.errors) {
                    setFieldErrors(data.errors);
                }
                throw new Error(data?.message || 'An error occurred.');
            }

            setSuccess(true);
            setForm(initialFormState);

            setToast({
                show: true,
                message: successMessage,
                type: 'success',
            });

        } catch (err) {
            setError(err.message);
            setToast({
                show: true,
                message: err.message || 'There was an error submitting your request.',
                type: 'error',
            });
        } finally {
            setSubmitting(false);
        }
    };

    // Auto-hide toast after 4 seconds
    useEffect(() => {
        if (toast.show) {
            const timer = setTimeout(() => setToast((t) => ({ ...t, show: false })), 4000);
            return () => clearTimeout(timer);
        }
    }, [toast.show]);

    return (
        <>
            <Toast
                show={toast.show}
                message={toast.message}
                type={toast.type}
                onClose={() => setToast((t) => ({ ...t, show: false }))}
            />
            <div className={`p-5 ${className}`}>
                {showTitle && (
                    <h1 className='text-2xl md:text-3xl font-bold mb-10 text-center'>{title}</h1>
                )}
                <form id={formId} className="px-8 pt-6 pb-8 mb-4 bg-white" onSubmit={handleSubmit}>
                    {error && (
                        <div className="mb-4 p-3 rounded bg-red-100 text-red-700">
                            {error}
                        </div>
                    )}
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            First Name<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="first_name"
                            type="text"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your first name"
                            value={form.first_name}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.first_name && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.first_name[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Surname<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="last_name"
                            type="text"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your surname"
                            value={form.last_name}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.last_name && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.last_name[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Company Name<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="company_name"
                            type="text"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your company name"
                            value={form.company_name}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.company_name && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.company_name[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Email Address<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="email"
                            type="email"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your email address"
                            value={form.email}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.email && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.email[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Phone Number<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="phone"
                            type="tel"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your phone number"
                            value={form.phone}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.phone && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.phone[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Country<span className="text-red-500">*</span>
                        </label>
                        <CountrySelect
                            name="country"
                            value={form.country}
                            onChange={handleCountryChange}
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            required
                        />
                        {fieldErrors.country && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.country[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Postal Code<span className="text-red-500">*</span>
                        </label>
                        <input
                            name="postal_code"
                            type="text"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            placeholder="Enter your postal code"
                            value={form.postal_code}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.postal_code && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.postal_code[0]}</div>
                        )}
                    </div>
                    <div className="mb-6">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Details of your Project<span className="text-red-500">*</span>
                        </label>
                        <textarea
                            name="project_details"
                            className="appearance-none bg-transparent border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none resize-none"
                            rows={3}
                            placeholder="Enter project details"
                            value={form.project_details}
                            onChange={handleChange}
                            required
                        />
                        {fieldErrors.project_details && (
                            <div className="mt-1 text-sm text-red-600">{fieldErrors.project_details[0]}</div>
                        )}
                    </div>
                    <div className="flex items-center mb-6">
                        <input
                            name="opt_in"
                            type="checkbox"
                            id="opt_in"
                            className="mr-2"
                            checked={form.opt_in}
                            onChange={handleChange}
                        />
                        <label htmlFor="opt_in" className="text-gray-700 text-sm">
                            Opt-in to STS Marketing Emails
                        </label>
                    </div>
                    <div className="flex items-center justify-start mt-8">
                        <button
                            type="submit"
                            className="bg-[#0069A9] hover:bg-[#005885] text-white font-normal py-2 px-20 rounded focus:outline-none focus:shadow-outline"
                            disabled={submitting}
                        >
                            {submitting ? 'Submitting...' : 'Submit'}
                        </button>
                    </div>
                </form>
            </div>
        </>
    );
}
