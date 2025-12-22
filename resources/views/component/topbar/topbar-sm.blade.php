<div class="bottom-bar">
    <a class="col-sm-2 btbar-item" href="{{ url(App::getLocale() . '/blog') }}">
        <i class="bi bi-file-text-fill"></i>
        <span>{{ __('message.blog') }}</span>
    </a>
    @if (!empty($user_name))
        <a class="col-sm-2 btbar-item" href="{{ url(App::getLocale() . '/account') }}">
            <i class="bi bi-person-fill"></i>
            <span>{{ $user_name }}</span>
        </a>
    @else
        <a class="col-sm-2 btbar-item" href="{{ route('login') }}">
            <i class="bi bi-person-fill"></i>
            <span>{{ __('message.noLogin') }}</span>
        </a>
    @endif
    <a class="col-sm-2 btbar-item center" href="{{ url(App::getLocale() . '/') }}">
        <i class="bi bi-house-fill"></i>
    </a>
    <a class="col-sm-2 btbar-item" href="{{ url(App::getLocale() . '/cart') }}">
        <i class="bi bi-cart-fill">
            @isset($cart)
                <div class="quantity-item"><span>{{ count($cart->products) }}</span></div>
            @else
                <div class="quantity-item"><span>0</span></div>
            @endisset
        </i>
        <span>{{ __('message.cart') }}</span>
    </a>
    <a class="col-sm-2 btbar-item" href="{{ route('wishlist') }}">
        <i class="bi bi-bag-heart-fill"></i>
        <span>{{ __('message.list') }}</span>
    </a>
</div>

<!-- Sidebar -->
<div class="custom-sidebar" id="custom-sidebar">
    <div id="sidebar" class="sidebar">
        <div class="container">
            <button id="closeSidebar" class="btn close-btn">&times;</button>
            <div class="sidebar-header">
                <div class="row mb-4 align-items-center">
                    <div class="col-auto">
                        <img src="/images/profile.png" alt="User Avatar" class="rounded-circle img-fluid"
                            style="width: 80px; height: 80px;">
                    </div>
                    <div class="col">
                        @if (!empty($user_name))
                            <h5 class="mb-0">{{ $user_name }}</h5>
                        @else
                            <a href="{{ route('login') }}">
                                <h5 class="mb-0">{{ __('message.noLogin') }}</h5>
                            </a>
                        @endif
                    </div>
                    <div class="col">
                        @include('component.language-modal.button')
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <div class="b-group">
                    <i class="bi bi-house-heart-fill"></i>
                    <a class="btn b-home active" type="button"
                        href="{{ LaravelLocalization::localizeUrl(url('/')) }}">
                        {{ __('message.home') }}
                    </a>
                </div>
                @isset($categories)
                    @foreach ($categories as $category)
                        <div class="cate">
                            @if (!empty($category->children))
                                <div class="b-group">
                                    <i class="bi bi-menu-down"></i>
                                    <a class="btn b-item" type="button">
                                        <span>{{ $category->name }}</span>
                                        <i class="bi bi-caret-right-fill"></i>
                                    </a>
                                </div>
                                <div class="cate-menu" style="top: 100% ; left: 30%">
                                    @foreach ($category->children as $child)
                                        @include('component.topbar.topbar-item-expand', [
                                            'category' => $child,
                                        ])
                                    @endforeach
                                </div>
                            @else
                                <div class="cate">
                                    <div class="b-group">
                                        <i class="bi bi-menu-down"></i>
                                        <a class="btn b-item" type="button"
                                            href="{{ LaravelLocalization::localizeUrl(url('/product-category' . '/' . $category->slug)) }}">
                                            <span>{{ $category->name }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endisset
                <div class="b-group">
                    <i class="bi bi-telephone-fill"></i>
                    <a class="btn b-item" type="button" href="{{ route('contact') }}">
                        {{ __('message.contactUs') }}
                    </a>
                </div>
                <div class="b-group">
                    <i class="bi bi-question-circle"></i>
                    <a class="btn b-item" type="button" href="{{ route('helps') }}">
                        {{ __('message.helpPage') }}
                    </a>
                </div>
                <div class="b-group">
                    <i class="bi bi-file-earmark-text"></i>
                    <a class="btn b-item" type="button" href="{{ route('policies') }}">
                        {{ __('message.policyTitle') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="sidebar-footer" id="sidebar-footer">
        <div class="b-group">
            @if (!empty($user_name))
                <i class="bi bi-box-arrow-in-right"></i>
                <a class="btn b-item" type="button" href="{{ url(App::getLocale() . '/logout') }}">
                    {{ __('message.logout') }}
                </a>
            @else
                <i class="bi bi-box-arrow-in-left"></i>
                <a class="btn b-item" type="button" href="{{ url(App::getLocale() . '/login') }}">
                    {{ __('message.login') }}
                </a>
            @endif
        </div>
    </div>
</div>

<div id="mainContent" class="main-content">
    <div class="topbar-sm-bar">
        <button id="openSidebar" class="topbar-sm-btn">☰</button>

        <div class="topbar-sm-brand" onclick="window.location.href='{{ url(App::getLocale() . '/') }}'">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="topbar-sm-logo">
            <span class="topbar-sm-title">{{ __('message.webName') }}</span>
        </div>

        <button id="topbarsm-toggleSearch" class="topbar-sm-search-btn btn">
            <i id="topbarsm-search-icon" class="bi bi-search"></i>
        </button>
    </div>
</div>

<div id="topbarsm-searchBar" class="topbar-sm-search-wrapper"
    data-topbar-sm-search-url="{{ route('search.page') }}">
    <div class="topbar-sm-search-box d-flex">
        @isset($searchQuery)
            <input type="search" id="topbarsm-searchInput" class="topbar-sm-search-input form-control"
                placeholder="{{ __('message.searchAnything') }}" value="{{ $searchQuery }}">
            <button id="topbarsm-searchBtn" class="btn btn-primary ms-2" onclick="topbarsmGoToSearchPage()">
                {{ __('message.next') }}
            </button>
        @else
            <input type="search" id="topbarsm-searchInput" class="topbar-sm-search-input form-control"
                placeholder="{{ __('message.searchAnything') }}">
            <button id="topbarsm-searchBtn" class="btn btn-primary ms-2" onclick="topbarsmGoToSearchPage()">
                {{ __('message.next') }}
            </button>
        @endisset
    </div>
</div>

@push('js')
    <script src="{{ asset('js/custom-sidebar-sm.js') }}"></script>
@endpush
