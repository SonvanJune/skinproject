@extends('layout')

@section('title', __('message.dailyOffer'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/wish-list.css') }}">
@endpush

@section('content')
    <div class="wish-list">
        <div class="wishlist-container">
            <div class="wishlist-banner" style="background-image: url('{{ asset('images/breadcamb.jpg') }}')">
                <div class="container">
                    <h2>{{ __('message.dailyOffer') }}</h2>
                    <p class="breadcrumb">
                        <a href="{{ route('home') }}">{{ __('message.home') }}</a> <span>></span>
                        <span>{{ __('message.dailyOffer') }}</span>
                    </p>
                    @isset($sales)
                        @if (count($sales->data) > 0)
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    @if ($sales->current_page != 1)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ url(App::getLocale() . '/popular-products' . '?page=' . $sales->current_page - 1) }}"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    @endif
                                    @for ($i = 1; $i <= $sales->last_page; $i++)
                                        @if ($sales->current_page == $i)
                                            <li class="page-item active"><a class="page-link"
                                                    href="{{ url(App::getLocale() . '/popular-products' . '?page=' . $i) }}">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ url(App::getLocale() . '/popular-products' . '?page=' . $i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                    @if ($sales->current_page != $sales->last_page)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ url(App::getLocale() . '/popular-products' . '?page=' . $sales->current_page + 1) }}"
                                                aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    @endisset
                </div>
            </div>

            <div class="container">
                <div class="grid-container mt-5">
                    @isset($sales)
                        @if (count($sales->data) > 0)
                            @foreach ($sales->data as $product)
                                <div class="wishlist-item">
                                    <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                        alt="{{ $product->post_image_alt }}" />

                                    <div class="item-details">
                                        <h6 class="item-title">{{ $product->product_name }}</h6>
                                        @if ($product->product_price != $product->product_price_sale)
                                            <p class="item-price"><span>${{ $product->product_price }}</span>
                                                ${{ $product->product_price_sale }}</p>
                                        @else
                                            <p class="item-price">${{ $product->product_price }}</p>
                                        @endif
                                        <div class="item-action">
                                            <a href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}"
                                                class="see-more-btn icon-container">
                                                <i class="bi bi-eye"></i>
                                                <span class="tooltip">{{ __('message.viewDetailPost') }}</span>
                                            </a>
                                            @if (!empty($user_name))
                                                @if ($product->can_download == true)
                                                    <form action="{{ route('downloadProduct') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="src_product"
                                                            value="{{ $product->product_file_path }}">
                                                        <input type="hidden" name="zip_file_name"
                                                            value="{{ $product->product_name }}">
                                                        <button type="submit" class="see-more-btn icon-container"><i
                                                                class="bi bi-arrow-down-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($product->can_download == false)
                                                    <form action="{{ route('cart.add') }}" method="POST" id="formAddToCart">
                                                        @csrf
                                                        <input type="hidden" name="product_slug"
                                                            value="{{ $product->product_slug }}">
                                                        <button type="submit" class="see-more-btn icon-container"
                                                            id="addTocart" onclick="addToCart()">
                                                            <i class="bi bi-cart-plus"></i>
                                                            <span class="tooltip">{{ __('message.addToCartBtn') }}</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <button type="button" class="see-more-btn icon-container" id="addTocartNoLogin"
                                                    onclick="openModalLoginRegis()">
                                                    <i class="bi bi-cart-plus"></i>
                                                    <span class="tooltip">{{ __('message.addToCartBtn') }}</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @include('component.noticationNoData.list.index', [])
                        @endif
                    @else
                        @include('component.noticationNoData.list.index', [])
                    @endisset
                </div>
            </div>
        </div>
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
    <script src="{{ asset('js/wishlist.js') }}"></script>
@endpush
