@extends('layout')

@section('title', $searchQuery ?? __('message.searchResult'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endpush

@section('content')
    <div class="search">
        <div class="search-container">
            <div class="search-banner" style="background-image: url('{{ asset('images/breadcamb.jpg') }}')">
                <div class="container">
                    <h2>{{ __('message.searchResult') }}</h2>
                    <p class="breadcrumb">
                        <a href="{{ route('home') }}">{{ __('message.home') }}</a> <span>></span>
                        <span>{{ __('message.searchResult') }}</span>
                    </p>
                </div>
            </div>

            <div class="container mt-4 search-content">
                @isset($searchResult)
                    <div class="row">
                        <aside class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">
                                    Filter
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Filtered:</label>
                                        <div id="filtered-badges">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="job-titles" class="form-label fw-bold">Filter Selection</label>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                @if (count($searchResult->products) > 0)
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-products" checked onchange="toggleVisibility()" />
                                                @else
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-products" onchange="toggleVisibility()" />
                                                @endif
                                                <label class="form-check-label"
                                                    for="filter-products">{{ __('message.productTitle') }}</label>
                                            </li>
                                            <li class="list-group-item">
                                                @if (count($searchResult->categories) > 0)
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-categories" checked onchange="toggleVisibility()" />
                                                @else
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-categories" onchange="toggleVisibility()" />
                                                @endif
                                                <label class="form-check-label"
                                                    for="filter-categories">{{ __('message.categoryTitle') }}</label>
                                            </li>
                                            <li class="list-group-item">
                                                @if (count($searchResult->brands) > 0)
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-brands" checked onchange="toggleVisibility()" />
                                                @else
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-brands" onchange="toggleVisibility()" />
                                                @endif
                                                <label class="form-check-label"
                                                    for="filter-brands">{{ __('message.brand') }}</label>
                                            </li>
                                            <li class="list-group-item">
                                                @if (count($searchResult->posts) > 0)
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-posts" checked onchange="toggleVisibility()" />
                                                @else
                                                    <input class="form-check-input me-1" type="checkbox" value=""
                                                        id="filter-posts" onchange="toggleVisibility()" />
                                                @endif
                                                <label class="form-check-label"
                                                    for="filter-posts">{{ __('message.postTitle') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>

                        <main class="col-md-8">
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                @foreach ($searchResult->products as $product)
                                    <div class="col product-item">
                                        <div class="job-item card h-100 w-100">
                                            <div class="card-body d-flex flex-row">
                                                <div class="company-info">
                                                    <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                                        alt="{{ $product->post_image_alt }}" onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"/>
                                                </div>
                                                <div class="card-right d-flex flex-column">
                                                    <h5 class="card-title mb-0">
                                                        {{ $product->product_name }}
                                                    </h5>
                                                    <span
                                                        class="text-danger fw-bold note-small">★{{ __('message.productTitle') }}</span>
                                                    <p class="card-text salary">
                                                        @if ($product->product_price != $product->product_price_sale)
                                                            <span class="price-sale">${{ $product->product_price }}</span>
                                                            ${{ $product->product_price_sale }}
                                                        @else
                                                            ${{ $product->product_price }}
                                                        @endif
                                                    </p>
                                                    @if ($product->can_download == true)
                                                        <p class="card-text">
                                                            <span class="badge bg-info">{{ __('message.hasOwned') }}</span>
                                                        </p>
                                                    @endif
                                                    <a class="btn btn-primary btn-sm me-2 mt-3"
                                                        href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}">
                                                        {{ __('message.viewDetailPost') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($searchResult->categories as $category)
                                    <div class="col category-item">
                                        <div class="job-item card h-100 w-100">
                                            <div class="card-body d-flex flex-row">
                                                <div class="company-info">
                                                    <img src="{{ route('get.file', ['filename' => $category->image_path])}}"
                                                        alt="{{ $category->image_alt }}" onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"/>
                                                </div>
                                                <div class="card-right d-flex flex-column">
                                                    <h5 class="card-title mb-0">
                                                        {{ $category->name }}
                                                    </h5>
                                                    <span
                                                        class="text-danger fw-bold note-small">★{{ __('message.categoryTitle') }}</span>
                                                    <a class="btn btn-primary btn-sm me-2 mt-3"
                                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                                                        {{ __('message.viewDetailPost') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($searchResult->brands as $category)
                                    <div class="col brand-item">
                                        <div class="job-item card h-100 w-100">
                                            <div class="card-body d-flex flex-row">
                                                <div class="company-info">
                                                    <img src="{{ route('get.file', ['filename' => $category->image_path]) }}"
                                                        alt="{{ $category->image_alt }}" onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"/>
                                                </div>
                                                <div class="card-right d-flex flex-column">
                                                    <h5 class="card-title mb-0">
                                                        {{ $category->name }}
                                                    </h5>
                                                    <span class="text-danger fw-bold note-small">★{{ __('message.brand') }}</span>
                                                    <a class="btn btn-primary btn-sm me-2 mt-3"
                                                        href="{{ url(App::getLocale() . '/brand' . '/' . $category->slug) }}">
                                                        {{ __('message.viewDetailPost') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($searchResult->posts as $post)
                                    <div class="col post-item">
                                        <div class="job-item card h-100 w-100">
                                            <div class="card-body d-flex flex-row">
                                                <div class="company-info">
                                                    <img src="{{ route('get.file', ['filename' => $post->image_path]) }}"
                                                        alt="{{ $post->image_alt }}" onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"/>
                                                </div>
                                                <div class="card-right d-flex flex-column">
                                                    <h5 class="card-title mb-0">
                                                        {{ $post->name }}
                                                    </h5>
                                                    <span
                                                        class="text-danger fw-bold note-small">★{{ __('message.postTitle') }}</span>
                                                    <a class="btn btn-primary btn-sm me-2 mt-3"
                                                        href="{{ url(App::getLocale() . '/blog' . '/' . $post->slug) }}">
                                                        {{ __('message.viewDetailPost') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </main>
                    </div>
                @else
                    @include('component.noticationNoData.list.index', [])
                @endisset
            </div>
        </div>
    </div>
    @include('component.noticationNoData.loading.index', [])
@endsection

@push('js')
    <script>
        const categoryTitle = @json(__('message.categoryTitle'));
        const productTitle = @json(__('message.productTitle'));
        const postTitle = @json(__('message.postTitle'));
        const brandTitle = @json(__('message.brand'));
    </script>
    <script src="{{ asset('js/search.js') }}"></script>
@endpush
