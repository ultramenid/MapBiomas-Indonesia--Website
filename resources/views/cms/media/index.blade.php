@php
$content = view('cms.media.content', ['isPicker' => $isPicker, 'defaultType' => $defaultType]);
@endphp

@if ($isPicker)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Media Picker</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/thumbnail.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            var stored = localStorage.getItem('cms-theme');
            var dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="bg-canvas font-sans text-ink antialiased p-4 sm:p-6 min-h-screen">
    {!! $content !!}
</body>
</html>
@else
<x-cms-layout title="Media Library">
    <div class="p-6">
        {!! $content !!}
    </div>
</x-cms-layout>
@endif
