@extends('layout')

@section('title', $category_detail->name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product-category.css') }}">
@endpush
@section('content')
    <section class="product-category">
        <div class="title">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h4>@yield('name')</h4>
                    </div>
                    <div class="col-md-6 col-sm-12 path">
                        <a href="/"><i class="fa-solid fa-house"></i></a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="">@yield('root')</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a
                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug) }}">@yield('nameSmall')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        @include('component.sidebar.sidebar-category', [
                            'categories' => $categories,
                            'category_select' => $category_detail,
                        ])
                    </div>
                    <div class="col-md-9 col-sm-12">
                        {{-- list product --}}
                        @yield('main')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    {{-- Import js if have --}}
@endpush
