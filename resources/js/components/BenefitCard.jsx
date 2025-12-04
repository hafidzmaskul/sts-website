import React from 'react';

export default function BenefitCard({ image, text }) {


    return (
        <div className="bg-[#0079C2] rounded-xl text-[#CEEDFF] p-4 m-2">
            <img src={image} alt="" className='mb-2' />
            <p className='font-inter font-medium text-base'>{text}</p>
        </div>

    );
}

