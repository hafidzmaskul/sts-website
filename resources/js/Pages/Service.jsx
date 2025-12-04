import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import HeroSection from '../components/HeroSection';
import ServiceArticleCard from '../components/ServiceArticleCard';

const ServiceData = [
    {
        title: 'Site Surveys',
        text: "Our fully accredited team of specialists will assist you on-site to understand your customer’s needs, assess site feasibility and help you achieve the best solution.",
        button: 'Learn More',
        image: 'assets/dummmy/67c57045b30ee9de5aab0faafc0b0dae7302c714.png'
    },
    {
        title: 'Product Demos',
        text: "Benefit from STS’s access to the widest range of brands and products. Let our fully accredited team of specialists conduct a product demonstration in a face-to-face or remote interactive environment.",
        button: 'Learn More',
        image: 'assets/dummmy/67c57045b30ee9de5aab0faafc0b0dae7302c714.png'
    },
    {
        title: 'System Design',
        text: "Our Technical Support team has expert multi-discipline knowledge and can provide a complete end-to-end service with the best system solution design along with full quotation support.",
        button: 'Learn More',
        image: 'assets/dummmy/67c57045b30ee9de5aab0faafc0b0dae7302c714.png'
    }
];

export default function Service({ services = [] }) {
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
                    {ServiceData.map((service, idx) => (
                        <ServiceArticleCard
                            key={idx}
                            title={service.title}
                            text={service.text}
                            image={service.image}
                            button={service.button}
                        />
                    ))}
                </section>
            </main>

            <Footer />
        </div>
    );
}

