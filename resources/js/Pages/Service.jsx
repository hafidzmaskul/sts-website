import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import ServiceArticleCard from '../components/ServiceArticleCard';

export default function Service({ services = [] }) {
    console.log(services)
    return (
        <div className="min-h-screen flex flex-col">
            <Head title="Services" />
            <Header />
            <HeroSection
                bgUrl="/assets/bg-about.jpg"
                title="Support & Service"
                text="Our comprehensive suite ofx value-added services is designed to make installations easier"
                textColor="text-white"
                textSize='text-5xl md:text-7xl'
            />
            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10">
                <div className="max-w-4xl mx-auto">
                    <p className="font-inter text-xl text-center font-medium mb-6 text-black py-20">
                        Our services and expertise enable you to save time and money. Our knowledgeable pre-application team members have years of industry experience, and we have experts for every category and application. We work to solve your product, design and programming challenges at every interaction, alleviating your workload and making installations easier and more efficient.
                    </p>
                </div>
                <section id='service' className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {services.map((service) => (
                        <ServiceArticleCard
                            key={service.id}
                            title={service.title}
                            text={service.content}
                            image={service.image_path}
                            button="Learn More"
                        />
                    ))}
                </section>
            </main>
            <Footer />
        </div>
    );
}
