import React from 'react';

export default function AboutStatsCard({ title, image, text }) {


    return (
        <div className="bg-[#0079C2] rounded-xl text-[#CEEDFF] p-4 m-2">
            <img src={image} alt="" className='mb-2' />
            <h1 className='font-inter font-extrabold mb-2 text-3xl md:text-4xl'>{title}</h1>
            <p className='font-inter font-medium text-base'>{text}</p>
        </div>

    );
}

