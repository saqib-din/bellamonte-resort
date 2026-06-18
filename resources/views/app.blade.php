<!doctype html>
<html lang="en">

<head>
    {{-- <title>{{ config('app.name', 'Bellamonte Resort') }}</title> --}}
    <title>Bellamonte Resort</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description"
        content="Bellamonte Resort Shogran offers luxury rooms, mountain views, hotel booking, family stays, and premium hospitality services in Shogran." />

    <meta name="keywords"
        content="Bellamonte Resort, Shogran Hotel, luxury resort, hotel booking, mountain resort, family hotel, Shogran tourism, Pakistan hotels, vacation resort, rooms booking" />

    <meta name="author" content="Bellamonte Resort" />

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/logo.jpeg') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('admin/assets/images/logo.jpeg') }}" />

    {{-- Able Pro theme CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/fonts/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/animate.min.css') }}" />

    <script src="{{ asset('admin/assets/js/tech-stack.js') }}"></script>

    @inertiaHead
    @vite(['resources/js/app.js'])
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="">

    @inertia

    {{-- Able Pro theme JS (chrome: sidebar scroll, dropdowns, dark/light, charts) --}}
    <script src="{{ asset('admin/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('admin/assets/js/script.js') }}"></script>
    <script src="{{ asset('admin/assets/js/theme.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/apexcharts.min.js') }}"></script>
</body>

</html>
