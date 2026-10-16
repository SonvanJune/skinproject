@extends('layout')

@php
    $brandImg = !empty($category->image_path) ? route('get.file', ['filename' => $category->image_path]) : asset('images/logo.png');
@endphp

@section('title', __('message.brandMetaTitle', ['name' => $category->name]))
@section('meta_title', __('message.brandMetaTitle', ['name' => $category->name]))
@section('meta_description', __('message.brandMetaDescription', ['name' => $category->name]))
@section('og_image', $brandImg)

@push('schema')
    @include('component.schema.index', [
        'type' => 'breadcrumb',
        'breadcrumbs' => [
            ['name' => __('message.home'), 'url' => url(App::getLocale())],
            ['name' => $category->name, 'url' => url()->current()],
        ]
    ])
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@section('content')
    <div class="brand">
        {{-- Luxury Breadcrumb Navigation --}}
        <div class="brand-breadcrumbs container mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url(App::getLocale() . '/') }}">
                            <i class="fa-solid fa-house me-1"></i> {{ __('message.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $category->name }}
                    </li>
                </ol>
            </nav>
        </div>

        @if ($description != null)
            <div class="image-type2">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-5 col-sm-12 text-center text-md-start mb-3 mb-md-0">
                            <img src="{{ route('get.file', ['filename' => $category->image_path]) }}" alt="{{ $category->image_alt ?? $category->name }}" class="brand-hero-img">
                        </div>
                        <div class="col-md-7 col-sm-12">
                            <h2>
                                {{ $category->name }}
                            </h2>
                            @include('component.brand.description', [
                                'descriptions' => $description,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="image-type1">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-sm-12 text-center">
                            @if (!empty($category->image_path))
                                <img src="{{ route('get.file', ['filename' => $category->image_path]) }}" alt="{{ $category->image_alt ?? $category->name }}" class="brand-hero-img mb-3">
                            @endif
                            <h2>{{ $category->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Sub-categories / Models (if any) --}}
        @include('brand.brand-list', [
            'categories' => $category_child,
        ])
    </div>
@endsection

@push('js')
    {{-- Import js if have --}}
@endpush
