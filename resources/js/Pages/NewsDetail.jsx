import React from 'react';
import ScaffoldBase from './_ScaffoldBase';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function NewsDetail({ slug }) {
  return (
    <div className="">
    <div
        className="relative flex flex-col rounded-b-4xl sm:rounded-b[100px] lg:rounded-b-[100px] overflow-visible"
        style={{
            backgroundImage: 'url(/assets/bg.png)',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        }}
    >
        <Header />
        <div className="w-full flex flex-col items-center text-center mt-20">
            <H1 text="About Us" color="black" />
            <p className="text-roboto text-xs md:text-base font-normal mb-10 md:mb-30 mt-10 max-w-md">
                Learn more about our company, our mission, and the people who make
                it all possible. We are committed to delivering exceptional HR
                solutions that empower teams and organizations to thrive.
            </p>
        </div>

        {/* Mobile image is in div, desktop is absolute */}
        <div className="block md:hidden w-full flex justify-center relative z-10 mt-4 mb-8">
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
                md:w-3/3 w-[60vw] max-w-lg
                z-10
                rounded-4xl
            "
            style={{
                bottom: '-40%',
            }}
        />
    </div>

    <main className="flex-1 container mx-auto px-6 py-12 text-white">
        <h1 className="text-3xl font-bold mb-6" />
    </main>
    <Footer />
</div>
  );
}

