@extends('layoutCore')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:title" content="PPFTEMPLATE.COM" />
    <meta property="og:description"
        content="Download PPF Template & Artistic Wrap Design Files for cars from many popular brands such as Mercedes-Benz, BMW, Audi, Toyota, Tesla, and more.
Files are available in CDR, SVG, DXF, AI, and PDF formats, ready for precise cutting on most plotter machines. Each template and design is accurately created and tested to ensure perfect fitment for PPF installation and artistic vinyl wraps, helping installers achieve clean edges, faster workflow, and professional-quality results with standard automotive wrap and paint protection materials." />
    <meta property="og:image" content="https://ppftemplate.com/images/logo.png" />
    <meta property="og:url" content="https://ppftemplate.com" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PPFTEMPLATE.COM">
    <meta name="twitter:description" content="Download PPF Template & Artistic Wrap Design Files for cars from many popular brands such as Mercedes-Benz, BMW, Audi, Toyota, Tesla, and more.
Files are available in CDR, SVG, DXF, AI, and PDF formats, ready for precise cutting on most plotter machines. Each template and design is accurately created and tested to ensure perfect fitment for PPF installation and artistic vinyl wraps, helping installers achieve clean edges, faster workflow, and professional-quality results with standard automotive wrap and paint protection materials.">
    <meta name="twitter:image" content="https://ppftemplate.com/images/logo.png">

    <link rel="stylesheet" href="{{ asset('css/track-css.css') }}">

    {{-- bootrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstraps/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap-icons.css') }}">

    {{-- font-awesome --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/brands.css') }}">

    {{-- title --}}
    <title>{{ __('message.webName') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Example import css , js , image --}}
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet.css') }}">

    <script src="{{ asset('js/handleHtml.js') }}"></script>

    <script>
        const routeGetFileTemplate = @json(route('get.file', ['filename' => ':filename']));
    </script>
    <script src="{{ asset('js/track-js.js') }}"></script>

    {{-- jquery --}}
    <script src="{{ asset('js/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/translate.js') }}"></script>
    @include('component.search-modal.import-js')
    {{-- security --}}
    <script src="{{ asset('js/security.js') }}"></script>
    <script src="{{ asset('js/modalNologin.js') }}"></script>
    {{-- <script src="{{asset('js/tooltip.js')}}"></script> --}}
    {{-- <script src="{{ asset('js/block-dev-tool.js') }}"></script> --}}
@endsection

@section('body')
    <noscript>{{ __('message.noscript') }}</noscript>
    @include('header')


    @yield('content')
    @include('component.tracking-codes.index')
    @include('footer')
    @include('component.button.scrollToTop')
    @include('component.cart-modal.index')
    @include('component.language-modal.index')
    @include('component.modal.modalNoLogin')
@endsection
