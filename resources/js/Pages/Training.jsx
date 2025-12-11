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
                    bgUrl="/assets/bg-training.png"
                    title="Training and Events"
                    text="We offer a variety of resources to help you stay up-to-date on what you need to know in the industry."
                    textColor="text-white"
                    textSize='text-6xl'
                />
                <section className='container md:px-20 px-20 mx-auto h-[50vh] flex justify-center items-center'>
                    <p className='font-nunito-sans font-medium text-2xl text-center  '>
                    Grow your business with ADI’s learning opportunities. Sign up for a webinar, attend a branch event or stop by an ADI Expo near you to gain industry knowledge and learn about new products and solutions. Our in-person events offer plenty of opportunities for networking with others in your industry, and our digital training resources provide you the tools you need to sharpen your skills from anywhere.
                    </p>
                </section>
                <section
                    id="articles"
                    className="container mx-auto px-6 md:px-10 lg:px-20 py-10"
                >
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <AboutArticleCard
                            image="/assets/dummmy/4da057811344b5b24e0cebd05a110367acce6d17.jpg"
                            title="Branch Events, Expos and Tradeshows"
                            description="Held at various locations across UK&I, ADI branch events and the ADI Expo Series are an excellent opportunity to demo new products, learn about new supplier offerings and savings, network and much more."
                            buttonLabel="FIND AN EVENT"
                            additionnal={false}
                        />
                        <AboutArticleCard
                            image="/assets/dummmy/76d31484b6e24d55dbd51757391aac276e45a069.jpg"
                            title="STS Academy"
                            description="Improve your knowledge in access control, IP video and more. These online trainings are direct from ADI's extensive supplier partner network and can be done all from the comfort of your home or office."
                            buttonLabel="GET STARTED"
                            additionnal={false}
                        />
                        <AboutArticleCard
                            image="/assets/dummmy/e627bae0f4f8fee6ca10ca03182e4947644236a2.jpg"
                            title="Webinars"
                            description="We offer free webinars presented by experts at ADI and leading suppliers on the industry’s top trends, technologies and challenges. Sign up for an upcoming webinar or watch a replay of a past event."
                            buttonLabel="SIGN UP"
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
