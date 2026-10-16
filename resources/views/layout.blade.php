@extends('layoutCore')

@section('head')
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Title --}}
    <title>@hasSection('meta_title')@yield('meta_title')@else{{ __('message.webName') }} - @yield('title', __('message.defaultMetaTitle'))@endif</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', __('message.defaultMetaDescription'))">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Multilingual Hreflang Tags (ISO 639-1 & Google Search Console Standard) --}}
    @php
        $supportedLocales = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales();
        $isoLanguageMap = [
            'zh' => 'zh-Hans',
            'ja' => 'ja',
        ];
    @endphp
    @foreach($supportedLocales as $localeCode => $properties)
        @php
            $hreflangCode = $isoLanguageMap[$localeCode] ?? $localeCode;
            $localizedUrl = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
        @endphp
        <link rel="alternate" hreflang="{{ $hreflangCode }}" href="{{ $localizedUrl }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL('en', null, [], true) }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:title" content="@yield('og_title', View::hasSection('meta_title') ? View::getSection('meta_title') : (View::hasSection('title') ? View::getSection('title') . ' - PPFTEMPLATE' : __('message.webName') . ' - ' . __('message.defaultMetaTitle')))" />
    <meta property="og:description" content="@yield('meta_description', __('message.defaultMetaDescription'))" />
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))" />
    <meta property="og:url" content="@yield('canonical', url()->current())" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:site_name" content="PPFTEMPLATE" />

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', View::hasSection('meta_title') ? View::getSection('meta_title') : (View::hasSection('title') ? View::getSection('title') . ' - PPFTEMPLATE' : __('message.webName')))">
    <meta name="twitter:description" content="@yield('meta_description', __('message.defaultMetaDescription'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo.png'))">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Global Organization & WebSite JSON-LD Schema --}}
    @include('component.schema.index', ['type' => 'global'])

    {{-- Fonts: Italiana & Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">

    {{-- Core CSS (Libraries via CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/track-css.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bronze-luxury-theme.css') }}">
    {{-- Toastify & Luxury Toast --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('css/luxury-toast.css') }}">
@endsection

@section('body')
    <!-- Dynamic Liquid Bronze & Sparks Background Canvas (Global across all pages) -->
    <canvas id="bronze-bg-canvas"></canvas>

    <noscript>{{ __('message.noscript') }}</noscript>
    @include('header')

    @yield('content')
    @include('component.tracking-codes.index')
    @hasSection('custom_footer')
        @yield('custom_footer')
    @else
        @include('footer')
    @endif
    @include('component.button.scrollToTop')
    @include('component.cart-modal.index')
    @include('component.language-modal.index')
    @include('component.modal.modalNoLogin')
    @include('component.toast.index')

    {{-- Core JavaScripts (Libraries via CDN) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Toast Library & Controller --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('js/luxury-toast.js') }}"></script>
    <script src="{{ asset('js/handleHtml.js') }}"></script>
    <script>
        const routeGetFileTemplate = @json(route('get.file', ['filename' => ':filename']));
    </script>
    <script src="{{ asset('js/track-js.js') }}"></script>
    <script src="{{ asset('js/translate.js') }}"></script>
    @include('component.search-modal.import-js')
    <script src="{{ asset('js/security.js') }}"></script>
    <script src="{{ asset('js/modalNologin.js') }}"></script>
    <script src="{{ asset('js/cart-ajax.js') }}"></script>
    <!-- Background Shader: Liquid Bronze & Floating Forge Sparks (Global) -->
    <script type="module" src="{{ asset('js/bronze-background.js') }}"></script>
@endsection
