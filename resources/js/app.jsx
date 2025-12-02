import React, { useEffect } from 'react';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import AOS from 'aos';
import 'aos/dist/aos.css';

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
    return pages[`./Pages/${name}.jsx`];
  },
  setup({ el, App, props }) {
    const Root = () => {
      useEffect(() => {
        AOS.init({
          duration: 700,
          easing: 'ease-out-cubic',
          once: true,
          offset: 80,
        });

        AOS.refresh();
      }, []);

      return <App {...props} />;
    };

    createRoot(el).render(<Root />);
  },
});


// font-inter
// font-inter-tight
// font-dm-sans
// font-bebas-neue
// font-poppins
// font-nunito-sans
