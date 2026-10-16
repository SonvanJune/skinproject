<div class="cart-header">
    <p>{{ __('message.cart') }}</p>
    <a href="{{ url(App::getLocale() . '/cart') }}">{{ __('message.cartDetail') }}</a>
</div>
@if (isset($cart) && !empty($cart->products) && count($cart->products) > 0)
    <div class="cart-items-list">
        @foreach ($cart->products as $product)
            <div class="cart-item row">
                <div class="col-md-3 img">
                    <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}" alt="{{ $product->post_image_alt }}"
                        class="item-img">
                </div>
                <div class="col-md-5">
                    <div class="item-name" title="{{ $product->product_name }}">{{ $product->product_name }}</div>
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
    </div>
@elseif (isset($cart))
    <div class="alert alert-warning text-center mt-5">
        <p class="alert-heading" style="font-size: 1em;">{{ __('message.emptyCart') }}</p>
        <a href="{{ !empty($categories[0]->url) ? $categories[0]->url : url(App::getLocale() . '/') }}"
            class="btn btn-primary" style="width: 100%; font-size: 1em">{{ __('message.shoppingNow') }}</a>
    </div>
@else
    @include('component.noticationNoData.cart.index', [])
@endif
