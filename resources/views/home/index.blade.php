@extends('layout')

@section('title', 'Homepage')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush

@section('content')
    <section class="home">
        {{-- carousel img --}}
        @include('component.carousel.carousel', [])

        {{-- menu --}}
        <div class="menu">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="d-flex flex-wrap flex-md-nowrap">
                            @if ($categories)
                                @foreach ($categories as $category)
                                    <a class="flex-fill d-flex justify-content-center" style="text-decoration: none"
                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                                        <i class="bi bi-phone-fill"></i>
                                        <div class="flex-item ">
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

        @isset($categories)
            @foreach ($categories as $key => $category)
                @if ($key == 0)
                    @if ($productPopulars && count($productPopulars) > 0)
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
                    @endif
                @endif
                @if ($key == 1)
                    @if ($productNews && count($productNews) > 0)
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
                    @endif
                @endif
                @include('component.card.card-intro', [
                    'titleBig' => 'Best Quantity All ' . $category->name . ' Template Tested',
                    'titleSmall' => $category->name . ' Template',
                    'children' => $category->children,
                    'icon' => [
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                        'fa-solid fa-' . strtolower($category->name),
                    ],
                    'background' => route('get.file', ['filename' => $category->image_path]),
                    'fixed' => 'fixed',
                ])
            @endforeach
        @endisset

        <div class="thank">
            <h4>{{ __('message.thankForWatching') }}</h4>
        </div>

        @include('component.carousel.carousel-drug', [])

        @if (session('register_success'))
            <div class="page-overlay"></div>
            <div class="alert alert-warning alert-dismissible fade show zoom-in" role="alert">
                {{ session('register_success') }}
                <button type="button" class="btn-close" onclick="closeAlert()" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

    </section>

    @include('component.noticationNoData.loading.index', [])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'add_to_cart_failed',
        'type' => 'error'
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'add_to_cart_success',
        'type' => 'success'
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'error',
        'type' => 'error'
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'success',
        'type' => 'success'
    ])
@endsection

@push('js')
    <script src="{{ asset('js/product-detail.js') }}"></script>
@endpush
