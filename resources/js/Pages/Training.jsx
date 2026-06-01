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
            <Head title="Training" />
            <Header />

            <main>
                <HeroSection
                    bgUrl="/assets/bg-training.png"
                    title="Training and Events"
                    text="We offer a variety of resources to help you stay up-to-date on what you need to know in the industry."
                    textColor="text-white"
                    textSize="text-4xl md:text-6xl"
                />
                {/* Section: Intro Text */}
                <section className="container mx-auto px-4 md:px-12 lg:px-20 py-10 flex items-center min-h-[35vh]">
                    <p className="font-nunito-sans font-medium text-lg md:text-2xl text-center">
                        Grow your business with STS’s learning opportunities. Sign up for a webinar, attend a branch event or stop by an STS Expo near you to gain industry knowledge and learn about new products and solutions. Our in-person events offer plenty of opportunities for networking with others in your industry, and our digital training resources provide you the tools you need to sharpen your skills from anywhere.
                    </p>
                </section>
                {/* Section: Articles (Cards Grid) */}
                <section
                    id="articles"
                    className="container mx-auto py-10 px-4 sm:px-6 md:px-10 lg:px-20"
                >
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
                        <div className="w-full max-w-[420px] mx-auto">
                            <AboutArticleCard
                                image="/assets/dummmy/4da057811344b5b24e0cebd05a110367acce6d17.jpg"
                                title="Branch Events, Expos and Tradeshows"
                                description="Held at various locations across UK&I, STS branch events and the STS Expo Series are an excellent opportunity to demo new products, learn about new supplier offerings and savings, network and much more."
                                buttonLabel="FIND AN EVENT"
                                additionnal={false}
                            />
                        </div>
                        <div className="w-full max-w-[420px] mx-auto">
                            <AboutArticleCard
                                image="/assets/dummmy/76d31484b6e24d55dbd51757391aac276e45a069.jpg"
                                title="STS Academy"
                                description="Improve your knowledge in access control, IP video and more. These online trainings are direct from STS's extensive supplier partner network and can be done all from the comfort of your home or office."
                                buttonLabel="GET STARTED"
                                additionnal={false}
                            />
                        </div>
                        {/* Center and keep the last item the same size as others on sm and md */}
                        <div className="w-full max-w-[420px] mx-auto sm:col-span-2 md:col-span-2 xl:col-span-1 flex justify-center">
                            <AboutArticleCard
                                image="/assets/dummmy/e627bae0f4f8fee6ca10ca03182e4947644236a2.jpg"
                                title="Webinars"
                                description="We offer free webinars presented by experts at STS and leading suppliers on the industry’s top trends, technologies and challenges. Sign up for an upcoming webinar or watch a replay of a past event."
                                buttonLabel="SIGN UP"
                                additionnal={false}
                            />
                        </div>
                    </div>
                </section>
                {/* Section: By the Numbers */}
                <section className="bg-[#F0F2F3] py-10">
                    <h1 className="font-inter font-bold text-2xl md:text-4xl text-center py-6 md:py-10">By the number</h1>
                    <div className="container px-4 md:px-10 xl:px-20 mx-auto">
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-8">
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
                {/* Section: Company headlines and Solutions */}
                <section className="py-10">
                    <div className="container mx-auto py-8 px-4 sm:px-6 md:px-10 lg:px-20">
                        <div className="grid font-nunito-sans grid-cols-1 md:grid-cols-1 xl:grid-cols-2 gap-6 md:gap-8">
                            {/* Card 1 */}
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
                            {/* Card 2 */}
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
