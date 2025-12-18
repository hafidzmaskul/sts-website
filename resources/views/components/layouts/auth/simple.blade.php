<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <!-- Header -->
        @include('partials.auth-header')

        <div class="relative flex flex-col items-center justify-center gap-6 p-6 min-h-[70vh] w-full" style="background: url('/assets/bg-login.png') center center / cover no-repeat;">
            <div class="absolute left-0 top-0 w-full h-full" style="background: #1976D2E5;"></div>
            <div class="relative flex w-full max-w-md flex-col gap-2 bg-background/90 py-20 text-black ">
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>


        @fluxScripts
    </body>
</html>
