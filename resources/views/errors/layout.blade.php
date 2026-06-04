<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $status = trim($__env->yieldContent('code'));
        $headline = trim($__env->yieldContent('title'));
        $pageTitle = $headline !== '' ? $headline . ' • ' . config('app.name') : config('app.name') . ' • Error';

        $backUrl = url()->previous();
        if (! $backUrl || $backUrl === url()->current()) {
            $backUrl = route('home', absolute: false);
        }
    @endphp

    <title>{{ $pageTitle }}</title>

    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#302F2F] text-zinc-100 antialiased">
    <div class="relative isolate flex min-h-screen items-center justify-center px-6 py-14">
        <div class="absolute inset-0">
            <div class="absolute left-6 top-10 h-44 w-44 rounded-full bg-[#F8B803]/15 blur-3xl"></div>
            <div class="absolute right-12 bottom-0 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute inset-x-10 top-32 h-[520px] rounded-[32px] border border-white/5 bg-white/5 blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-white/10 bg-white/5 px-8 py-10 shadow-[0_30px_120px_-35px_rgba(0,0,0,0.55)] backdrop-blur">
            <div class="flex flex-wrap items-center gap-3 text-sm font-medium uppercase tracking-[0.14em] text-zinc-300">
                <span class="inline-flex items-center gap-2 rounded-full bg-[#F8B803]/10 px-4 py-2 text-[#F8B803] ring-1 ring-[#F8B803]/30">
                    <span class="size-2 rounded-full bg-[#F8B803]"></span>
                    {{ $status !== '' ? $status : 'Error' }}
                </span>
            </div>

            <div class="mt-6 space-y-4">
                <h1 class="text-4xl font-semibold leading-tight text-white md:text-5xl">
                    @yield('title')
                </h1>
                <p class="max-w-3xl text-lg leading-relaxed text-zinc-200">
                    @yield('message')
                </p>
            </div>

            <div class="mt-10 flex flex-wrap items-center gap-3">
                <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 rounded-full border border-[#F8B803]/30 bg-[#F8B803]/10 px-4 py-2 text-sm font-semibold text-[#F8B803] transition hover:-translate-y-0.5 hover:bg-[#F8B803]/15 hover:shadow-[0_20px_50px_-30px_rgba(248,184,3,0.9)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    {{ __('Back') }}
                </a>

                {{-- <a href="{{ route('home', absolute: false) }}" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/15 hover:shadow-[0_20px_50px_-30px_rgba(255,255,255,0.8)]">
                    {{ __('Go home') }}
                </a> --}}

                {{-- <a href="{{ route('contact-us') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-zinc-100 transition hover:-translate-y-0.5 hover:border-white/25 hover:bg-white/5">
                    {{ __('Contact us') }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4.5 6.75 12 12l7.5-5.25M4.5 17.25 12 12l7.5 5.25" />
                    </svg>
                </a> --}}
            </div>

            <div class="mt-8 grid gap-2 text-sm text-zinc-400 md:grid-cols-2">
                <div class="flex items-center gap-3">
                    <span class="h-px w-10 bg-white/20"></span>
                    <span>{{ __('Check the URL or use the main navigation.') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="h-px w-10 bg-white/20"></span>
                    <span>{{ __('If the issue continues, our team can help.') }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
