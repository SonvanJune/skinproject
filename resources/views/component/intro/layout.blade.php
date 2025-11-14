@extends('layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/intro.css') }}">
    <style>
        .intro-page {
            background-image: url('{{ asset('images/intro.jpg') }}');
        }
    </style>
@endpush

@section('content')
    <div class="intro-page">
        @yield('admin')
        <div class="left-panel">
            @include('component.carousel.carousel-drug', [])
        </div>
        <div class="right-panel">
            <a href="{{ url(App::getLocale() . '/') }}" class="back"><i class="fa-solid fa-house"></i></a>
            <div class="title">
                <h1>@yield('title-intro')</h1>
            </div>
            @yield('form')
        </div>
        @include('component.noticationNoData.notification.index', ['notification' => 'register_failed' , 'type' => 'error'])
        @include('component.noticationNoData.notification.index', ['notification' => 'failed' , 'type' => 'error'])
        @include('component.noticationNoData.notification.index', ['notification' => 'reset_success', 'type' => 'success'])
        @include('component.noticationNoData.notification.index', ['notification' => 'permission_denied', 'type' => 'warning'])
        @include('component.noticationNoData.notification.index', ['notification' => 'token_expired', 'type' => 'warning'])
        @include('component.noticationNoData.notification.index', ['notification' => 'success', 'type' => 'success'])
        @include('component.noticationNoData.notification.index', ['notification' => 'error' , 'type' => 'error'])
    </div>
@endsection

@push('js')
@endpush
