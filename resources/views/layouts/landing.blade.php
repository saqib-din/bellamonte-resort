<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="description"
        content="Bellamonte Resort Shogran offers luxury rooms, mountain views, hotel booking, family stays, and premium hospitality services in Shogran." />

    <meta name="keywords"
        content="Bellamonte Resort, Shogran Hotel, luxury resort, hotel booking, mountain resort, family hotel, Shogran tourism, Pakistan hotels, vacation resort, rooms booking" />

    <meta name="author" content="Bellamonte Resort" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Bellamonte Resort</title>

    {{-- Icon Bellamonte --}}
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/bella.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('admin/assets/images/bella.png') }}" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('landing-assets/css/bootstrap.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/font-awesome.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/elegant-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/flaticon.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/owl.carousel.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/nice-select.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/jquery-ui.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/magnific-popup.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/slicknav.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('landing-assets/css/style.css') }}" type="text/css" />

    @stack('styles')

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Section Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="canvas-open">
        <i class="icon_menu"></i>
    </div>
    <div class="offcanvas-menu-wrapper">
        <div class="canvas-close">
            <i class="icon_close"></i>
        </div>
        <nav class="mainmenu mobile-menu">
            <ul>
                <li class="active"><a href="{{ route('welcomepage') }}">Home</a></li>
                <li><a href="{{ route('rooms.list') }}">Rooms</a></li>
                <li><a href="{{ route('events.list') }}">Events</a></li>
                <li><a href="{{ route('about.us') }}">About Us</a></li>
                <li><a href="{{ route('contact.us') }}">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>

        <h4 class="m-4"> Get In Touch </h4>
        {{-- <div class="top-social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-tripadvisor"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
        </div> --}}
        <ul class="top-widget">
            <li><i class="fa fa-phone"></i>0329 6777222</li>
            <li><i class="fa fa-envelope"></i>info@bellamonteresort.com</li>
        </ul>
    </div>

    @include('partials.landing.header')

    @yield('hero')

    <!-- Main-content -->
    <div class="main-content pt-0 page-index">

        @yield('content')

    </div>
    <!-- /.Main-content -->

    @include('partials.landing.footer')


    <!-- WhatsApp Floating Button START -->
    <a href="https://wa.me/923296777222" class="whatsapp-float" target="_blank" title="Chat with us on WhatsApp">
        <i class="fa fa-whatsapp"></i>
    </a>
    <!-- WhatsApp Floating Button END -->

    <!-- Js Plugins -->

    <script src="{{ asset('landing-assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('landing-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing-assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
