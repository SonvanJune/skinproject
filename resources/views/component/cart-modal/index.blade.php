@push('css')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush
<div id="modal" class="cart-modal">
    <div class="modal-content position-relative">
        <button type="button" class="close" onclick="closeCartModal()" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        <div class="container mt-1" id="cartModalContainer" data-url="{{ route('cart.modal.items') }}">
            @include('component.cart-modal.cart-items', ['cart' => $cart ?? null, 'categories' => $categories ?? []])
        </div>
    </div>
</div>
@push('js')
    <script>
        window.cartModalItemsUrl = @json(route('cart.modal.items'));
    </script>
    <script src="{{ asset('js/modal.js') }}"></script>
@endpush

