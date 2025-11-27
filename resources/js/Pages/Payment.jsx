import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function Payment({ product }) {
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [city, setCity] = useState('');
  const [postcode, setPostcode] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [agreeTerms, setAgreeTerms] = useState(false);

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [apiMessage, setApiMessage] = useState(null);
  const [apiMessageType, setApiMessageType] = useState(''); // 'success' | 'error' | ''

  const rawPrice = product?.price ?? 0;
  const price = Number.isFinite(Number.parseFloat(rawPrice))
    ? Number.parseFloat(rawPrice)
    : 0;
  const vatRate = 0.1; // 10% VAT
  const vat = price * vatRate;

  const total = price + vat;

  const formattedPrice = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(price);

  const formattedVat = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(vat);

  // This will display "$NaN" if total is NaN
  const formattedTotal = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(total);

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!agreeTerms) {
      setApiMessage('You must agree to the Terms and Conditions.');
      setApiMessageType('error');
      return;
    }

    if (!product?.id) {
      setApiMessage('Product is not available for checkout.');
      setApiMessageType('error');
      return;
    }

    setIsSubmitting(true);
    setApiMessage(null);
    setApiMessageType('');

    const payload = {
      product_id: product.id,
      first_name: firstName || 'Jane',
      last_name: lastName || 'Doe',
      email: email || 'jane@example.com',
      mobile_phone: phone || '+628123456789',
      town_city: city || 'Jakarta',
      postcode: postcode || '554123',
      payment_method: 'payment_gateway',
      vat: vat, // tambahkan 'vat' ke payload jika diperlukan
    };

    try {
      const response = await fetch('/api/checkout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        const message =
          data.message ||
          data.error ||
          'Failed to process payment. Please try again.';
        setApiMessage(message);
        setApiMessageType('error');
        return;
      }

      if (data.redirect_url) {
        // smooth small delay for UX
        setApiMessage('Redirecting to payment page...');
        setApiMessageType('success');
        setTimeout(() => {
          window.location.href = data.redirect_url;
        }, 800);
        return;
      }

      setApiMessage(
        data.message || 'Payment request sent. Please check your email or dashboard.',
      );
      setApiMessageType('success');
    } catch (error) {
      setApiMessage('Failed to send payment request. Please check your connection.');
      setApiMessageType('error');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <>
    <Head title={product?.name ? `Payment - ${product.name}` : 'Payment'} />
    <div className="bg-[#302F2F]" data-aos="fade-in">
      <Header />
      <div className="w-full flex flex-col items-center text-center mt-20" data-aos="fade-up">
        <H1 text="PAYMENT" color="white" />
      </div>
      <img
        src="/assets/gradient-payment.svg"
        alt=""
        className="pointer-events-none select-none absolute -z-10 top-50 left-0 w-1/4 max-w-lg opacity-60"
        style={{
            objectFit: "contain",
        }}
        aria-hidden="true"
      />
      <img
        src="/assets/gradient-payment2.svg"
        alt=""
        className="pointer-events-none select-none absolute -z-10 bottom-100 right-0 w-1/4 max-w-lg opacity-60"
        style={{
            objectFit: "contain",
        }}
        aria-hidden="true"
      />
      <main
        className="flex-1 container mx-auto px-10 md:px-20 py-12"
        data-aos="fade-up"
        data-aos-delay="100"
      >
        <div className="container mx-auto mt-12 mb-20">
          <div className="text-center" data-aos="fade-up" data-aos-delay="150">
          </div>


          <div
            className="mt-10 flex flex-col md:flex-row justify-center items-start gap-10 bg-[#FFFFFF0A] p-6 md:p-10 rounded-2xl font-inter"
            style={{
              backdropFilter: 'blur(38px)',
              WebkitBackdropFilter: 'blur(38px)',
            }}
            data-aos="fade-up"
            data-aos-delay="200"
          >
            {/* Left: Payment form */}
            <div className="w-full md:w-1/2">
              <h2 className="text-2xl md:text-3xl py-4 text-white font-inter">
                Fill Personal Information
              </h2>

              {apiMessage && (
                <div className="mb-4" id="api-message">
                  <div
                    className={`rounded px-4 py-3 ${
                      apiMessageType === 'success'
                        ? 'bg-green-800/60 text-white'
                        : 'bg-red-800/60 text-white'
                    }`}
                  >
                    {apiMessage}
                  </div>
                </div>
              )}

              <form
                id="paymentForm"
                className="font-inter"
                onSubmit={handleSubmit}
              >
                <div className="flex flex-col md:flex-row gap-6 mb-6">
                  <div className="w-full md:w-1/2">
                    <input
                      type="text"
                      id="first_name"
                      name="first_name"
                      placeholder="First Name"
                      className="w-full px-4 py-2 rounded-lg border font-inter"
                      style={{
                        border: '1px solid #FFFFFF33',
                        background: 'transparent',
                        color: 'white',
                      }}
                      value={firstName}
                      onChange={(e) => setFirstName(e.target.value)}
                    />
                  </div>
                  <div className="w-full md:w-1/2">
                    <input
                      type="text"
                      id="last_name"
                      name="last_name"
                      placeholder="Last Name"
                      className="w-full px-4 py-2 rounded-lg border font-inter"
                      style={{
                        border: '1px solid #FFFFFF33',
                        background: 'transparent',
                        color: 'white',
                      }}
                      value={lastName}
                      onChange={(e) => setLastName(e.target.value)}
                    />
                  </div>
                </div>

                <div className="mb-6">
                  <input
                    type="text"
                    id="city"
                    name="city"
                    required
                    placeholder="Town / City *"
                    className="w-full px-4 py-2 rounded-lg border font-inter"
                    style={{
                      border: '1px solid #FFFFFF33',
                      background: 'transparent',
                      color: 'white',
                    }}
                    value={city}
                    onChange={(e) => setCity(e.target.value)}
                  />
                </div>

                <div className="mb-6">
                  <input
                    type="text"
                    id="postcode"
                    name="postcode"
                    required
                    placeholder="Postcode *"
                    className="w-full px-4 py-2 rounded-lg border font-inter"
                    style={{
                      border: '1px solid #FFFFFF33',
                      background: 'transparent',
                      color: 'white',
                    }}
                    value={postcode}
                    onChange={(e) => setPostcode(e.target.value)}
                  />
                </div>

                <div className="mb-6">
                  <input
                    type="text"
                    id="phone"
                    name="phone"
                    required
                    placeholder="Mobile Phone Number *"
                    className="w-full px-4 py-2 rounded-lg border font-inter"
                    style={{
                      border: '1px solid #FFFFFF33',
                      background: 'transparent',
                      color: 'white',
                    }}
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                  />
                </div>

                <div className="mb-6">
                  <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email Address"
                    className="w-full px-4 py-2 rounded-lg border font-inter"
                    style={{
                      border: '1px solid #FFFFFF33',
                      background: 'transparent',
                      color: 'white',
                    }}
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                </div>

                <p className="py-4 text-white text-sm md:text-base">
                  Purchasing on behalf of another company? Please tick this box and
                  enter the applicant company details below. These details will be used
                  to create the account on the assessment platform and the login
                  credentials will be sent to the named person in this section.
                </p>

                <button
                  id="submitPaymentBtn"
                  type="submit"
                  disabled={isSubmitting}
                  className="text-[#302F2F] px-20 font-inter py-2 rounded-xl font-semibold text-xs transition"
                  style={{
                      background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)',
                  }}
                >
                  {isSubmitting && (
                    <span className="mr-2 inline-block h-4 w-4 rounded-full border-2 border-white/60 border-t-transparent animate-spin" />
                  )}
                  <span id="btn-text" className="inline-flex items-center">
                    {isSubmitting ? 'Processing payment...' : 'Submit'}
                  </span>
                </button>
              </form>
            </div>

            {/* Right: Summary & payment methods */}
            <div className="w-full md:w-1/2 mb-10 md:mb-0">
              <h2 className="text-2xl md:text-3xl mt-4 text-white font-inter">
                Payment
              </h2>

              <div className="mt-4 text-white font-inter">
                <table className="w-full mb-4">
                  <tbody>
                    <tr>
                      <td className="text-lg text-left">
                        {product?.name ?? 'Selected Product'}
                      </td>
                      <td className="text-lg text-right">
                        {formattedPrice}
                      </td>
                    </tr>
                    <tr>
                      <td className="text-lg text-left">VAT (10%)</td>
                      <td className="text-lg text-right">{formattedVat}</td>
                    </tr>
                  </tbody>
                </table>
                <hr />
                <table className="w-full mt-4 mb-4">
                  <tbody>
                    <tr>
                      <td className="text-lg text-left">Total</td>
                      <td className="text-lg text-right">{formattedTotal}</td>
                    </tr>
                  </tbody>
                </table>

                <hr
                  style={{
                    height: '2px',
                    border: 'none',
                    background:
                      'linear-gradient(298.54deg, #840CCF -7.7%, #39B3F7 47.73%, #5CE989 95.1%)',
                    borderRadius: '999px',
                  }}
                />

                <h3 className="text-2xl mt-4 text-white font-inter">
                  Choose Payment
                </h3>

                <div className="mt-4">
                  <table className="w-full">
                    <tbody>
                      <tr>
                        <td className="py-2">
                          <label className="flex items-center gap-2 font-inter">
                            <span className="inline-flex h-3 w-3 rounded-full bg-[#39B3F7]" />
                            <span className="text-lg text-white">Square</span>
                          </label>
                        </td>
                        <td className="py-2 text-right w-10">
                          <svg
                            width="32"
                            height="32"
                            viewBox="0 0 48 48"
                            fill="none"
                          >
                            <rect width="48" height="48" rx="12" fill="#fff" />
                            <rect
                              x="12"
                              y="12"
                              width="24"
                              height="24"
                              rx="6"
                              fill="#fff"
                              stroke="#212142"
                              strokeWidth="2"
                            />
                            <rect
                              x="20"
                              y="20"
                              width="8"
                              height="8"
                              rx="2"
                              fill="#39B3F7"
                            />
                          </svg>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <hr className="my-4" />
                  <p className="text-white text-sm md:text-base">
                    Your personal data will be used to process your order, support
                    your experience throughout this website, and for other purposes
                    described in our Privacy Policy.
                  </p>
                  <hr className="my-4" />

                  <div className="flex items-start gap-2 mt-4 mb-4">
                    <input
                      type="checkbox"
                      id="terms"
                      name="terms"
                      className="accent-[#39B3F7] mt-1"
                      checked={agreeTerms}
                      onChange={(e) => setAgreeTerms(e.target.checked)}
                    />
                    <span className="text-white text-sm md:text-base">
                      I have read and agree to the website{' '}
                      <a
                        href="#"
                        className="text-[#56DF9D] hover:text-[#212142] underline"
                      >
                        Terms and Conditions
                      </a>
                      *
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <Footer />
    </div>
    </>
  );
}
