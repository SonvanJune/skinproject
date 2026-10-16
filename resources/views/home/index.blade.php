@extends('layout')

@section('title', __('message.defaultMetaTitle'))
@section('meta_title', 'PPFTEMPLATE - ' . __('message.defaultMetaTitle'))
@section('meta_description', __('message.defaultMetaDescription'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush

@section('content')
    <div class="home-snap-container">

        <!-- Section 1: Hero Section -->
        <section class="home-section hero-section" id="section-hero">
            <div class="home-section-inner container">
                @include('component.hero.index', [])
            </div>
        </section>

        <!-- Section 2: Daily Offer & Quick Categories -->
        <section class="home-section daily-section" id="section-daily">
            <div class="home-section-inner container">
                <div class="daily">
                    <img src="{{ asset('images/ab.png') }}" alt="daily">
                    <a class="btn-daily" href="{{ route('saleProducts') }}">
                        <i class="bi bi-bullseye"></i>
                        <div class="item">
                            <h1>{{ __('message.dailyOffer') }}</h1>
                        </div>
                    </a>
                    <p>{{ __('message.goToDailyOffer') }}</p>
                </div>

                {{-- menu --}}
                <div class="menu">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-center">
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <a class="flex-fill d-flex justify-content-center" style="text-decoration: none"
                                            href="{{ $category->url }}">
                                            <i class="bi bi-phone-fill"></i>
                                            <div class="flex-item">
                                                <h2>{{ $category->name }}</h2>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Mirror Hall 3D Showcase Carousel -->
        <section class="home-section mirror-section" id="section-mirror">
            <div class="home-section-inner w-100">
                @include('component.carousel.carousel', [])
            </div>
        </section>

        <!-- Dynamic Product News, Popular Products & Categories -->
        @isset($categories)
            @foreach ($categories as $key => $category)
                @if ($key == 0)
                    @if ($productNews && count($productNews) > 0)
                        <section class="home-section news-section" id="section-news">
                            <div class="home-section-inner container">
                                <div class="new">
                                    @include('component.svg-title.svg-title', [
                                        'titleBig' => __('message.newUploadTemplates'),
                                        'link' => 'new-products',
                                    ])
                                </div>
                                @include('home.home-products', [
                                    'products' => $productNews,
                                    'fakeView' => false,
                                ])
                            </div>
                        </section>
                    @endif
                @endif

                @if ($key == 1)
                    @if ($productPopulars && count($productPopulars) > 0)
                        <section class="home-section populars-section" id="section-populars">
                            <div class="home-section-inner container">
                                <div class="new">
                                    @include('component.svg-title.svg-title', [
                                        'titleBig' => __('message.newPopularTemplates'),
                                        'link' => 'popular-products',
                                    ])
                                </div>
                                @include('home.popular-product', [
                                    'products' => $productPopulars,
                                    'fakeView' => true,
                                ])
                            </div>
                        </section>
                    @endif
                @endif

                <section class="home-section category-section" id="section-cat-{{ $key }}">
                    <div class="home-section-inner container">
                        @include('component.card.card-intro', [
                            'titleBig' => $category->name . ' - ' . __('message.homeBigTitle'),
                            'titleSmall' => $category->name . ' ' . __('message.homeSmallTitle'),
                            'children' => $category->children,
                            'categoryName' => $category->name,
                            'background' => route('get.file', ['filename' => $category->image_path]),
                            'fixed' => 'fixed',
                        ])
                    </div>
                </section>
            @endforeach
        @endisset

        <!-- Brand Showcase & Thank You -->
        <section class="home-section brand-section" id="section-brand">
            <div class="home-section-inner container">
                <div class="thank">
                    <h4>{{ __('message.thankForWatching') }}</h4>
                </div>
                @include('component.carousel.carousel-drug', [])
            </div>
        </section>

    </div>


    @include('component.noticationNoData.loading.index', [])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'add_to_cart_failed',
        'type' => 'error',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'add_to_cart_success',
        'type' => 'success',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'error',
        'type' => 'error',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'success',
        'type' => 'success',
    ])
@endsection

@push('js')
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script src="{{ asset('js/section-scroll.js') }}"></script>
@endpush
