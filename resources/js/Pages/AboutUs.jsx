import React from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import AboutArticleCard from '../components/AboutArticleCard';
import AboutStatsCard from '../components/AboutStatsCard';

// Dummy data for AboutStatsCard
const aboutStatsData = [
    {
        title: '200+',
        image: '/assets/earth.svg',
        text: 'Locations in 17 countries',
    },
    {
        title: '2700+',
        image: '/assets/team.svg',
        text: 'Team members',
    },
    {
        title: '1M+',
        image: '/assets/space.svg',
        text: 'Square feet of distribution space',
    },
    {
        title: '40M+',
        image: '/assets/unit.svg',
        text: 'Units pass through our hubs each year',
    },
    {
        title: '35 HRS',
        image: '/assets/hours.svg',
        text: 'Of training for team members',
    },
    {
        title: '350K+',
        image: '/assets/product.svg',
        text: 'Products available',
    },
    {
        title: '8M+',
        image: '/assets/inventory.svg',
        text: 'Units in inventory',
    },
    {
        title: '$3.4B',
        image: '/assets/sales.svg',
        text: 'Net sales',
    },
];

export default function AboutUs({ teamMembers = [] }) {
    // Tidak perlu grouping manual: gunakan CSS grid untuk 4 kolom responsif
    return (
        <div className="min-h-screen flex flex-col">
            <Head title="About Us" />
            <Header />

            <main className="">

                <HeroSection
                    bgUrl="/assets/bg-about.jpg"
                    title="About Us"
                    text="The leading security and low-voltage distributor and a trusted name among installers and dealers"
                    textColor="text-white"
                />
                <section className='container md:px-20 px-20 mx-auto h-[50vh] flex justify-center items-center'>
                    <p className='font-nunito-sans font-medium text-2xl text-center  '>
                        STS is the leading global wholesale distributor of security, AV and low-voltage products with more than
                        25 years in the business. Our extensive global footprint reaches more than 200 locations in 17 countries
                        and is combined with our strategic supplier relationships and focus on customer service to serve you with
                        one of the widest ranges of products and services of any low-voltage distributor.</p>
                </section>
                <section
                    id="articles"
                    className="container mx-auto px-6 md:px-10 lg:px-20 py-10"
                >
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <AboutArticleCard
                            image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                            title="Our customers"
                            description="We serve more than 100,000 customers globally, from independent contractors to national business accounts, by offering thousands of products as well as services and expertise. In fact, over 78% of the SDM 100 companies buy from ADI."
                            buttonLabel="Become a Customer"
                            additionnal={true}
                        />
                        <AboutArticleCard
                            image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                            title="Leading pros rely on STS."
                            description="Our customers demand a high-performance distributor. See what makes ADI stand out from competitors, and why we’re the leading security and low-voltage distributor."
                            buttonLabel="See Why"
                            additionnal={false}
                        />
                        <AboutArticleCard
                            image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                            title="Services and resources"
                            description="It’s our mission to deliver an exceptional experience at every touchpoint – in store, by phone and online. We support your business with pre-sales support, on-demand training and other resources to boost your industry knowledge and help you grow."
                            buttonLabel="Explore Service"
                            additionnal={false}
                        />
                    </div>
                </section>

                <section className='bg-[#F0F2F3] py-10'>
                    <h1 className='font-inter font-bold text-4xl text-center py-10'>By the number</h1>
                    {/* Tanpa double loop, gunakan grid untuk membuat 4 kolom per baris */}
                    <div className="container px-10 md:px-20 mx-auto">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 ">
                            {aboutStatsData.map((item, idx) => (
                                <AboutStatsCard
                                    key={idx}
                                    title={item.title}
                                    image={item.image}
                                    text={item.text}
                                />
                            ))}
                        </div>
                    </div>
                </section>
                <section className='py-10'>
                    <div className="container mx-auto px-6 md:px-10 lg:px-20 py-10">
                        <div className="grid font-nunito-sans grid-cols-1 md:grid-cols-2 gap-8">

                                <div className=" bg-[#0079C2] flex p-5 rounded-xl">
                                    <div className="w-full md:w-6/8 flex flex-col justify-center text-white">
                                        <h2 className="font-bold text-2xl mb-2">Company Headlines</h2>
                                        <p className="text-white text-base mb-4">Read the latest news and press releases from STS</p>
                                        <div className="">
                                            <button className='text-[#0079C2] text-base bg-white rounded-lg py-1 px-10'>View News</button>
                                        </div>
                                    </div>
                                    <div className="w-full md:w-2/8 flex justify-center items-center">
                                        <img src="/assets/company.svg" alt="Company" className="max-w-full h-auto" />
                                    </div>
                                </div>

                                <div className=" bg-[#0079C2] flex p-5 rounded-xl">
                                    <div className="w-full md:w-6/8 flex flex-col justify-center text-white">
                                        <h2 className="font-bold text-2xl mb-2">Solutions</h2>
                                        <p className="text-white text-base mb-4">Explore how we can help you design best-in-class systems</p>
                                        <div className="">
                                            <button className='text-[#0079C2] text-base bg-white rounded-lg py-1 px-10'>View News</button>
                                        </div>
                                    </div>
                                    <div className="w-full md:w-2/8 flex justify-center items-center">
                                        <img src="/assets/solution.svg" alt="Company" className="max-w-full h-auto" />
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
