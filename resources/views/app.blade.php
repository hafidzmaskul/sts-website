<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Absolutely Human Resources') }}</title>
    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400;700&family=DM+Sans:wght@400;500&family=Inter:wght@300;400;500;600;700;800&family=Inter+Tight:wght@600&family=Nunito+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                if (registrations.length > 0) {
                    for(let registration of registrations) {
                        registration.unregister();
                    }
                    window.location.reload();
                }
            });
        }
    </script>


</head>

<body class="min-h-dvh ">

    @inertia

</body>

</html>
