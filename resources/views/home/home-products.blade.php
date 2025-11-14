@push('css')
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush

<div class="products">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">
                @foreach (collect($products)->chunk(4) as $chunk)
                    <div class="d-flex flex-md-nowrap @if(count($products) == 4) justify-content-between justify-content-center @endif flex-wrap list">
                        @foreach ($chunk as $product)
                            @include('component.product.product-item', [
                                'product' => $product,
                            ])
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
