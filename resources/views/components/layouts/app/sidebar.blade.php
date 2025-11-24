<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <x-alert-banner />

        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                
                <!-- Platform Group -->
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                    >{{ __('Dashboard') }}</flux:navlist.item>
                    @can('roles.view')
                        <flux:navlist.item
                            icon="users"
                            :href="route('admin.roles.index')"
                            :current="request()->routeIs('admin.roles.*')"
                            wire:navigate
                        >{{ __('Role Management') }}</flux:navlist.item>
                    @endcan
                    @can('users.view')
                        <flux:navlist.item
                            icon="user"
                            :href="route('admin.users.index')"
                            :current="request()->routeIs('admin.users.*')"
                            wire:navigate
                        >{{ __('User Management') }}</flux:navlist.item>
                    @endcan
                    @can('settings.view')
                        <flux:navlist.item
                            icon="cog"
                            :href="route('admin.settings.index')"
                            :current="request()->routeIs('admin.settings.*')"
                            wire:navigate
                        >{{ __('General Settings') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>

                <!-- Catalog Group -->
                @canany(['services.view', 'products.view'])
                <flux:navlist.group :heading="__('Catalog')" class="grid">
                    @can('services.view')
                        <flux:navlist.item
                            icon="briefcase"
                            :href="route('admin.services.index')"
                            :current="request()->routeIs('admin.services.*')"
                            wire:navigate
                        >{{ __('Services') }}</flux:navlist.item>
                    @endcan
                    @can('products.view')
                        <flux:navlist.item
                            icon="shopping-bag"
                            :href="route('admin.products.index')"
                            :current="request()->routeIs('admin.products.*')"
                            wire:navigate
                        >{{ __('Products') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endcanany

                <!-- Sales Group -->
                @can('transactions.view')
                <flux:navlist.group :heading="__('Sales')" class="grid">
                    <flux:navlist.item
                        icon="banknotes"
                        :href="route('admin.transactions.index')"
                        :current="request()->routeIs('admin.transactions.*')"
                        wire:navigate
                    >{{ __('Transactions') }}</flux:navlist.item>
                </flux:navlist.group>
                @endcan

                <!-- Content Group -->
                @canany(['testimonials.view', 'our-team.view', 'news-categories.view', 'news.view', 'careers.view'])
                <flux:navlist.group :heading="__('Content')" class="grid">
                    @can('news-categories.view')
                        <flux:navlist.item
                            icon="tag"
                            :href="route('admin.news-categories.index')"
                            :current="request()->routeIs('admin.news-categories.*')"
                            wire:navigate
                        >{{ __('News Categories') }}</flux:navlist.item>
                    @endcan
                    @can('news.view')
                        <flux:navlist.item
                            icon="newspaper"
                            :href="route('admin.news.index')"
                            :current="request()->routeIs('admin.news.*')"
                            wire:navigate
                        >{{ __('News') }}</flux:navlist.item>
                    @endcan
                    @can('careers.view')
                        <flux:navlist.item
                            icon="briefcase"
                            :href="route('admin.careers.index')"
                            :current="request()->routeIs('admin.careers.*')"
                            wire:navigate
                        >{{ __('Careers') }}</flux:navlist.item>
                    @endcan
                    @can('testimonials.view')
                        <flux:navlist.item
                            icon="chat-bubble-left-right"
                            :href="route('admin.testimonials.index')"
                            :current="request()->routeIs('admin.testimonials.*')"
                            wire:navigate
                        >{{ __('Testimonials') }}</flux:navlist.item>
                    @endcan
                    @can('our-team.view')
                        <flux:navlist.item
                            icon="user-group"
                            :href="route('admin.our-team.index')"
                            :current="request()->routeIs('admin.our-team.*')"
                            wire:navigate
                        >{{ __('Our Team') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endcanany

                <!-- Leads Group -->
                @canany(['newsletter-subscriptions.view', 'contact-submissions.view', 'careers.view'])
                <flux:navlist.group :heading="__('Leads')" class="grid">
                    @can('newsletter-subscriptions.view')
                        <flux:navlist.item
                            icon="inbox-arrow-down"
                            :href="route('admin.newsletter-subscriptions.index')"
                            :current="request()->routeIs('admin.newsletter-subscriptions.*')"
                            wire:navigate
                        >{{ __('Newsletter') }}</flux:navlist.item>
                    @endcan
                    @can('contact-submissions.view')
                        <flux:navlist.item
                            icon="envelope"
                            :href="route('admin.contact-submissions.index')"
                            :current="request()->routeIs('admin.contact-submissions.*')"
                            wire:navigate
                        >{{ __('Contact Submissions') }}</flux:navlist.item>
                    @endcan
                    @can('careers.view')
                        <flux:navlist.item
                            icon="document-text"
                            :href="route('admin.career-submissions.index')"
                            :current="request()->routeIs('admin.career-submissions.*')"
                            wire:navigate
                        >{{ __('Job Applications') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endcanany

            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:navlist.item>
                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
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
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
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