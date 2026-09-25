<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/thumbnail.png') }}" type="image/x-icon">
    <title>{{ $title ?? 'Page Title'}}</title>
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script
    src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.x.x/dist/cdn.min.js"
    defer
    ></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    @livewireStyles

    @livewireScripts

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SYKJ4XDS53"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-SYKJ4XDS53');
    </script>
</head>

<body class="frontend font-sans">

    @yield('content')
    @stack('scripts')


</body>
</html>
