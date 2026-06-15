import React, { useState, useRef } from 'react';
import axios from 'axios';
import { usePage } from '@inertiajs/react';
import { formatPrice } from '../helpers/currency';

// Fungsi untuk menentukan warna badge berdasarkan nilai badge
const getBadgeBgColor = (badge) => {
    if (typeof badge === 'string') {
        if (badge.toLowerCase() === 'new') {
            return '#01AD5A';
        } else if (badge.toLowerCase() === 'sales') {
            return '#F5813F';
        }
    }
    return '#0F172A'; // default
};

export default function ProductCard({
    title,
    price,
    image,
    badge = 'New',
    id,
    slug,
    initialLiked = false,
}) {
    const { logged, is_guest: isGuest } = usePage().props;
    const badgeBgColor = getBadgeBgColor(badge);
    const imageRef = useRef(null);
    const [isAddingToCart, setIsAddingToCart] = useState(false);
    const [liked, setLiked] = useState(initialLiked);
    const [isTogglingLike, setIsTogglingLike] = useState(false);

    // Cart helpers: animation and add-to-cart
    const animateAddToCart = () => {
        const img = imageRef.current;
        if (!img) return;
        const cartEl = document.querySelector('a[href="/cart"]');
        const imgRect = img.getBoundingClientRect();
        const cartRect = cartEl ? cartEl.getBoundingClientRect() : { left: window.innerWidth - 40, top: 20, width: 20, height: 20 };
        const clone = img.cloneNode();
        clone.style.position = 'fixed';
        clone.style.left = `${imgRect.left}px`;
        clone.style.top = `${imgRect.top}px`;
        clone.style.width = `${imgRect.width}px`;
        clone.style.height = `${imgRect.height}px`;
        clone.style.zIndex = 9999;
        clone.style.transition = 'transform 700ms ease-in-out, opacity 700ms ease-in-out';
        document.body.appendChild(clone);
        requestAnimationFrame(() => {
            const translateX = cartRect.left + cartRect.width / 2 - (imgRect.left + imgRect.width / 2);
            const translateY = cartRect.top + cartRect.height / 2 - (imgRect.top + imgRect.height / 2);
            clone.style.transform = `translate(${translateX}px, ${translateY}px) scale(0.15)`;
            clone.style.opacity = '0.6';
        });
        setTimeout(() => clone.remove(), 800);
    };

    const refreshCart = async () => {
        try {
            const res = await axios.get('/web/cart');
            const items = res.data.data || res.data || [];
            const count = items.reduce((s, i) => s + (i.quantity || 0), 0);
            window.dispatchEvent(new CustomEvent('cart:changed', { detail: { count } }));
        } catch (e) {
            // ignore
        }
    };

    const handleAddToCart = async () => {
        if (isAddingToCart) return;
        setIsAddingToCart(true);
        animateAddToCart();
        try {
            await axios.post('/web/cart', { product_id: id, quantity: 1 });
            // Optional: emit a success event or toast if possible

            // Handle guest first-time refresh
            if (isGuest) {
                const hasAddedBefore = localStorage.getItem('guest_has_added_to_cart');
                if (!hasAddedBefore) {
                    localStorage.setItem('guest_has_added_to_cart', 'true');
                    window.location.reload();
                    return;
                }
            }

            await refreshCart();
        } catch (error) {
            console.error('Add to cart failed', error);
            // Optional: emit error event
        } finally {
            setIsAddingToCart(false);
        }
    };

    const handleToggleLike = async () => {
        if (!id || isTogglingLike || !logged) {
            return;
        }

        setIsTogglingLike(true);

        try {
            if (!liked) {
                await axios.post('/web/products/like', { product_id: id });
                setLiked(true);
                window.dispatchEvent(
                    new CustomEvent('liked:changed', {
                        detail: { productId: id, liked: true },
                    }),
                );
            } else {
                await axios.post('/web/products/unlike', { product_id: id });
                setLiked(false);
                window.dispatchEvent(
                    new CustomEvent('liked:changed', {
                        detail: { productId: id, liked: false },
                    }),
                );
            }
        } catch (error) {
            if (error?.response?.status === 401) {
                window.location.href = '/login-page';
            }

            console.error('Toggle like failed', error);
        } finally {
            setIsTogglingLike(false);
        }
    };

    return (
        <article className="group relative h-full overflow-hidden rounded-3xl bg-white px-5  pb-6 pt-5 transition-transform duration-500 hover:-translate-y-2">
            <div
                className="absolute left-5 top-5 z-10 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white"
                style={{ backgroundColor: badgeBgColor }}
            >
                {badge}
            </div>

            {/* like */}
            {logged && (
                <button
                    type="button"
                    onClick={handleToggleLike}
                    disabled={isTogglingLike}
                    className={`absolute right-5 top-5 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full transition-colors duration-300 group ${isTogglingLike ? 'opacity-60 cursor-not-allowed' : ''
                        }`}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width={24}
                        height={24}
                        viewBox="0 0 24 24"
                        className={`block ${liked ? 'hidden' : 'group-hover:hidden'}`}
                    >
                        <path
                            fill="none"
                            stroke="#0079C2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M19.5 12.572L12 20l-7.5-7.428A5 5 0 1 1 12 6.006a5 5 0 1 1 7.5 6.572"
                        />
                    </svg>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width={24}
                        height={24}
                        viewBox="0 0 24 24"
                        className={liked ? 'block' : 'hidden group-hover:block'}
                    >
                        <path
                            fill="#0079C2"
                            d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037.033l.034-.03a6 6 0 0 1 4.733-1.44l.246.036a6 6 0 0 1 3.364 10.008l-.18.185l-.048.041l-7.45 7.379a1 1 0 0 1-1.313.082l-.094-.082l-7.493-7.422A6 6 0 0 1 6.979 3.074"
                        />
                    </svg>
                </button>
            )}
            <a href={`/products/${slug}`}>
                <div className="relative overflow-hidden rounded-[22px] ">
                    <img
                        ref={imageRef}
                        src={image}
                        alt={title}
                        className="aspect-[4/5] h-full w-full object-contain transition duration-700 ease-out group-hover:scale-105"
                        loading="lazy"
                    />
                </div>
            </a>


            <div className="mt-5 flex items-start justify-between gap-3">

                <a href={`/products/${slug}`}>
                    <div className="">
                        <p className="text-base font-normal ">{title}</p>
                        <p className="text-base font-bold ">{formatPrice(price)}</p>
                    </div>
                </a>
                {/* Cart */}
                <button
                    type="button"
                    onClick={handleAddToCart}
                    disabled={isAddingToCart}
                    className={`inline-flex items-center gap-2 rounded-lg bg-[#EEF2F7] px-4 py-4 text-sm font-semibold text-[#1E1E1E] transition-colors duration-300 hover:bg-[#0079C2] hover:text-white ${isAddingToCart ? 'opacity-50 cursor-not-allowed' : ''}`}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width={18}
                        height={18}
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="1.6"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                    >
                        <path d="M17 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-8 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4m-1.5-7H19l-1 5H9" />
                        <path d="m2.5 2.5l2 1L6 14h10.5" />
                        <path d="M5 6h16l-2 7H9" />
                    </svg>
                </button>
            </div>
        </article>
    );
}
