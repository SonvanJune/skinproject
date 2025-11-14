@push('css')
    <link rel="stylesheet" href="{{ asset('css/sidebar-category.css') }}">
@endpush

<div class="sidebar-caterory">
    <div class="search">
        <div class="title">{{ __('message.accessProduct') }}</div>
    </div>

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
