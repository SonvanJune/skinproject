@push('css')
    <link rel="stylesheet" href="{{ asset('css/sidebar-category.css') }}">
@endpush

<div class="sidebar-caterory">
    <div class="search">
        <div class="title">{{ __('message.accessProduct') }}</div>
    </div>

    @if(!empty($access_list))
        <div class="recent-products mt-2 d-flex flex-wrap gap-2">
            @foreach($access_list as $product)
                <a href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}"
                   class="badge text-truncate text-decoration-none"
                   style="max-width: 100%; padding: 0.5em 0.8em;">
                    {{ $product->product_name }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="category-list">
        <div class="title">{{ __('message.productCatalog') }}</div>

        <div class="list">
            @include('component.sidebar.sidebar-category-list', [
                'categories' => $categories,
                'category_select' => $category_detail
            ])
        </div>
    </div>
</div>

@push('js')
    {{-- Import js if have --}}
@endpush
