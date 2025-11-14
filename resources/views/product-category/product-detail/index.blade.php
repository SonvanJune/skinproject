@extends('layout')

@section('title', $product->product_name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
    <div class="product-detail">
        <div class="container">
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ $product->product_name }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ __('message.productTitle') }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="{{ url(App::getLocale() . '/product-category' . '/' . $product->product_slug) }}">{{ $product->product_name }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="zoomer">
                        <div class="image">
                            @if ($product->product_images)
                                <img id="mainImage" alt="{{ $product->product_images[0]->product_image_alt }}"
                                    src="{{ route('get.file', ['filename' => $product->product_images[0]->product_image_path]) }}"
                                    class="zoomImg">
                                <div class="result hide"></div>
                            @else
                                <img id="mainImage" alt="{{ $product->post_image_alt }}"
                                    src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                    class="zoomImg">
                                <div class="result hide"></div>
                            @endif
                        </div>

                        <!-- Image thumbnails below the main image -->
                        <div class="image-thumbnails">
                            @foreach ($product->product_images as $image)
                                <img src="{{ route('get.file', ['filename' => $image->product_image_path]) }}"
                                    alt="{{ $image->product_image_alt }}" class="thumbnail"
                                    data-src="{{ route('get.file', ['filename' => $image->product_image_path]) }}" />
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 info">
                    <h2>{{ $product->product_name }}</h2>
                    <div class="view d-flex">
                        {{-- <div class="star d-flex">
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                        </div> --}}
                        <div class="view-in">
                            <i class="fa-solid fa-eye"></i>
                            @if ($product->product_status_views == 0)
                                <span>{{ $product->product_fake_views }}</span>
                            @else
                                <span>{{ $product->product_views }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="price">
                        @if ($product->product_price != $product->product_price_sale)
                            <span class="price-sale">${{ $product->product_price }}</span>
                        @endif
                        {{ '$' . $product->product_price_sale }}
                    </div>
                    <h6>
                        {!! $product->post_content !!}
                    </h6>
                    @if (count($categories_detail) > 0)
                        <h6 class="mb-2">{{ __('message.categories') }}:</h6>
                        <div class="d-flex flex-wrap gap-2" id="productCategories">
                            @foreach ($categories_detail as $cat)
                                <a href="{{ url(App::getLocale() . '/product-category' . '/' . $cat->slug) }}"
                                    class="category-badge d-flex align-items-center border rounded px-2 py-1 text-decoration-none text-dark"
                                    style="max-width: 180px;">
                                    <img src="{{ route('get.file', ['filename' => $cat->image_path]) }}"
                                        alt="{{ $cat->image_alt ?? $cat->name }}" width="30" height="30"
                                        class="me-2 rounded">
                                    <span class="text-truncate fw-bold">{{ $cat->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    @if (!empty($user_name))
                        @if ($product->can_download == true)
                            <p class="note mt-3">{{ __('message.downloadNote') }}</p>
                            <div class="down">
                                <form action="{{ route('downloadProduct') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="src_product" value="{{ $product->product_file_path }}">
                                    <input type="hidden" name="zip_file_name" value="{{ $product->product_name }}">
                                    <button type="submit" class="btn-down">
                                        <img src="{{ asset('images/download.png') }}" alt="">
                                    </button>
                                </form>
                            </div>
                        @endif
                        @if ($product->can_download == false)
                            <div class="add-cart">
                                <form action="{{ route('cart.add') }}" method="POST" class="form-add-cart"
                                    id="formAddToCart">
                                    @csrf
                                    <input type="hidden" name="product_slug" value="{{ $product->product_slug }}">
                                    <button type="submit" id="addTocart">{{ __('message.addToCartBtn') }}</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="add-cart">
                            <form class="form-add-cart" action="#">
                                <button type="button" id="addTocartNoLogin" onclick="openModalLoginRegis()">{{ __('message.addToCartBtn') }}</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @include('component.noticationNoData.loading.index', [])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'add_to_cart_failed',
            'type' => 'error',
        ])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'error',
            'type' => 'error',
        ])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'add_to_cart_success',
            'type' => 'success',
        ])
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script src="{{ asset('js/zoomer.js') }}"></script>
    <script>
        window.productImages = @json($product->product_images).map(image => {
            return {
                ...image,
                product_image_path: routeGetFileTemplate.replace(":filename", image.product_image_path)
            };
        });
    </script>
    <script src="{{ asset('js/product-image.js') }}"></script>
@endpush
