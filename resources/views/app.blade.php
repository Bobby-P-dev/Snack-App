<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- SEO Global Fallback (ditimpa per-halaman via Inertia <Head>) -->
        <meta name="description" content="Padu Kue — Toko kue & custom snack box online. Pesan aneka kue basah, jajanan pasar, dan rakit snack box untuk acara Anda.">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="robots" content="index, follow">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Padu Kue">
        <meta property="og:locale" content="id_ID">
        <meta property="og:title" content="Padu Kue — Toko Kue & Custom Snack Box">
        <meta property="og:description" content="Pesan kue dan snack box jadi lebih mudah. Pilih aneka kue favorit dan susun isi snack box sesuai kebutuhan Anda.">
        <meta property="og:image" content="{{ url('/images/padukue-logo.png') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Padu Kue — Toko Kue & Custom Snack Box">
        <meta name="twitter:description" content="Pesan kue dan snack box jadi lebih mudah. Pilih aneka kue favorit dan susun isi snack box sesuai kebutuhan Anda.">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="shortcut icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
