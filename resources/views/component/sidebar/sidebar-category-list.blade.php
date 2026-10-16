@foreach ($categories as $category)
    @php
        $hasChildren = !empty($category->children) && count($category->children) > 0;
        $isActive = isset($category_select) && $category_select && ($category_select->slug == $category->slug);
    @endphp
    <div class="category-branch {{ $isActive ? 'active-branch' : '' }}" data-category-slug="{{ $category->slug }}">
        <a class="item-category @if ($isActive) active @endif" href="{{ $category->url }}">
            <div class="radio-form">
                <div class="radio"></div>
                <div class="name" title="{{ $category->name }}">{{ $category->name }}</div>
            </div>
            <div class="item-meta">
                <div class="item-count">
                    <span>{{ $category->product_count ?? 0 }}</span>
                </div>
                @if ($hasChildren)
                    <span class="btn-tree-toggle"
                          role="button"
                          tabindex="0"
                          title="Toggle {{ $category->name }}"
                          aria-label="Toggle {{ $category->name }}"
                          aria-expanded="true"
                          onclick="event.preventDefault(); event.stopPropagation(); window.toggleCategoryBranch(this);"
                          onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();event.stopPropagation();window.toggleCategoryBranch(this);}">
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                @endif
            </div>
        </a>
        @if ($hasChildren)
            <div class="children-list">
                @include('component.sidebar.sidebar-category-list', [
                    'categories' => $category->children,
                    'category_select' => $category_select,
                ])
            </div>
        @endif
    </div>
@endforeach
