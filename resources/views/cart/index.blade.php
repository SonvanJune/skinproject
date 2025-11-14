@extends('layout')

@section('title', 'Cart Detail')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
    <div class="cart-page">
        @if (!empty($user_name))
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ __('message.cart') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="{{ url(App::getLocale() . '/') }}"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="{{ url(App::getLocale() . '/cart') }}">{{ __('message.cart') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @isset($cart)
                @if (count($cart->products) > 0)
                    <div class="container mt-5">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('message.productImageColumn') }}</th>
                                        <th scope="col">{{ __('message.productNameColumn') }}</th>
                                        <th scope="col">{{ __('message.productPriceColumn') }}</th>
                                        <th scope="col">{{ __('message.productPriceSaleColumn') }}</th>
                                        <th scope="col">{{ __('message.productTotalPriceColumn') }}</th>
                                        <th scope="col">{{ __('message.productActionColumn') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart->products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                                    alt="{{ $product->post_image_alt }}">
                                            </td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>${{ $product->product_price }}</td>
                                            @if ($product->product_price != $product->product_price_sale)
                                                <td>${{ $product->product_price_sale }}</td>
                                            @else
                                                <td>No</td>
                                            @endif
                                            @if ($product->product_price != $product->product_price_sale)
                                                <td>${{ $product->product_price_sale }}</td>
                                            @else
                                                <td>${{ $product->product_price }}</td>
                                            @endif
                                            <td>
                                                <form action="{{ route('cart.delete') }}" method="POST">
                                                    @csrf
                                                    <input type="text" name="cart_id" value="{{ $cart->cart_id }}" hidden>
                                                    <input type="text" name="product_slug"
                                                        value="{{ $product->product_slug }}" hidden>
                                                    <button
                                                        class="btn btn-danger btn-sm">{{ __('message.deleteButton') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a class="btn btn-secondary btn-block"
                                    href="{{ url(App::getLocale() . '/product-category' . '/' . $categories[0]->slug) }}">{{ __('message.continueShoppingButton') }}</a>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ __('message.totalCart') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>{{ __('message.totalPrice') }}:</strong> {{ $cart->price }}$</p>
                                        <a href="{{ url(App::getLocale() . '/checkout') }}"
                                            class="btn btn-primary btn-block">{{ __('message.checkoutButton') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @include('component.noticationNoData.cart.index', [])
                @endif
            @else
                @include('component.noticationNoData.cart.index', [])
            @endisset
        @else
            @include('component.noticationNoData.user.index', [])
        @endif
    </div>
    @include('component.noticationNoData.notification.index', ['notification' => 'delete_failed' , 'type' => 'error'])
    @include('component.noticationNoData.notification.index', ['notification' => 'delete_success', 'type' => 'success'])
@endsection

@push('js')
    {{-- Import js if have --}}
@endpush
