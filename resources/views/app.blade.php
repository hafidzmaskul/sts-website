<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Absolutely Human Resources') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@400;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/4a86e4751d2650701d8bb25433b8781e?family=AkzidenzGroteskBQ-Super"
        rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    @php
        $googleAnalyticsId = \App\Models\Setting::where('key', 'google_analytics_id')->first()?->value;
        $googleTagManagerId = \App\Models\Setting::where('key', 'google_tag_manager_id')->first()?->value;
        $customScriptHeader = \App\Models\Setting::where('key', 'custom_script_header')->first()?->value;
        $customScriptFooter = \App\Models\Setting::where('key', 'custom_script_footer')->first()?->value;
    @endphp

    @if($googleAnalyticsId)
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $googleAnalyticsId }}');
        </script>
    @endif

    @if($googleTagManagerId)
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || []; w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                }); var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $googleTagManagerId }}');</script>
        <!-- End Google Tag Manager -->
    @endif

    @if($customScriptHeader)
        {!! $customScriptHeader !!}
    @endif
</head>

<body class="min-h-dvh bg-[#302F2F]">
    @if($googleTagManagerId)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $googleTagManagerId }}" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    @inertia
    @if($customScriptFooter)
        {!! $customScriptFooter !!}
    @endif
</body>

</html>
