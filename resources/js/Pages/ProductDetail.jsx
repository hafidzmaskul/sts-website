import React, { useEffect } from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';
import ProductCard from '../components/ProductCard';
import ExploreButton from '../components/ExploreButton';

export default function ProductDetail({ product, products = [] }) {
    const data = product;
    const imageUrl = data?.image_url ?? 'https://placehold.co/600x400?text=No+Image';

  return (
    <>
    <Head title={data?.name ? `${data.name} - Product` : 'Product Detail'} />
    <div className="bg-[#302F2F]" data-aos="fade-in">

            <div
                className="relative rounded-b-xl md:rounded-b-[200px] overflow-hidden h-[50vh] mx-auto "
                style={{
                    background: 'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                }}
            >
                <Header />
                <div className="flex container mx-auto flex-col items-center  w-full px-10 md:hidden">
                    {/* Left: Image */}
                    <div className="flex-1 flex justify-center items-center ">
                        <img
                            src={imageUrl}
                            alt={data.name}
                            className="w-48 drop-shadow-lg "
                            style={{ objectFit: 'contain' }}
                        />
                    </div>
                    {/* Right: Price and Button */}
                    <div className="flex-1 flex flex-col items-start justify-center mt-8 mb-10">
                        <div className="text-2xl font-bold text-white">
                            £{Number(data.price).toFixed(2)}
                        </div>
                        <a
                            href="#"
                            className="text-[#302F2F] px-10 font-inter py-3 rounded-xl font-semibold text-xs transition"
                            style={{
                                background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)',
                            }}
                        >
                            Buy Now
                        </a>
                    </div>
                </div>
            </div>
            <div className="hidden md:flex container mx-auto flex-col md:flex-row items-center justify-between absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 md:static md:translate-x-0 md:translate-y-0 z-10 w-full px-10 md:px-20">
                {/* Left: Image */}
                <div className="flex-1 flex justify-center items-center relative md:-mt-60">
                    <img
                        src={imageUrl}
                        alt={data.name}
                        className="w-48 md:w-200 drop-shadow-lg relative md:-left-10"
                        style={{ objectFit: 'contain' }}
                    />
                </div>
                {/* Right: Price and Button */}
                <div className="flex-1 flex flex-col items-start md:items-start justify-center mt-8 md:mt-0 md:ml-16">
                    <div className="text-3xl md:text-5xl font-bold text-white mb-4">
                        £{Number(data.price).toFixed(2)}
                    </div>
                    <a
                        href={`/payment/${product.slug}`}
                        className="text-[#302F2F] px-20 font-inter py-5 rounded-xl font-semibold text-sm transition"
                        style={{
                            background: 'linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.6) 100%)',
                        }}
                    >
                        Buy Now
                    </a>
                </div>
            </div>

            <img
                    src="/assets/gradient-product-detail.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 top-50 left-0 w-1/4 max-w-lg opacity-60"
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />

<img
                    src="/assets/gradient-product-detail2.svg"
                    alt=""
                    className="pointer-events-none select-none absolute -z-10 bottom-200 right-0 w-1/4 max-w-lg opacity-60"
                    style={{
                        // Example: appear only in top right, not covering full area
                        objectFit: "contain",
                    }}
                    aria-hidden="true"
                />

            <main className="flex-1 container mx-auto px-10 md:px-20  py-20" data-aos="fade-up">
                <section className='py-20' data-aos="fade-up">

                    <h1 className='font-montserrat font-extrabold text-2xl md:text-5xl mb-10 text-white uppercase text-center'>{data.name}</h1>
                    <p className='font-normal font-roboto text-sm md:font-base text-white text-center'>
                        {data.description}
                    </p>
                    <div className="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div
                            className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col md:flex-row items-stretch"
                            style={{
                                background:
                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                            }}
                        >

                            <div
                                className="flex-1 px-6 rounded-2xl  pt-6 pb-4 flex flex-col"
                                style={{
                                    boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                    background:
                                        'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                }}
                            >
                                <h3 className="text-lg md:text-1xl font-inter text-white font-bold mb-5 uppercase">
                                    Handbooks also outline best practice, including:
                                </h3>
                                <ul className="text-left space-y-3 text-white">
                                    {data.benefits && data.benefits.map((benefit, idx) => (
                                        <li className="flex items-start" key={idx}>
                                            <span
                                                className="inline-block w-3 h-3 mt-2 rounded-full mr-3"
                                                style={{ background: '#FFED2E' }}
                                            ></span>
                                            {benefit}
                                        </li>
                                    ))}
                                </ul>

                            </div>
                        </div>

                        <div
                            className="p-5 backdrop-blur-[70px] rounded-2xl shadow-[0px_1.2px_29.92px_0px_rgba(69,42,124,0.10)] overflow-hidden flex flex-col md:flex-row items-stretch"
                            style={{
                                background:
                                    'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                            }}
                        >

                            <div
                                className="flex-1 px-6 rounded-2xl  pt-6 pb-4 flex flex-col"
                                style={{
                                    boxShadow: '0px 1.2px 29.92px 0px #452A7C1A',
                                    background:
                                        'linear-gradient(86.16deg, rgba(255, 255, 255, 0.2) 11.14%, rgba(255, 255, 255, 0.035) 113.29%)',
                                }}
                            >
                                <h3 className="text-lg md:text-1xl font-inter text-white font-bold mb-5 uppercase">
                                    We also ensure that suitable HR Policies are put in place to protect your business, this includes guidance on:
                                </h3>
                                <ul className="text-left space-y-3 text-white">
                                    {data.policies && data.policies.map((policy, idx) => (
                                        <li className="flex items-start" key={idx}>
                                            <span
                                                className="inline-block w-3 h-3 mt-2 rounded-full mr-3"
                                                style={{ background: '#FFED2E' }}
                                            ></span>
                                            {policy}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>

                    </div>
                    <p className='font-normal font-roboto text-sm md:font-base mt-10 text-white text-center'>
                        {data.note}
                    </p>
                </section>

                <section id="product-showcase">
                    <H1 text="PRODUCT SHOWCASE" color="white" />

                    <div className="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {products.map((relatedProduct, index) => (
                            <ProductCard
                                key={relatedProduct.id ?? index}
                                index={index + 1}
                                slug={relatedProduct.slug}
                                name={relatedProduct.name}
                                content={relatedProduct.content}
                                image={relatedProduct.image_url}
                                isLoading={false}
                            />
                        ))}
                    </div>
                    <div className="flex justify-center mt-10">

                        <ExploreButton href="/products">
                            EXPLORE OTHER PRODUCTS
                        </ExploreButton>
                    </div>
                </section>
            </main>

            <Footer />
        </div>
        </>
    );
}
