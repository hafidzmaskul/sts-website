import React from 'react';
import { Head } from '@inertiajs/react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function AboutUs({ teamMembers = [] }) {
    return (
        <div className="min-h-screen flex flex-col bg-[#F3F3F3]">
            <Head title="About Us" />
            <Header />

            <main className="flex-1 container mx-auto px-6 md:px-10 lg:px-20 py-10 space-y-8">
                <section>
                    <h1 className="font-bebas-neue text-4xl md:text-5xl mb-4 text-[#232323]">
                        About Us
                    </h1>
                    <p className="text-gray-700 max-w-2xl">
                        Halaman ini menampilkan informasi singkat mengenai perusahaan Anda.
                        Silakan sesuaikan konten ini nanti sesuai kebutuhan bisnis.
                    </p>
                </section>

                {teamMembers.length > 0 && (
                    <section>
                        <h2 className="text-xl font-semibold text-[#232323] mb-4">
                            Tim Kami
                        </h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {teamMembers.map((member) => (
                                <div
                                    key={member.id}
                                    className="bg-white rounded-xl shadow-sm p-4"
                                >
                                    <p className="text-base font-semibold text-[#232323]">
                                        {member.name}
                                    </p>
                                    {member.position && (
                                        <p className="text-sm text-gray-600">
                                            {member.position}
                                        </p>
                                    )}
                                </div>
                            ))}
                        </div>
                    </section>
                )}
            </main>

            <Footer />
        </div>
    );
}

