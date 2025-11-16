import React from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';

export default function ScaffoldBase({ title, children }) {
  return (
    <div className="min-h-dvh bg-[#302F2F] flex flex-col">
      <Header />
      <main className="flex-1 container mx-auto px-6 py-12 text-white">
        <h1 className="text-3xl font-bold mb-6">{title}</h1>
        {children}
      </main>
      <Footer />
    </div>
  );
}

