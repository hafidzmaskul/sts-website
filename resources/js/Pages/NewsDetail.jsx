import React from 'react';
import ScaffoldBase from './_ScaffoldBase';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import ExploreButton from '../components/ExploreButton';

export default function NewsDetail({ news }) {
    const data = news
    console.log(data)
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

                    <h1 className='font-montserrat font-bold md:text-5xl text-2xl mb-10 md:mb-50'>{data.title}</h1>
                </div>

                {/* Mobile image is in div, desktop is absolute */}
                <div className=" md:hidden w-full flex justify-center relative z-10 mt-4 mb-8">
                    <div className="w-[80vw] max-w-xs rounded-4xl overflow-hidden">
                        <img
                            src={`/storage/${data.image}`}
                            alt=""
                            className="w-full h-auto rounded-4xl"
                        />
                    </div>
                </div>
                <img
                    src={`/storage/${data.image}`}
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

            <main className="flex-1 container px-10 md:px-20 mx-auto md:mt-50 py-12 text-white" data-aos="fade-up">
                <article className="prose prose-invert  mx-auto">
                  {data.content}
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
                                        href={`/news/${data.slug || ''}`}
                                        className="bg-white text-black w-full px-6 py-1 rounded-full font-semibold shadow hover:bg-gray-200 transition inline-flex items-center justify-center"
                                    >
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="flex justify-center mt-10">
                        <ExploreButton href="/news">
                            EXPLORE OTHER ARTICLES
                        </ExploreButton>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
