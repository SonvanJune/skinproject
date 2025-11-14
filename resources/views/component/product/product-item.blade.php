@push('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

<div class=" d-flex flex-column it-p">
    <a class="product" href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}">
        @if ($product->product_price != $product->product_price_sale)
            <div class="sale"><img src="{{ asset('images/sale.png') }}" alt="sale"></div>
        @endif
        <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
            alt="{{ $product->post_image_alt }}">
        <div class="view">{{ __('message.quickView') }} <i class="bi bi-eye-fill"></i></div>

        @if (!empty($user_name))
            <form action="{{ route('wishlist.add') }}" method="post">
                @csrf
                <input type="text" value="{{ $product->product_id }}" hidden name="product_id">
                @if ($product->is_like)
                    <button class="like isLike" id="likebtn" onclick="like()"><i
                            class="bi bi-suit-heart-fill"></i></button>
                @else
                    <button class="like" id="likebtn" onclick="like()"><i
                            class="bi bi-suit-heart-fill"></i></button>
                @endif
            </form>
        @endif
    </a>
    <div class="detail">
        <div class="name">
            @if ($product->can_download == true)
                <a href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}">
                    <h3>{{ $product->product_name }}</h3>
                    <span>&nbsp;({{ __('message.hasOwned') }})</span>
                </a>
            @else
                <a href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}">
                    <h3>{{ $product->product_name }}</h3>
                </a>
            @endif
        </div>
        <div class="release-date">
            <i class="bi bi-calendar-event-fill"></i> {{ $product->post_release }}
        </div>
        @if ($product->coupon_code)
            <div class="release-date">
                <i class="bi bi-ticket-fill"></i> {{ $product->coupon_code }}
            </div>
        @endif
        <div class="view {{ isset($fakeView) && $fakeView === true ? 'on' : '' }}">
            <i class="bi bi-eye-fill"></i> {{ $product->product_fake_views }}
        </div>
        @if ($product->product_price != $product->product_price_sale)
            <div class="price"><span class="price-sale">${{ $product->product_price }}</span>
                ${{ $product->product_price_sale }}</div>
        @else
            <div class="price"> ${{ $product->product_price }}</div>
        @endif
        @if (!empty($user_name))
            @if ($product->can_download == false)
                <form action="{{ route('cart.add') }}" method="POST" class="form-add-cart" id="formAddToCart">
                    @csrf
                    <input type="hidden" name="product_slug" value="{{ $product->product_slug }}">
                    <button type="submit" id="addTocart" class="cart"><i
                            class="bi bi-bag-plus"></i>&nbsp;{{ __('message.cart') }}</button>
                </form>
            @endif
            @if ($product->can_download == true)
                <form action="{{ route('downloadProduct') }}" method="post">
                    @csrf
                    <input type="hidden" name="src_product" value="{{ $product->product_file_path }}">
                    <input type="hidden" name="zip_file_name" value="{{ $product->product_name }}">
                    <button type="submit" class="cart"><i class="bi bi-arrow-down-circle"></i></button>
                </form>
            @endif
        @else
            <button type="button" id="addTocartNoLogin" onclick="openModalLoginRegis()" class="cart"><i
                    class="bi bi-bag-plus"></i>&nbsp;{{ __('message.cart') }}</button>
        @endif
    </div>
</div>

@push('js')
    <script>
        function like() {
            document.getElementById('notiSuc').classList.remove('d-none');
        }
    </script>
@endpush
