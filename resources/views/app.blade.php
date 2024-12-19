<!DOCTYPE html>
<html class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Inertia --}}
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>

    @vite('resources/js/app.js')
    {{ Vite::useBuildDirectory('vendor/ninshiki')
    ->useHotFile('vendor/ninshiki/ninshiki.hot')
    ->withEntryPoints(['resources/js/app.js', 'resources/css/ninshiki.css']) }}
    @inertiaHead
</head>
<body class="font-sans leading-none text-gray-700 antialiased">
@inertia
</body>
</html>
