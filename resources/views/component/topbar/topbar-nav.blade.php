<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="col-md-8">
            <div class="nav-contact">
                <div class="nc-item">
                    <div class="nc-item-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="nc-item-des">
                        <a href="#">{{ __('message.webEmail') }}</a>
                    </div>
                </div>
                <div class="search-bar">
                    @isset($searchQuery)
                        <input type="text" class="form-control form-input"
                            placeholder="{{ __('message.searchAnything') }}" id="searchInput" oninput="searchApi()"
                            value="{{ $searchQuery }}">
                        <button class="left-pan" onclick="goToSearchPage()"><i class="fa fa-search"></i></button>
                    @else
                        <input type="text" class="form-control form-input"
                            placeholder="{{ __('message.searchAnything') }}" id="searchInput" oninput="searchApi()">
                        <button class="left-pan" onclick="goToSearchPage()"><i class="fa fa-search"></i></button>
                    @endisset
                </div>
            </div>
        </div>
        <div class="col-md-4 left-nav">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                {{ __('message.menu') }}
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav list-icon">
                    <li class="nav-item">
                        @include('component.language-modal.button')
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-heart" href="{{ route('wishlist') }}"><i
                                class="fa-solid fa-heart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon"
                            href="{{ url(App::getLocale() . '/blog') }}">{{ __('message.blog') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon" href="{{ route('contact') }}">{{ __('message.contactUs') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon"
                            href="{{ url(App::getLocale() . '/helps') }}">{{ __('message.helpPage') }}</a>
                    </li>
                    @if (!empty($user_name))
                        <li class="nav-item">
                            <a class="nav-link icon"
                                href="{{ url(App::getLocale() . '/account') }}">{{ $user_name }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <button class="nav-link icon cart" href="#" id="openModal" onclick="openCartModal()">
                            <i class="fa-solid fa-cart-shopping">
                                @isset($cart)
                                    <div class="quantity-item"><span>{{ count($cart->products) }}</span></div>
                                @else
                                    <div class="quantity-item"><span>0</span></div>
                                @endisset
                            </i>
                        </button>
                    </li>
                    @if (!empty($user_name))
                        <li class="nav-item">
                            <a class="nav-link icon" href="{{ url(App::getLocale() . '/logout') }}"><i
                                    class="fa-solid fa-arrow-right-from-bracket"></i></a>
                        </li>
                    @endif
                    @if (empty($user_name))
                        <li class="nav-item">
                            <a class="nav-link icon"
                                href="{{ url(App::getLocale() . '/login') }}">{{ __('message.login') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
