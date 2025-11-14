@push('css')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush
<div id="modal" class="cart-modal">
    <div class="modal-content">
        <button class="close" onclick="closeCartModal()">&times;</button>
        <div class="container mt-1">
            <div class="cart-header">
                <p>{{ __('message.cart') }}</p>
                <a href="{{ url(App::getLocale() . '/cart') }}">{{ __('message.cartDetail') }}</a>
            </div>
            @isset($cart)
                @if (count($cart->products) > 0)
                    @foreach ($cart->products as $product)
                        <div class="cart-item row">
                            <div class="col-md-3 img">
                                <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}" alt="{{ $product->post_image_alt }}"
                                    class="item-img">
                            </div>
                            <div class="col-md-5">
                                <div class="item-name">{{ $product->product_name }}</div>
                            </div>
                            <div class="col-md-4 item-price">
                                @if ($product->product_price != $product->product_price_sale)
                                    <div class="item-price">${{ $product->product_price_sale }}</div>
                                @else
                                    <div class="item-price">${{ $product->product_price }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-warning text-center mt-5">
                        <p class="alert-heading" style="font-size: 1em;">{{ __('message.emptyCart') }}</p>
                        <a href="{{ url(App::getLocale() . '/product-category' . '/' . $categories[0]->slug) }}"
                            class="btn btn-primary" style="width: 100%; font-size: 1em">{{ __('message.shoppingNow') }}</a>
                    </div>
                @endif
            @else
                @include('component.noticationNoData.cart.index', [])
            @endisset
        </div>
    </div>
</div>
@push('js')
    <script src="{{ asset('js/modal.js') }}"></script>
@endpush
