<div class="cate">
    @if (!empty($category->children))
        <a class="btn b-item" type="button">
            <span>{{ $category->name }}</span>
            <i class="bi bi-caret-right-fill"></i>
        </a>
        <div class="cate-menu" style="top: 1% ; left: 100%">
            @foreach ($category->children as $child)
                @include('component.topbar.topbar-item-expand', ['category' => $child])
            @endforeach
        </div>
    @else
        <div class="cate">
            <a class="btn b-item" type="button" href="{{url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                <span>{{ $category->name }}</span>
            </a>
        </div>
    @endif
</div>
