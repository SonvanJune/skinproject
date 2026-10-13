@extends('layout')

@section('title', __('message.defaultMetaTitle'))
@section('meta_title', 'PPFTEMPLATE - ' . __('message.defaultMetaTitle'))
@section('meta_description', __('message.defaultMetaDescription'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush

@section('custom_footer')
    {{-- Footer is integrated inside home vertical carousel as the final slide --}}
@endsection

@section('content')
    <div class="vscroll-viewport" id="vscrollViewport">
        <div class="vscroll-track" id="vscrollTrack">

            <!-- Slide 1: Hero Section -->
            <section class="vscroll-slide vscroll-slide-hero" id="vslide-hero">
                <div class="vslide-inner container">
                    @include('component.hero.index', [])
                </div>
            </section>

            <!-- Slide 2: Daily Offer & Quick Categories -->
            <section class="vscroll-slide vscroll-slide-daily" id="vslide-daily">
                <div class="vslide-inner container">
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
                                                href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
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

            <!-- Slide 3: Mirror Hall 3D Showcase Carousel -->
            <section class="vscroll-slide vscroll-slide-mirror" id="vslide-mirror">
                <div class="vslide-inner w-100">
                    @include('component.carousel.carousel', [])
                </div>
            </section>

            <!-- Dynamic Product News, Popular Products & Categories -->
            @isset($categories)
                @foreach ($categories as $key => $category)
                    @if ($key == 0)
                        @if ($productNews && count($productNews) > 0)
                            <section class="vscroll-slide vscroll-slide-products" id="vslide-news">
                                <div class="vslide-inner container">
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
                            <section class="vscroll-slide vscroll-slide-products" id="vslide-populars">
                                <div class="vslide-inner container">
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

                    <section class="vscroll-slide vscroll-slide-category" id="vslide-cat-{{ $key }}">
                        <div class="vslide-inner container">
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

            <!-- Slide: Thank You & Brand Showcase -->
            <section class="vscroll-slide vscroll-slide-brand" id="vslide-brand">
                <div class="vslide-inner container">
                    <div class="thank">
                        <h4>{{ __('message.thankForWatching') }}</h4>
                    </div>
                    @include('component.carousel.carousel-drug', [])
                </div>
            </section>

            <!-- Slide: Footer Slide -->
            <section class="vscroll-slide vscroll-slide-footer" id="vslide-footer">
                <div class="vslide-inner w-100">
                    @include('footer')
                </div>
            </section>

        </div>
    </div>

    @if (session('register_success'))
        <div class="page-overlay"></div>
        <div class="alert alert-warning alert-dismissible fade show zoom-in" role="alert">
            {{ session('register_success') }}
            <button type="button" class="btn-close" onclick="closeAlert()" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif

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
    <script src="{{ asset('js/vertical-scroll.js') }}"></script>
@endpush
