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
                {/* Main text section: Responsive for mobile, tablet, desktop */}
                <section className="container mx-auto flex justify-center items-center py-12 px-4 sm:py-12 sm:px-6 md:px-10 lg:px-20">
                    <p className="font-nunito-sans font-medium text-lg xs:text-xl sm:text-2xl text-center leading-relaxed">
                        STS is the leading global wholesale distributor of security, AV and low-voltage products with more than
                        25 years in the business. Our extensive global footprint reaches more than 200 locations in 17 countries
                        and is combined with our strategic supplier relationships and focus on customer service to serve you with
                        one of the widest ranges of products and services of any low-voltage distributor.
                    </p>
                </section>
                {/* Articles section: responsive grid */}
                <section
                    id="articles"
                    className="container mx-auto py-10 px-4 sm:px-6 md:px-10 lg:px-20"
                >
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        <div className="w-full max-w-[420px] mx-auto">
                            <AboutArticleCard
                                image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                                title="Our customers"
                                description="We serve more than 100,000 customers globally, from independent contractors to national business accounts, by offering thousands of products as well as services and expertise. In fact, over 78% of the SDM 100 companies buy from ADI."
                                buttonLabel="Become a Customer"
                                additionnal={true}
                            />
                        </div>
                        <div className="w-full max-w-[420px] mx-auto">
                            <AboutArticleCard
                                image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                                title="Leading pros rely on STS."
                                description="Our customers demand a high-performance distributor. See what makes STS stand out from competitors, and why we’re the leading security and low-voltage distributor."
                                buttonLabel="See Why"
                                additionnal={false}
                            />
                        </div>
                        {/* Center and keep the last item the same size as others on sm and md */}
                        <div className="w-full max-w-[420px] mx-auto sm:col-span-2 md:col-span-2 lg:col-span-1 flex justify-center">
                            <AboutArticleCard
                                image="/assets/dummmy/17cf1c96e5c96541e5cafeff7e5646a03a50ef93.png"
                                title="Services and resources"
                                description="It’s our mission to deliver an exceptional experience at every touchpoint – in store, by phone and online. We support your business with pre-sales support, on-demand training and other resources to boost your industry knowledge and help you grow."
                                buttonLabel="Explore Service"
                                additionnal={false}
                            />
                        </div>
                    </div>
                </section>

                {/* Stats Grid: responsive spacing and columns */}
                <section className='bg-[#F0F2F3] py-10'>
                    <h1 className="font-inter font-bold text-2xl sm:text-3xl md:text-4xl text-center py-6 sm:py-10">By the numbers</h1>
                    <div className="container mx-auto px-4 sm:px-6 md:px-10 lg:px-20">
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-8">
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

                {/* Company headlines & solutions: responsive flex and spacing */}
                <section className='py-10'>
                    <div className="container mx-auto py-8 px-4 sm:px-6 md:px-10 lg:px-20">
                        <div className="grid font-nunito-sans grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                            {/* Company Headlines Card */}
                            <div className="bg-[#0079C2] flex flex-col sm:flex-row items-center p-4 md:p-6 rounded-xl">
                                <div className="w-full sm:w-3/5 flex flex-col justify-center text-white mb-4 sm:mb-0 sm:mr-5">
                                    <h2 className="font-bold text-xl md:text-2xl mb-2">Company Headlines</h2>
                                    <p className="text-white text-sm sm:text-base mb-4">Read the latest news and press releases from STS</p>
                                    <div>
                                        <a
                                            href="/news"
                                            className="text-[#0079C2] text-sm sm:text-base bg-white rounded-lg py-1 px-6 sm:px-10 inline-block"
                                        >
                                            View News
                                        </a>
                                    </div>
                                </div>
                                <div className="w-full sm:w-2/5 flex justify-center items-center">
                                    <img src="/assets/company.svg" alt="Company" className="max-w-[120px] md:max-w-[140px] h-auto" />
                                </div>
                            </div>
                            {/* Solutions Card */}
                            <div className="bg-[#0079C2] flex flex-col sm:flex-row items-center p-4 md:p-6 rounded-xl">
                                <div className="w-full sm:w-3/5 flex flex-col justify-center text-white mb-4 sm:mb-0 sm:mr-5">
                                    <h2 className="font-bold text-xl md:text-2xl mb-2">Solutions</h2>
                                    <p className="text-white text-sm sm:text-base mb-4">Explore how we can help you design best-in-class systems</p>
                                    <div>
                                        <a
                                            href="/solutions"
                                            className="text-[#0079C2] text-sm sm:text-base bg-white rounded-lg py-1 px-6 sm:px-10 inline-block"
                                        >
                                            View Solutions
                                        </a>
                                    </div>
                                </div>
                                <div className="w-full sm:w-2/5 flex justify-center items-center">
                                    <img src="/assets/solution.svg" alt="Solutions" className="max-w-[120px] md:max-w-[140px] h-auto" />
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
