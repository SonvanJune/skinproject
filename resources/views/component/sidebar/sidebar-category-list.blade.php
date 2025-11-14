@foreach ($categories as $category)
    <a class="item-category @if ($category_select->slug == $category->slug) active @endif" href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
        <div class="radio-form">
            <div class="radio"></div>
            <div class="name">{{ $category->name }}</div>
        </div>
        <div class="item-count">
            <span>{{ $category->product_count}}</span>
        </div>
    </a>
    <div class="children-list">
        @if (!empty($category->children))
            @include('component.sidebar.sidebar-category-list', [
                'categories' => $category->children,
                'category_select' => $category_select,
            ])
        @endif
    </div>
@endforeach
