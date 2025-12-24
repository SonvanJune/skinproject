@extends('layout')

@section('title', __('message.checkout'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush

@section('content')
    <div class="checkout">
        @if (!empty($user_name))
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ __('message.checkout') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ __('message.checkout') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @isset($cart)
                @if (count($cart->products) > 0)
                    <div class="container my-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2 class="text-center">{{ __('message.listProduct') }}</h2>
                                        <div class="product-list">
                                            <ul class="list-group">
                                                @foreach ($cart->products as $product)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                                            alt="{{ $product->post_image_alt }}">
                                                        <span>{{ $product->product_name }}</span>
                                                        @if (session('typeCoupon'))
                                                            @if (session('typeCoupon') == 'couponProduct' && session('productCouponId') == $product->product_id)
                                                                <span>${{ session('priceAfterUseCouponForProduct') }}<p
                                                                        class="textUnderline">${{ $product->product_price }}
                                                                    </p>
                                                                </span>
                                                            @else
                                                                @if ($product->product_price != $product->product_price_sale)
                                                                    <span>${{ $product->product_price_sale }}<p
                                                                            class="textUnderline">
                                                                            ${{ $product->product_price }}
                                                                        </p>
                                                                    </span>
                                                                @else
                                                                    <span>${{ $product->product_price }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($product->product_price != $product->product_price_sale)
                                                                <span>${{ $product->product_price_sale }}<p
                                                                        class="textUnderline">{{ $product->product_price }}$
                                                                    </p>
                                                                </span>
                                                            @else
                                                                <span>${{ $product->product_price }}</span>
                                                            @endif
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @if (session('priceCoupon'))
                                            <h5 class="mt-3">{{ __('message.totalPrice') }}:
                                                <strong>${{ session('priceCoupon') }}</strong>
                                            </h5>
                                        @else
                                            <h5 class="mt-3">{{ __('message.totalPrice') }}:
                                                <strong>${{ $cart->price }}</strong>
                                            </h5>
                                        @endif
                                        <div class="mt-3">
                                            <div class="title-coupon d-flex">
                                                <h5>{{ __('message.couponTitle') }}</h5>
                                                <div class="small text-danger">
                                                    {{ __('message.noteOfCoupon') }}
                                                </div>
                                            </div>

                                            @if (session('coupon'))
                                                @if (session('coupon')->coupon_price)
                                                    @if (session('typeCoupon') == 'couponProduct')
                                                        <input type="text" class="form-control"
                                                            value="{{ session('coupon')->coupon_code . ' ' . '-$' . session('coupon')->coupon_price . ' ' . __('message.for') . ' ' . session('coupon')->product->product_name }}"
                                                            disabled>
                                                    @else
                                                        <input type="text" class="form-control"
                                                            value="{{ session('coupon')->coupon_code . ' ' . '-$' . session('coupon')->coupon_price . ' ' . __('message.forOrder') }}"
                                                            disabled>
                                                    @endif
                                                @else
                                                    @if (session('typeCoupon') == 'couponProduct')
                                                        <input type="text" class="form-control"
                                                            value="{{ session('coupon')->coupon_code . ' ' . -session('coupon')->coupon_per_hundred . '%' . ' ' . __('message.for') . ' ' . session('coupon')->product->product_name }}"
                                                            disabled>
                                                    @else
                                                        <input type="text" class="form-control"
                                                            value="{{ session('coupon')->coupon_code . ' ' . -session('coupon')->coupon_per_hundred . '%' . ' ' . __('message.forOrder') }}"
                                                            disabled>
                                                    @endif
                                                @endif
                                                <a class="btn btn-warning mt-3"
                                                    href="{{ route('checkout') }}">{{ __('message.cancleCouponButton') }}</a>
                                            @else
                                                <form action="{{ route('coupon.apply') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" name="coupon_code" />
                                                        <input type="hidden" name="priceCart" value="{{ $cart->price }}">
                                                    </div>
                                                    <button class="btn btn-success"
                                                        type="submit">{{ __('message.applyCouponButton') }}</button>
                                                </form>
                                            @endif

                                            <div class="mt-4 border-top pt-3">
                                                <div class="d-flex justify-content-between">
                                                    @if (session('priceCoupon'))
                                                        <span>{{ __('message.totalPrice') }}</span>
                                                        <strong>${{ session('priceCoupon') }}</strong>
                                                    @else
                                                        <span>{{ __('message.totalPrice') }}</span>
                                                        <strong>${{ $cart->price }}</strong>
                                                    @endif
                                                </div>

                                                <div class="d-flex justify-content-between mt-2">
                                                    <span>
                                                        {{ __('message.VAT') }}
                                                        ({{ $vatText }})
                                                    </span>
                                                    <strong>+ ${{$cart->priceOfVat}}</strong>
                                                </div>

                                                <hr>

                                                <div class="d-flex justify-content-between fs-5">
                                                    <span>{{ __('message.totalPrice') }}</span>
                                                    <strong class="text-success">
                                                        ${{$totalPriceAndVat}}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 checkout-payment">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2 class="text-center">{{ __('message.selectPaymentMethod') }}</h2>
                                        <div class="form-group">
                                            <label for="payment-method">{{ __('message.paymentMethod') }}</label>
                                            <select class="form-control" id="payment-method" required>
                                                <option value="" disabled selected>
                                                    {{ __('message.selectPaymentMethod') }}
                                                </option>
                                                {{-- <option value="credit-card">Thẻ Tín Dụng</option> --}}
                                                <option value="paypal">{{ __('message.paypal') }}</option>
                                                {{-- <option value="bank-transfer">Chuyển Khoản Ngân Hàng</option> --}}
                                            </select>
                                        </div>

                                        <div id="payment-details" class="payment-details">
                                            {{-- <div id="credit-card-details" class="mb-3">
                                                <h5>Thông Tin Thẻ Tín Dụng</h5>
                                                <div class="form-group">
                                                    <label for="card-number">Số Thẻ</label>
                                                    <input type="text" class="form-control" id="card-number"
                                                        placeholder="Nhập số thẻ">
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="expiry">Ngày Hết Hạn</label>
                                                        <input type="text" class="form-control" id="expiry"
                                                            placeholder="MM/YY">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="cvv">CVV</label>
                                                        <input type="text" class="form-control" id="cvv"
                                                            placeholder="CVV">
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-checkout">Thanh
                                                    Toán</button>
                                            </div> --}}

                                            <div id="paypal-details" class="mb-3" style="display: none;">
                                                <form method="POST" action="{{ route('paypal.payment') }}">
                                                    @csrf
                                                    @if (session('priceCoupon'))
                                                        <input type="hidden" name="price"
                                                            value="{{$totalPriceAndVat}}">
                                                        <input type="hidden" name="coupon_id"
                                                            value="{{ session('coupon')->coupon_id }}">
                                                    @else
                                                        <input type="hidden" name="price" value="{{ $totalPriceAndVat }}">
                                                    @endif
                                                    @if (session('typeCoupon') == 'couponProduct')
                                                        <input type="hidden" name="type_coupon" value="couponProduct">
                                                    @endif
                                                    <input type="hidden" name="vat_detail" value="{{$vatText}}">
                                                    <input type="hidden" name="vat_value" value="{{$cart->priceOfVat}}"">
                                                    <input type="hidden" name="cart_id" value="{{ $cart->cart_id }}">
                                                    <input type="hidden" name="order_payment" value=1>
                                                    <button
                                                        class="btn btn-checkout d-flex justify-content-center align-items-center"
                                                        type="submit" id="paybut">
                                                        <img src="{{ asset('images/paypal.png') }}" alt="PayPal logo"
                                                            class="paypal-img">
                                                    </button>
                                                </form>
                                            </div>

                                            {{-- <div id="bank-transfer-details" class="mb-3" style="display: none;">
                                                <h5>Thông Tin Chuyển Khoản</h5>
                                                <div class="form-group">
                                                    <label for="bank-account">Số Tài Khoản Ngân Hàng</label>
                                                    <input type="text" class="form-control" id="bank-account"
                                                        placeholder="Nhập số tài khoản">
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank-name">Tên Ngân Hàng</label>
                                                    <input type="text" class="form-control" id="bank-name"
                                                        placeholder="Nhập tên ngân hàng">
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-checkout">Thanh
                                                    Toán</button>
                                            </div> --}}
                                        </div>
                                        <p class="note">{{ __('message.sendEmailNote') }}</p>
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

    @include('component.noticationNoData.loading.index', [])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'success',
        'type' => 'success',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'error',
        'type' => 'error',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'cancel',
        'type' => 'warning',
    ])
@endsection

@push('js')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
