import React from 'react';
import Header from '../landing/Header';
import Footer from '../landing/Footer';
import H1 from '../components/H1';

export default function ContactUs() {
  return (
    <div className="">
            <div className="flex flex-col rounded-b-xl md:rounded-b-[200px]" style={{ backgroundImage: 'url(/assets/bg.png)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <Header />
                <div className="w-full flex flex-col items-center text-center mt-20">
                    <H1 text={'Our Services'} color='black'  />
                    <p className='text-roboto text-xs md:text-base font-normal mb-10 md:mb-20 mt-10 max-w-md'>
                        Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu
                        aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl  malesuada lacinia integer nunc posuere.
                    </p>
                </div>
            </div>

            <main className="flex-1 container mx-auto px-6 py-12 text-white">
                <h1 className="text-3xl font-bold mb-6"></h1>
            </main>
            <Footer />
        </div>
  );
}
