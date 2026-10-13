@push('css')
    <link rel="stylesheet" href="{{ asset('css/sidebar-category.css') }}">
@endpush

<div class="sidebar-caterory">
    {{-- Access Product Section --}}
    <div class="sidebar-section access-product-section mb-4">
        <div class="title sidebar-toggle-header d-flex align-items-center justify-content-between"
             role="button"
             tabindex="0"
             onclick="window.toggleSidebarSection('access-product-content', this);"
             onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.toggleSidebarSection('access-product-content', this);}">
            <span>{{ __('message.accessProduct') }}</span>
            <i class="fa-solid fa-chevron-down section-toggle-icon"></i>
        </div>
        <div id="access-product-content" class="sidebar-section-content">
            @if(!empty($access_list) && count($access_list) > 0)
                <div class="recent-products mt-2 d-flex flex-wrap gap-2">
                    @foreach($access_list as $product)
                        <a href="{{ url(App::getLocale() . '/product' . '/' . $product->product_slug) }}"
                           class="badge text-truncate text-decoration-none"
                           style="max-width: 100%; padding: 0.5em 0.8em;">
                            {{ $product->product_name }}
                        </a>
                    @endforeach
                </div>
            @else
                <div class="access-empty-note py-2 px-1">
                    <i class="fa-regular fa-clock me-1 opacity-50"></i>{{ __('message.noRecentProducts') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Product Categories Section --}}
    <div class="sidebar-section category-list">
        <div class="title sidebar-toggle-header d-flex align-items-center justify-content-between"
             role="button"
             tabindex="0"
             onclick="window.toggleSidebarSection('category-tree-content', this);"
             onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.toggleSidebarSection('category-tree-content', this);}">
            <span>{{ __('message.productCatalog') }}</span>
            <i class="fa-solid fa-chevron-down section-toggle-icon"></i>
        </div>

        <div id="category-tree-content" class="sidebar-section-content">
            <div class="list">
                @include('component.sidebar.sidebar-category-list', [
                    'categories' => $categories,
                    'category_select' => $category_select ?? ($category_detail ?? null)
                ])
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof window.toggleCategoryBranch !== 'function') {
        window.toggleCategoryBranch = function(btn) {
            var branch = btn.closest('.category-branch');
            if (!branch) return;
            var childrenList = branch.querySelector(':scope > .children-list');
            if (!childrenList) return;

            var isNowCollapsed = branch.classList.toggle('is-collapsed');
            btn.setAttribute('aria-expanded', !isNowCollapsed);

            if (isNowCollapsed) {
                childrenList.style.maxHeight = childrenList.scrollHeight + 'px';
                childrenList.offsetHeight;
                childrenList.style.overflow = 'hidden';
                childrenList.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                childrenList.style.maxHeight = '0px';
                childrenList.style.opacity = '0';
                setTimeout(function() {
                    if (branch.classList.contains('is-collapsed')) {
                        childrenList.style.display = 'none';
                    }
                }, 250);
            } else {
                childrenList.style.display = 'flex';
                childrenList.style.overflow = 'hidden';
                childrenList.style.maxHeight = '0px';
                childrenList.style.opacity = '0';
                childrenList.offsetHeight;
                childrenList.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                childrenList.style.maxHeight = childrenList.scrollHeight + 'px';
                childrenList.style.opacity = '1';
                setTimeout(function() {
                    if (!branch.classList.contains('is-collapsed')) {
                        childrenList.style.maxHeight = '';
                        childrenList.style.overflow = '';
                        childrenList.style.opacity = '';
                        childrenList.style.transition = '';
                    }
                }, 250);
            }
        };
    }

    if (typeof window.toggleSidebarSection !== 'function') {
        window.toggleSidebarSection = function(contentId, headerEl) {
            var content = document.getElementById(contentId);
            if (!content) return;

            var isNowCollapsed = content.classList.toggle('is-collapsed');
            headerEl.classList.toggle('is-collapsed', isNowCollapsed);
            headerEl.setAttribute('aria-expanded', !isNowCollapsed);

            if (isNowCollapsed) {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.offsetHeight;
                content.style.overflow = 'hidden';
                content.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                setTimeout(function() {
                    if (content.classList.contains('is-collapsed')) {
                        content.style.display = 'none';
                    }
                }, 250);
            } else {
                content.style.display = '';
                content.style.overflow = 'hidden';
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                content.offsetHeight;
                content.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                setTimeout(function() {
                    if (!content.classList.contains('is-collapsed')) {
                        content.style.maxHeight = '';
                        content.style.overflow = '';
                        content.style.opacity = '';
                        content.style.transition = '';
                    }
                }, 250);
            }
        };
    }
</script>
