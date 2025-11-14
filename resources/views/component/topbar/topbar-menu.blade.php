<div class="topbar-menu">
    <div class="container in-topbar-menu">
        <a class="btn b-home active" type="button" href="{{ LaravelLocalization::localizeUrl(url('/')) }}">
            <span>{{ __('message.home') }}</span>
        </a>
        @isset($categories)
            @foreach ($categories as $key => $category)
                @if ($key == $position_logo)
                    <div class="logo">
                        <a class="navbar-brand" href="{{route('home')}}"><img src="{{ asset('images/logo.png') }}" class="img-fluid"></a>
                    </div>
                    <a href="{{route('home')}}" class="logo-title">
                        <h2>{{ __('message.webName') }}</h2>
                    </a>
                @endif
                <div class="cate">
                    @if (!empty($category->children))
                        <a class="btn b-item" type="button"
                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                            <span>{{ $category->name }}</span>
                            <i class="bi bi-caret-right-fill"></i>
                        </a>
                        <div class="cate-menu" style="top: 100% ; left: 30%">
                            @foreach ($category->children as $child)
                                @include('component.topbar.topbar-item', ['category' => $child])
                            @endforeach
                        </div>
                    @else
                        <div class="cate">
                            <a class="btn b-item" type="button"
                                href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                                <span>{{ $category->name }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="logo">
                <a class="navbar-brand"><img src="{{ asset('images/logo.png') }}" class="img-fluid"></a>
            </div>
            <a href="#" class="logo-title">
                <h2>{{ __('message.webName') }}</h2>
            </a>
        @endisset
    </div>
</div>

<div class="topbar-menu-expand">
    <div class="dropdown-bot">
        <button class="dropdown-toggle-bot"></button>
        <div class="dropdown-menu-bot">
            <a class="btn b-home active" type="button" href="{{ url(App::getLocale() . '/') }}">
                {{ __('message.home') }}
            </a>
            @isset($categories)
                @foreach ($categories as $category)
                    <div class="cate">
                        @if (!empty($category->children))
                            <a class="btn b-item" type="button">
                                <span>{{ $category->name }}</span>
                                <i class="bi bi-caret-right-fill"></i>
                            </a>
                            <div class="cate-menu" style="top: 100% ; left: 30%">
                                @foreach ($category->children as $child)
                                    @include('component.topbar.topbar-item-expand', ['category' => $child])
                                @endforeach
                            </div>
                        @else
                            <div class="cate">
                                <a class="btn b-item" type="button"
                                    href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                                    <span>{{ $category->name }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endisset
        </div>
    </div>
</div>
