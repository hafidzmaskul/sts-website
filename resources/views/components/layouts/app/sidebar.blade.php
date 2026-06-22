<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @php
        // Determine if current user is admin or not
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('admin');

        $sidebarBg = '#0078c2';
        $sidebarBorder = '#00619e';
        $navitemActiveColor = '#0078c2';
    @endphp
    <style>
        /* Custom sidebar background color */
        .custom-sidebar-bg {
            background-color:
                {{ $sidebarBg }}
                !important;
            border-right: 1px solid
                {{ $sidebarBorder }}
                !important;
        }

        /* Custom nav item color */
        .custom-navitem {
            color: #fff !important;
            justify-content: flex-end !important;
            display: flex !important;
        }

        /* Custom active state for navlist.item */
        .custom-navitem-active {
            background-color: #fff !important;
            color:
                {{ $navitemActiveColor }}
                !important;
            justify-content: flex-end !important;
            display: flex !important;
        }

        .custom-navitem:not(.custom-navitem-active):hover {
            background: rgba(255, 255, 255, 0.07);
        }

        /* Optional for icon coloring, depends on icon rendering */
        .custom-navitem .flux-icon {
            color: #fff !important;
        }

        .custom-navitem-active .flux-icon {
            color:
                {{ $navitemActiveColor }}
                !important;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F0F2F3]">
    <x-alert-banner />

    <flux:sidebar sticky stashable class="custom-sidebar-bg">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex text-white items-center space-x-2 rtl:space-x-reverse"
            wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">

            <!-- Platform Group -->
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate :class="request()->routeIs('dashboard') ? 'custom-navitem-active' : 'custom-navitem'">
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                @if(auth()->user()->hasAnyRole(['trade account', 'credit facilities account']))
                    @if(auth()->user()->customer?->account_level !== 'staff')
                        <flux:navlist.item icon="users" :href="route('dashboard.users.index')"
                            :current="request()->routeIs('dashboard.users.*')" wire:navigate
                            :class="request()->routeIs('dashboard.users.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Manage Users') }}
                        </flux:navlist.item>
                    @endif

                    <flux:navlist.item icon="banknotes" :href="route('dashboard.transactions.index')"
                        :current="request()->routeIs('dashboard.transactions.*')" wire:navigate
                        :class="request()->routeIs('dashboard.transactions.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Transactions') }}
                    </flux:navlist.item>

                    @if(auth()->user()->hasRole('credit facilities account'))
                        <flux:navlist.item icon="scale" :href="route('dashboard.credit-limits.index')"
                            :current="request()->routeIs('dashboard.credit-limits.*')" wire:navigate
                            :class="request()->routeIs('dashboard.credit-limits.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Credit Limit') }}
                        </flux:navlist.item>
                    @endif

                    <flux:navlist.item icon="building-office-2" :href="route('dashboard.company.show')"
                        :current="request()->routeIs('dashboard.company.*')" wire:navigate
                        :class="request()->routeIs('dashboard.company.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Company') }}
                    </flux:navlist.item>
                @endif

                @can('roles.view')
                    <flux:navlist.item icon="users" :href="route('admin.roles.index')"
                        :current="request()->routeIs('admin.roles.*')" wire:navigate
                        :class="request()->routeIs('admin.roles.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Role Management') }}
                    </flux:navlist.item>
                @endcan
                @can('users.view')
                    <flux:navlist.item icon="user" :href="route('admin.users.index')"
                        :current="request()->routeIs('admin.users.*')" wire:navigate
                        :class="request()->routeIs('admin.users.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('User Management') }}
                    </flux:navlist.item>
                @endcan
                @can('customers.view')
                    <flux:navlist.item icon="users" :href="route('admin.customers.index')"
                        :current="request()->routeIs('admin.customers.*')" wire:navigate
                        :class="request()->routeIs('admin.customers.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Customers') }}
                    </flux:navlist.item>
                @endcan

                @can('customers.view')
                    <flux:navlist.item icon="building-office" :href="route('admin.companies.index')"
                        :current="request()->routeIs('admin.companies.*')" wire:navigate
                        :class="request()->routeIs('admin.companies.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Companies') }}
                    </flux:navlist.item>
                @endcan

                @can('settings.view')
                    <flux:navlist.item icon="cog" :href="route('admin.settings.general')"
                        :current="request()->routeIs('admin.settings.general')" wire:navigate
                        :class="request()->routeIs('admin.settings.general') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('General Settings') }}
                    </flux:navlist.item>
                @endcan
            </flux:navlist.group>

            <!-- Catalog Group -->
            @canany(['services.view', 'products.view', 'product-categories.view'])
                <flux:navlist.group :heading="__('Catalog')" class="grid">
                    @can('products.view')
                        <flux:navlist.item icon="shopping-bag" :href="route('admin.products.index')"
                            :current="request()->routeIs('admin.products.*')" wire:navigate
                            :class="request()->routeIs('admin.products.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Products') }}
                        </flux:navlist.item>
                    @endcan

                    @can('product-categories.view')
                        <flux:navlist.item icon="tag" :href="route('admin.product-categories.index')"
                            :current="request()->routeIs('admin.product-categories.*')" wire:navigate
                            :class="request()->routeIs('admin.product-categories.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Categories') }}
                        </flux:navlist.item>
                    @endcan

                    @can('brands.view')
                        <flux:navlist.item icon="tag" :href="route('admin.brands.index')"
                            :current="request()->routeIs('admin.brands.*')" wire:navigate
                            :class="request()->routeIs('admin.brands.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Brands') }}
                        </flux:navlist.item>
                    @endcan

                    @can('pricing-formulas.view')
                        <flux:navlist.item icon="calculator" :href="route('admin.pricing-formulas.index')"
                            :current="request()->routeIs('admin.pricing-formulas.*')" wire:navigate
                            :class="request()->routeIs('admin.pricing-formulas.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Pricing Formulas') }}
                        </flux:navlist.item>
                    @endcan
                </flux:navlist.group>
            @endcanany

            <!-- Sales Group -->
            @canany(['quotes.view', 'transactions.view'])
                <flux:navlist.group :heading="__('Sales')" class="grid">
                    @can('quotes.view')
                        <flux:navlist.item icon="currency-dollar" :href="route('admin.quotes.index')"
                            :current="request()->routeIs('admin.quotes.*')" wire:navigate
                            :class="request()->routeIs('admin.quotes.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Quote Submission') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document" :href="route('admin.quote-builders.index')"
                            :current="request()->routeIs('admin.quote-builders.*')" wire:navigate
                            :class="request()->routeIs('admin.quote-builders.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Quote Builders') }}
                        </flux:navlist.item>
                    @endcan
                    {{-- @can('transactions.view') --}}
                    <flux:navlist.item icon="banknotes" :href="route('admin.transactions.index')"
                        :current="request()->routeIs('admin.transactions.*')" wire:navigate
                        :class="request()->routeIs('admin.transactions.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Transactions') }}
                    </flux:navlist.item>
                    {{-- @endcan --}}
                    <flux:navlist.item icon="ticket" :href="route('admin.coupons.index')"
                        :current="request()->routeIs('admin.coupons.*')" wire:navigate
                        :class="request()->routeIs('admin.coupons.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Coupons') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('admin.product-requests.index')"
                        :current="request()->routeIs('admin.product-requests.*')" wire:navigate
                        :class="request()->routeIs('admin.product-requests.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Product Requests') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="arrow-path-rounded-square" :href="route('admin.rma-requests.index')"
                        :current="request()->routeIs('admin.rma-requests.*')" wire:navigate
                        :class="request()->routeIs('admin.rma-requests.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('RMA Requests') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="shopping-cart" :href="route('admin.abandoned-carts.index')"
                        :current="request()->routeIs('admin.abandoned-carts.*')" wire:navigate
                        :class="request()->routeIs('admin.abandoned-carts.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Abandoned Carts') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            @endcanany

            @canany(['news.view', 'news-categories.view', 'newsletter-subscriptions.view'])
                <flux:navlist.group :heading="__('News')" class="grid">
                    @can('news-categories.view')
                        <flux:navlist.item icon="newspaper" :href="route('admin.news-categories.index')"
                            :current="request()->routeIs('admin.news-categories.*')" wire:navigate
                            :class="request()->routeIs('admin.news-categories.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Categories') }}
                        </flux:navlist.item>
                    @endcan

                    @can('news.view')
                        <flux:navlist.item icon="document-text" :href="route('admin.news.index')"
                            :current="request()->routeIs('admin.news.*')" wire:navigate
                            :class="request()->routeIs('admin.news.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Articles') }}
                        </flux:navlist.item>
                    @endcan

                    @can('newsletter-subscriptions.view')
                        <flux:navlist.item icon="envelope" :href="route('admin.newsletter.index')"
                            :current="request()->routeIs('admin.newsletter.*')" wire:navigate
                            :class="request()->routeIs('admin.newsletter.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Newsletter') }}
                        </flux:navlist.item>
                    @endcan
                </flux:navlist.group>
            @endcanany

            <!-- Content Group -->
            @can('banner.view')
                <flux:navlist.group :heading="__('Content')" class="grid">
                    <flux:navlist.item icon="photo" :href="route('admin.banners.index')"
                        :current="request()->routeIs('admin.banners.*')" wire:navigate
                        :class="request()->routeIs('admin.banners.*') ? 'custom-navitem-active' : 'custom-navitem'">
                        {{ __('Banners') }}
                    </flux:navlist.item>

                    @can('contact-submissions.view')
                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('admin.contact-submissions.index')"
                            :current="request()->routeIs('admin.contact-submissions.*')" wire:navigate
                            :class="request()->routeIs('admin.contact-submissions.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Messages') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chat-bubble-bottom-center-text" :href="route('admin.feedback.index')"
                            :current="request()->routeIs('admin.feedback.*')" wire:navigate
                            :class="request()->routeIs('admin.feedback.*') ? 'custom-navitem-active' : 'custom-navitem'">
                            {{ __('Feedbacks') }}
                        </flux:navlist.item>
                    @endcan


                </flux:navlist.group>
            @endcan

        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="/" class="custom-navitem">
                {{ __('Back To Homepage') }}
            </flux:navlist.item>

        </flux:navlist>

        <!-- User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile class="custom-navitem" :initials="auth()->user()->initials()" icon:trailing="chevrons-up-down"
                data-test="sidebar-menu-button" name="{{ auth()->user()->name }}" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile Header -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>