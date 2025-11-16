import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';

export default function Header() {
    const { url } = usePage();
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    // Handle both relative and absolute URLs by extracting path only
    function getPathOnly(url) {
        try {
            // This will parse both relative and absolute URLs correctly
            return new URL(url, 'http://dummy.base').pathname;
        } catch {
            // Fallback (should not happen in normal cases)
            return url;
        }
    }

    const currentPath = getPathOnly(url);

    const isActive = (path) => {
        return currentPath === path || (currentPath.startsWith(path + '/') && path !== '/');
    };

    // Navigation items configuration
    const navItems = [
        { name: 'Services', href: '/services' },
        { name: 'Products', href: '/products' },
        { name: 'News', href: '/news' },
    ];

    const rightNavItems = [
        { name: 'About Us', href: '/about-us' },
        { name: 'Contact Us', href: '/contact-us' },
    ];

    // Generate breadcrumb items based on current path
    const generateBreadcrumbs = () => {
        const pathSegments = currentPath.split('/').filter(segment => segment !== '');
        const breadcrumbs = [{ name: 'Home', href: '/' }];

        let buildingPath = '';
        pathSegments.forEach(segment => {
            buildingPath += `/${segment}`;
            // Find matching nav item name
            const navItem = [...navItems, ...rightNavItems].find(item => item.href === buildingPath);
            if (navItem) {
                breadcrumbs.push({ name: navItem.name, href: buildingPath });
            } else {
                // Format segment name (capitalize and replace hyphens with spaces)
                const formattedName = segment.charAt(0).toUpperCase() + segment.slice(1).replace(/-/g, ' ');
                breadcrumbs.push({ name: formattedName, href: buildingPath });
            }
        });

        return breadcrumbs;
    };

    // Common link styles
    const linkStyle = {
        fontFamily: 'Inter, sans-serif',
        fontWeight: 700,
        textTransform: 'uppercase',
        fontSize: '16px',
        lineHeight: '14.4px',
        letterSpacing: '0.5px',
        verticalAlign: 'middle',
    };

    // Mobile link styles
    const mobileLinkStyle = {
        fontFamily: 'Inter, sans-serif',
        fontWeight: 600,
        fontSize: '14px',
        lineHeight: '18px',
        letterSpacing: '0.25px',
    };

    return (
        <>
            {/* Desktop Header (hidden on screens 850px and below) */}
            <header className="hidden lg:block mt-10 mx-auto container px-10 bg-[#6A6969] rounded-[32px]">
                <div className="mx-auto h-20 flex items-center justify-between rounded-[32px] relative">
                    {/* Navigation Menu Left */}
                    <nav className="flex-1 flex items-center gap-6">
                        {navItems.map((item) => (
                            <a
                                key={item.href}
                                href={item.href}
                                className={
                                    isActive(item.href)
                                        ? 'text-[#FFED2E]'
                                        : 'text-white hover:underline'
                                }
                                style={linkStyle}
                            >
                                {item.name}
                            </a>
                        ))}
                    </nav>

                    {/* Center Title */}
                    <a
                        href="/"
                        className="font-semibold text-white text-2xl tracking-wider text-center"
                        style={{
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 700,
                            fontStyle: 'bold',
                            textTransform: 'uppercase',
                            letterSpacing: '0.5px',
                            lineHeight: '36px',
                        }}
                    >
                        <img src="/assets/logo.svg" alt="Logo" className="h-12" />
                    </a>

                    {/* Navigation Menu Right */}
                    <nav className="flex-1 flex items-center justify-end gap-6">
                        {rightNavItems.map((item) => (
                            <a
                                key={item.href}
                                href={item.href}
                                className={
                                    isActive(item.href)
                                        ? 'text-[#FFED2E]'
                                        : 'text-white hover:underline'
                                }
                                style={linkStyle}
                            >
                                {item.name}
                            </a>
                        ))}
                    </nav>
                </div>
            </header>

            {/* Mobile Header (visible on screens 850px and below) */}
            <header className="lg:hidden">
                {/* Breadcrumb Navigation */}
                <div className="bg-[#6A6969] py-3 px-4">
                    <nav className="flex items-center text-sm">
                        {generateBreadcrumbs().map((item, index) => (
                            <React.Fragment key={item.href}>
                                {index > 0 && (
                                    <span className="mx-2 text-white">/</span>
                                )}
                                <a
                                    href={item.href}
                                    className={
                                        index === generateBreadcrumbs().length - 1
                                            ? 'text-[#FFED2E] font-medium'
                                            : 'text-white hover:underline'
                                    }
                                    style={mobileLinkStyle}
                                >
                                    {item.name}
                                </a>
                            </React.Fragment>
                        ))}
                    </nav>
                </div>

                {/* Mobile Header with Logo and Menu Button */}
                <div className="bg-[#6A6969] py-3 px-4 flex items-center justify-between">
                    <a
                        href="/"
                        className="flex items-center"
                    >
                        <img src="/assets/logo.svg" alt="Logo" className="h-10" />
                    </a>

                    <button
                        onClick={() => setIsMenuOpen(!isMenuOpen)}
                        className="text-white p-2 rounded-md hover:bg-gray-700 focus:outline-none"
                    >
                        <svg
                            className="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            {isMenuOpen ? (
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            ) : (
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            )}
                        </svg>
                    </button>
                </div>

                {/* Mobile Menu (collapsible) */}
                {isMenuOpen && (
                    <div className="bg-[#6A6969] py-2 px-4">
                        <nav className="flex flex-col space-y-2">
                            {[...navItems, ...rightNavItems].map((item) => (
                                <a
                                    key={item.href}
                                    href={item.href}
                                    className={
                                        isActive(item.href)
                                            ? 'text-[#FFED2E] py-2 border-b border-gray-600'
                                            : 'text-white py-2 border-b border-gray-600 hover:underline'
                                    }
                                    style={mobileLinkStyle}
                                >
                                    {item.name}
                                </a>
                            ))}
                        </nav>
                    </div>
                )}
            </header>
        </>
    );
}
