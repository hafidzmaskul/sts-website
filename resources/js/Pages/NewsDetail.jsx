import React from 'react';
import ScaffoldBase from './_ScaffoldBase';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function NewsDetail({ slug }) {
    return (
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
                <div className="container mx-auto px-10 md:px-20 flex flex-col items-center text-center mt-20">
                    <div
                        className="inline-block px-3 py-1 rounded-full bg-[#FFED2E] font-inter font-normal text-xs mb-10"
                    >
                        Categories
                    </div>

                    <h1 className='font-montserrat font-bold md:text-5xl text-2xl mb-10 md:mb-50'>ACAS and Early Conciliation – Arrives from 6th April 2014. Live from May 2014</h1>
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
                md:w-3/3 w-[20vw] max-w-lg
                z-10
                rounded-4xl
            "
                    style={{
                        bottom: '-30%',
                    }}
                />
            </div>

            <main className="flex-1 container mx-auto md:mt-50 px-6 py-12 text-white" data-aos="fade-up">
                <article className="prose prose-invert  mx-auto">
                    <h2>Well like buses, lots of change in the HR and Employment Law world. Nothing for ages, and then all at once.</h2>
                    <p>
                        Hot on the heels of the latest stats for Employment Tribunals which show a 70% drop in cases since the introduction of fees, there are further changes to the Employment Tribunal system. First up, <strong>ACAS Early Conciliation</strong>, which comes into force on the 6th of April.
                    </p>
                    <p>
                        It doesn’t become mandatory until 2 months later, so it will be 6th May before we fully find out how it works.
                    </p>
                    <p>
                        ACAS will be responsible for mandatory conciliation (with limited exceptions) on for a claimant to notify Acas of their intention to bring a tribunal claim before their claim is lodged. Between 6 April and 5 May 2014 the new scheme will be available to prospective claimants.<br />
                        It will then be mandatory for claims presented on or after 6 May 2014.
                    </p>
                    <h3>What Happens Next</h3>
                    <p>
                        The process involves employees submitting a short compulsory form, which can be done online, which is then submitted to ACAS. When this is received by ACAS a process called “Stop the Clock” takes effect which is a period of up to one month during which the employee’s time limit to bring their claim is ‘paused’ to allow ACAS and the parties to explore whether the matter can be resolved through early conciliation. This can be extended by two weeks where parties are in active talks.
                    </p>
                    <h3>What about the Employer?</h3>
                    <p>
                        An employer can also make a request for early conciliation but in these circumstances the “Stop the Clock” process does not apply.
                    </p>
                    <p>
                        An Early Conciliation Support Officer (ECSO) will contact the claimant employee (or representative if they have one) to obtain further details of the matter (the aim is for this to happen by close of play on the working day on which Acas receive the complaint). The ECSO will then explore the possibilities of resolution with both parties.
                    </p>
                    <h3>How will this change things?</h3>
                    <p>
                        It is notable that the employee is required to provide Acas with only very brief details about the nature of their claim. Employers will have to take care that they know what potential claims are being discussed, and potentially settled.
                    </p>
                    <p>
                        If both sides agree to conciliate and an agreement is reached, the ECSO will assist in recording this on a COT3. Care will have to be taken to ensure all claims are properly settled, as the detail provided by the employee will be brief, and it may be that an “all claims” settlement is appropriate. Acas will be willing to apply these terms in Early Conciliation cases (when in standard cases, they will only conciliate on the claims actually lodged at tribunal).
                    </p>
                    <h3>If agreement is not reached/conciliation is not undertaken</h3>
                    <p>
                        Whilst it is mandatory for claimant employees to notify Acas of their intention to bring a tribunal claim, it is not mandatory for either party to actually engage in conciliation. If parties decline to engage in conciliation or the conciliation is unsuccessful, the ECSO will issue a certificate to all parties confirming that the claimant employee has notified Acas as required. Without this certificate, the employee cannot bring a claim.
                    </p>
                    <p>
                        Under the ‘Stop the Clock’ process the claimant employee will have a minimum of one month following the end of early conciliation to submit a claim to the ET, meaning that the time limit for presenting claims could be extended by some two months.
                    </p>
                    <p>
                        Employers should also bear in mind that even if an employee does proceed to make a claim, ACAS will still offer their conciliation services at that stage.
                    </p>
                </article>
                <section className='py-20'>
                    <H1 text={'Other Article'} color='white' className='uppercase' />


                    <div className="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div
                            className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-row items-stretch"
                            style={{
                                background:
                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                            }}
                        >
                            {/* Image (kiri) */}
                            <div className="w-2/4  rounded-l-2xl overflow-hidden bg-white/40 flex items-center justify-center">
                                <img
                                    src='/assets/detail-service.png'
                                    className="w-full h-full object-cover"
                                />
                            </div>

                            {/* Text (kanan) */}
                            <div
                                className="flex-1 px-6 rounded-r-2xl pt-6 pb-4 flex flex-col"
                                style={{
                                    boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                    background:
                                        'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                }}
                            >
                                {/* Tambahkan ini dan di samping kanannya ada tanggal */}
                                <div className="flex items-center justify-between mb-4">
                                    <div
                                        className="inline-block px-3 py-1 rounded-full bg-[#fff] text-black font-inter font-normal text-xs "
                                    >
                                        Categories
                                    </div>
                                    <span className="text-xs text-[#ffff] font-bold font-inter">
                                        12 Jun 2024
                                    </span>
                                </div>
                                <h3 className="text-lg md:text-2xl font-inter text-white font-bold mb-5 uppercase">
                                ACAS and Early Conciliation – Arrives from 6th April 2014. Live from May 2014
                                </h3>
                                <p className="text-sm  font-regular  md:text-sm font-montserrat text-white mb-4 uppercase tracking-tight ">
                                    With over 18 years of experience in Human Resources, I am a seasoned professional specialising in employment law and Employee Relations (ER) issues. My expertise lies in navigating complex legal
                                </p>
                                <div className="flex w-full justify-center">
                                    <a
                                        href={`/news/${slug || ''}`}
                                        className="bg-white text-black w-full px-6 py-1 rounded-full font-semibold shadow hover:bg-gray-200 transition inline-flex items-center justify-center"
                                    >
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
