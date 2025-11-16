
import React from 'react';

/**
 * H1 Component
 *
 * Props:
 * - text: The header text to display.
 * - color: (optional) "white" or "black" (default: "black").
 * - className: (optional) Additional classes for the h1.
 *
 * Example usage:
 *   <H1 text="Welcome!" color="white" />
 */
const COLOR_CLASSES = {
  black: 'text-[#302F2F]',
  white: 'text-white',
};

export default function H1({ text, color = 'black', className = '' }) {
  return (
    <h1
      className={`
        capitalize 
        text-xl md:text-3xl lg:text-5xl
        leading-[100%]
        tracking-[0.00em]
        font-medium
        text-center
        align-middle
        font-akzidenz
        ${COLOR_CLASSES[color] || COLOR_CLASSES.black}
        ${className}
      `}

    >
      {text}
    </h1>
  );
}

