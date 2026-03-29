<nav class="kb-navbar">
    <div class="kb-navbar-in">
        <!-- Logo -->
        <div class="kb-logo">
            <div class="logo">
                <a class="navbar-brand"><img src="{{ asset('images/logo.png') }}" class="img-fluid"></a>
            </div>
            <a href="#" class="logo-title">
                {{ __('message.webName') }}
            </a>
        </div>

        <!-- Menu center -->
        <ul class="kb-nav-menu">
            <li class="kb-nav-item">
                <a class="kb-nav-item-title" href="{{ url(App::getLocale() . '/') }}">
                    {{ __('message.home') }}
                </a>
            </li>

            <li class="kb-nav-item kb-has-mega">
                <a class="kb-nav-item-title" href="#">
                    {{ __('message.explore') }}
                </a>
                <div class="kb-mega-menu">
                    <div class="kb-mega-content">
                        <!-- LEFT: flex ngang -->
                        <div class="kb-mega-left kb-limit">
                            @isset($categories)
                                @foreach ($categories as $category)
                                    <div class="kb-menu-group">
                                        <div class="kb-menu-title"
                                            onclick="window.location.href='{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}'">
                                            <h4>{{ $category->name }}</h4>
                                        </div>
                                        <ul class="kb-menu-list">
                                            @if (!empty($category->children))
                                                @foreach ($category->children as $child)
                                                    <li
                                                        data-img="{{ route('get.file', ['filename' => $child->image_path]) }}">
                                                        <a
                                                            href="{{ url(App::getLocale() . '/product-category' . '/' . $child->slug) }}">{{ $child->name }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                @endforeach
                            @endisset
                        </div>

                        @isset($categories)
                            <div class="kb-overlay">
                                <a class="kb-view-more"
                                    href="{{ url(App::getLocale() . '/product-category' . '/' . $categories[0]->slug) }}">{{ __('message.all') }}
                                    <i class="bi bi-arrow-right"></i></a>
                            </div>
                        @endisset

                        <!-- RIGHT: image -->
                        <div class="kb-mega-right">
                            <img id="kb-preview-img" src="{{ asset('images/logo.png') }}" alt="preview" />
                        </div>
                    </div>
                </div>
            </li>

            <li class="kb-nav-item">
                <a class="kb-nav-item-title" href="{{ url(App::getLocale() . '/blog') }}">
                    {{ __('message.blog') }}
                </a>
            </li>

            <li class="kb-nav-item kb-has-mega">
                <a class="kb-nav-item-title" href="#">
                    {{ __('message.support') }}
                </a>
                <div class="kb-mega-menu">
                    <div class="kb-mega-content kb-simple-menu">
                        <a href="{{ url(App::getLocale() . '/helps') }}"
                            class="kb-simple-link">{{ __('message.helpPage') }}</a>
                        <a href="{{ url(App::getLocale() . '/contact-us') }}"
                            class="kb-simple-link">{{ __('message.contactUs') }}</a>
                    </div>
                </div>
            </li>

            <li class="kb-nav-item kb-has-mega search-item">
                <a class="kb-nav-item-title" href="#">
                    <i class="bi bi-search"></i>
                </a>

                <!-- Mega menu -->
                <div class="kb-mega-menu search-mega">
                    <div class="kb-mega-content search-container">

                        <div class="search-left">
                            <div class="search-bar">
                                @isset($searchQuery)
                                    <input type="text" class="form-control form-input"
                                        placeholder="{{ __('message.searchAnything') }}" id="searchInput"
                                        oninput="searchApi()" value="{{ $searchQuery }}">
                                    <button class="left-pan" onclick="goToSearchPage()"><i
                                            class="fa fa-search"></i></button>
                                @else
                                    <input type="text" class="form-control form-input"
                                        placeholder="{{ __('message.searchAnything') }}" id="searchInput"
                                        oninput="searchApi()">
                                    <button class="left-pan" onclick="goToSearchPage()"><i
                                            class="fa fa-search"></i></button>
                                @endisset
                            </div>
                        </div>

                        <div class="search-right">
                            @include('component.search-modal.index')
                        </div>

                    </div>
                </div>
            </li>
        </ul>

        <!-- Right side -->
        <ul class="kb-nav-right">
            <li>@include('component.language-modal.button')</li>
            <li>
                <div class="icon cart" id="openModal" onclick="openCartModal()">
                    {{ __('message.cart') }}
                    <i class="fa-solid fa-cart-shopping">
                        @isset($cart)
                            <div class="quantity-item">{{ count($cart->products) }}</div>
                        @else
                            <div class="quantity-item">0</div>
                        @endisset
                    </i>
                </div>
            </li>
            @if (!empty($user_name))
                <li>
                    <a href="{{ url(App::getLocale() . '/account') }}">{{ $user_name }}</a>
                </li>
                <li>
                    <a href="{{ url(App::getLocale() . '/logout') }}"><i
                            class="fa-solid fa-arrow-right-from-bracket"></i></a>
                </li>
            @endif
            @if (empty($user_name))
                <li><a href="{{ url(App::getLocale() . '/login') }}">{{ __('message.login') }}</a></li>
            @endif
        </ul>
    </div>
</nav>

<div class="kb-fixed-bar">
    <a href="{{ url(App::getLocale() . '/wishlist') }}" class="kb-bar-item">
        <i class="bi bi-heart-fill icon"></i>
        <span class="text">Wishlist</span>
    </a>

    <a href="{{ url(App::getLocale() . '/sales') }}" class="kb-bar-item">
        <i class="bi bi-tags-fill icon"></i>
        <span class="text">Sale</span>
    </a>

    <a href="{{ url(App::getLocale() . '/new-products') }}" class="kb-bar-item">
        <i class="bi bi-stars icon"></i>
        <span class="text">New</span>
    </a>
</div>
