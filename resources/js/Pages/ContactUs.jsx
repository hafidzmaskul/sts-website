import React, { useState } from 'react';
import axios from 'axios';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function ContactUs() {
    const [form, setForm] = useState({
        name: '',
        email: '',
        subject: '',
        message: '',
    });

    const [errors, setErrors] = useState({});
    const [status, setStatus] = useState(null); // 'success' | 'error' | null
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleChange = (event) => {
        const { id, value } = event.target;

        setForm((previous) => ({
            ...previous,
            [id]: value,
        }));

        if (errors[id]) {
            setErrors((previous) => ({
                ...previous,
                [id]: null,
            }));
        }
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        setStatus(null);

        const newErrors = {};

        if (!form.name) {
            newErrors.name = 'Name is required.';
        }

        if (!form.email) {
            newErrors.email = 'Email is required.';
        }

        if (!form.subject) {
            newErrors.subject = 'Subject is required.';
        }

        if (!form.message) {
            newErrors.message = 'Message is required.';
        }

        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors);

            setStatus('error');

            return;
        }

        try {
            setIsSubmitting(true);

            const response = await axios.post('/api/contact', form);

            if (response.data?.success) {
                setStatus('success');
                setForm({
                    name: '',
                    email: '',
                    subject: '',
                    message: '',
                });
                setErrors({});
            } else {
                setStatus('error');
            }
        } catch (error) {
            if (error.response?.status === 422 && error.response.data?.errors) {
                setErrors(error.response.data.errors);
            }

            setStatus('error');
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="" data-aos="fade-in">
            <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'Contact Us'} color='black' />
                    <p className='text-roboto text-xs md:text-base font-normal mb-10 md:mb-20 mt-10 max-w-md'>
                        Lorem ipsum dolor sit amet consectetur adipiscing elit.  Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu
                        aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl  malesuada lacinia integer nunc posuere.
                    </p>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-6 py-12 text-white" data-aos="fade-up">
                <div className="flex flex-col gap-8 md:flex-row md:gap-16 w-full">
                    <form
                        onSubmit={handleSubmit}
                        className="w-full md:w-2/3 lg:w-1/2 flex flex-col gap-5 "
                        style={{
                            backdropFilter: 'blur(2px)',
                            maxWidth: '400px', // Membuat form jadi lebih sempit dari maps di desktop
                        }}
                    >
                        <div className="flex flex-col text-white">
                            <label htmlFor="name" className="text-sm mb-1 font-semibold">Your Name</label>
                            <input
                                type="text"
                                id="name"
                                value={form.name}
                                onChange={handleChange}
                                className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                placeholder="Type Your name"
                                style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                            />
                            {errors.name && (
                                <p className="mt-1 text-xs text-red-500 animate-pulse">{errors.name}</p>
                            )}
                        </div>
                        <div className="flex flex-col text-white">
                            <label htmlFor="email" className="text-sm mb-1 font-semibold">Your Email</label>
                            <input
                                type="email"
                                id="email"
                                value={form.email}
                                onChange={handleChange}
                                className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                placeholder="Type Your email"
                                style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                            />
                            {errors.email && (
                                <p className="mt-1 text-xs text-red-500 animate-pulse">{errors.email}</p>
                            )}
                        </div>
                        <div className="flex flex-col text-white">
                            <label htmlFor="subject" className="text-sm mb-1 font-semibold">Subject</label>
                            <input
                                type="text"
                                id="subject"
                                value={form.subject}
                                onChange={handleChange}
                                className="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                placeholder="Type the subject"
                                style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                            />
                            {errors.subject && (
                                <p className="mt-1 text-xs text-red-500 animate-pulse">{errors.subject}</p>
                            )}
                        </div>
                        <div className="flex flex-col text-white">
                            <label htmlFor="message" className="text-sm mb-1 font-semibold">Message</label>
                            <textarea
                                id="message"
                                value={form.message}
                                onChange={handleChange}
                                className="px-4 py-2 rounded-lg border border-gray-300 h-30 resize-none focus:outline-none focus:ring-2 font-monserat font-normal text-sm"
                                placeholder="Type Your message here..."
                                style={{
                                    background: 'linear-gradient(181.35deg, rgba(255, 255, 255, 0.5) 1.15%, rgba(255, 255, 255, 0) 98.91%)',
                                }}
                            />
                            {errors.message && (
                                <p className="mt-1 text-xs text-red-500 animate-pulse">{errors.message}</p>
                            )}
                        </div>

                        {status === 'success' && (
                            <div className="mt-2 flex items-center gap-2 rounded-lg bg-emerald-500/10 border border-emerald-400/40 px-4 py-2 text-emerald-100 animate-[fade-in_0.2s_ease-out]">
                                <span className="inline-block h-2 w-2 rounded-full bg-emerald-400 animate-ping" />
                                <p className="text-xs md:text-sm">
                                    Thank you! Your message has been sent successfully.
                                </p>
                            </div>
                        )}

                        {status === 'error' && Object.keys(errors).length === 0 && (
                            <div className="mt-2 flex items-center gap-2 rounded-lg bg-red-500/10 border border-red-400/40 px-4 py-2 text-red-100 animate-[fade-in_0.2s_ease-out]">
                                <span className="inline-block h-2 w-2 rounded-full bg-red-400 animate-ping" />
                                <p className="text-xs md:text-sm">
                                    Something went wrong while sending your message. Please try again.
                                </p>
                            </div>
                        )}

                        <div className="">
                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="mt-2 text-black py-2 font-inter text-base px-20 rounded-lg font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed"
                                style={{
                                    background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)'
                                }}
                            >
                                {isSubmitting ? (
                                    <span className="flex items-center gap-2 justify-center">
                                        <span className="h-4 w-4 rounded-full border-2 border-black/40 border-t-black animate-spin" />
                                        Sending...
                                    </span>
                                ) : (
                                    'Submit'
                                )}
                            </button>
                        </div>
                    </form>
                    <div className="flex-1 flex flex-col items-center md:items-start justify-start gap-6 text-white mt-10 md:mt-0">


                        <h2 className="font-montserrat text-1xl md:text-3xl font-extrabold mb-2 text-center md:text-left">Please Contact Us We Are Happy To Help</h2>
                        <p className="font-roboto text-xs md:text-sm fonr-normal mb-4 text-center md:text-left">We’re always interested in new projects, big or small. Send us an email andwe’ll get in touch shortly, or phone anytime Monday to Sunday.</p>
                        <div className="w-full flex flex-col gap-4">
                            <h6 className='font-montserrat font-bold text-base md:text-xl ' >
                                Absolutely Human Resources Limited
                            </h6>
                            <iframe
                                title="Location Map 1"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3162.918282069634!2d-122.08424908425455!3d37.4220656798256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fb6a3ba93926f%3A0x1a8e12454fab5b15!2sGoogleplex!5e0!3m2!1sen!2sid!4v1678898888888!5m2!1sen!2sid"
                                className="w-full rounded-lg"
                                style={{ minHeight: '160px', border: 0 }}
                                loading="lazy"
                                allowFullScreen=""
                                referrerPolicy="no-referrer-when-downgrade"
                            ></iframe>
                            <h6 className='font-montserrat font-bold text-base md:text-xl ' >
                                Absolutely Human Resources Limited
                            </h6>
                            <iframe
                                title="Location Map 2"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253857.7392261356!2d106.65559836487996!3d-6.229728105728672!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f157ae6aa3fb%3A0x301576d14febd9d4!2sJakarta!5e0!3m2!1sen!2sid!4v1678898888889!5m2!1sen!2sid"
                                className="w-full rounded-lg"
                                style={{ minHeight: '160px', border: 0 }}
                                loading="lazy"
                                allowFullScreen=""
                                referrerPolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </main>
            <Footer />
        </div>
    );
}
