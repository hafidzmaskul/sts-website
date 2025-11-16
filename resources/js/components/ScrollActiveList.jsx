import React, { useState, useEffect, useRef } from 'react';

// Data untuk list, bisa disesuaikan
const features = [
    { text: 'DISCIPLINARY' },
    { text: 'RANGE OF BUSINESS' },
    { text: 'EXPERIENCE EMPLOYEE' },
    { text: 'DEDICATED EXPERTS' },
    { text: '24/7 HELPLINE' },
];

const ScrollActiveList = () => {
    // State untuk melacak index item yang sedang aktif
    const [activeIndex, setActiveIndex] = useState(0);

    // Ref untuk mendapatkan referensi ke elemen DOM
    const listRef = useRef(null); // Ref untuk <ul>
    const containerRef = useRef(null); // Ref untuk container utama

    useEffect(() => {
        const listItems = listRef.current?.querySelectorAll('li');
        const container = containerRef.current;

        // Fungsi yang dijalankan saat halaman di-scroll
        const handleScroll = () => {
            if (!container || !listItems) return;

            // Dapatkan posisi container relatif terhadap viewport
            const containerTop = container.getBoundingClientRect().top;
            const containerHeight = container.offsetHeight;
            const windowHeight = window.innerHeight;

            // Tentukan zona scroll di mana efek akan aktif
            // Zona dimulai saat bagian atas container mencapai tengah layar
            const scrollStart = windowHeight / 4;
            // Zona berakhir saat bagian bawah container mencapai tengah layar
            const scrollEnd = containerTop + containerHeight - (windowHeight / 2);

            // Hitung progress scroll (nilai dari 0 hingga 1)
            let progress = (  containerTop - scrollStart) / (  containerTop - scrollEnd);
            progress = Math.max(0, Math.min(1, progress)); // Pastikan nilai tetap antara 0 dan 1

            // Tentukan index item yang aktif berdasarkan progress
            const newIndex = Math.round(progress * (listItems.length - 1));

            // Update state hanya jika index berubah
            setActiveIndex(newIndex);
        };

        // Intersection Observer untuk performa yang lebih baik
        // Logika scroll hanya akan berjalan saat komponen terlihat di layar
        const observerOptions = {
            root: null, // relative to the viewport
            rootMargin: '0px',
            threshold: 0.1 // trigger saat 10% container terlihat
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Komponen terlihat, tambahkan event listener scroll
                    window.addEventListener('scroll', handleScroll);
                    // Jalankan sekali saat pertama kali terlihat untuk men-set state awal
                    handleScroll();
                } else {
                    // Komponen tidak terlihat, hapus event listener
                    window.removeEventListener('scroll', handleScroll);
                }
            });
        }, observerOptions);

        if (container) {
            observer.observe(container);
        }

        // Fungsi cleanup untuk mencegah memory leak
        return () => {
            if (container) {
                observer.unobserve(container);
            }
            window.removeEventListener('scroll', handleScroll);
        };
    }, []); // Dependency array kosong berarti efek ini hanya berjalan sekali saat komponen dimuat

    return (
        // Ref untuk container utama
        <div className="md:w-1/2 w-full flex flex-col gap-2" ref={containerRef}>
            {/* Ref untuk list item */}
            <ul ref={listRef} className="list-inside text-white text-base md:text-lg font-inter list-none">
                {features.map((feature, index) => (
                    <li
                        key={index}
                        // Class dinamis berdasarkan activeIndex
                        // `transition-all duration-500 ease-out` untuk animasi yang halus
                        className={`font-inter font-bold transition-all duration-500 ease-out ${
                            index === activeIndex
                                ? 'mx-2 text-[#FFED2E]' // Gaya untuk item aktif
                                : 'text-[#FFED2E4F]' // Gaya untuk item non-aktif
                        }`}
                    >
                        {feature.text}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default ScrollActiveList;
