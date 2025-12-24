@extends('layoutCore')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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
    <link rel="icon" type="image/png" href="{{asset('images/logo.png')}}">

    {{-- Example import css , js , image --}}
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet.css') }}">

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
    <script src="{{asset('js/modalNologin.js')}}"></script>
    {{-- <script src="{{asset('js/tooltip.js')}}"></script> --}}
    <script src="{{ asset('js/block-dev-tool.js') }}"></script>
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
