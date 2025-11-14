@push('css')
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush


<div class="products">
    <div class="container">
        <div class="note">{{ $noteOfCategory }}</div>
        <div class="header-list">
            <div class="left">
                <btn class="btn-select btn-grid active"><i class="bi bi-grid"></i></btn>
                {{-- <btn class="btn-select btn-list"><i class="bi bi-list-task"></i></btn> --}}
                {{-- <div class="select">
                    <div class="ic"><i class="fa-solid fa-chevron-down"></i></div>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div> --}}

                @if (!$products->isEmpty())
                    {{-- panigation --}}
                    <div class="pag-top">
                        <ul class="pagination">
                            @if ($current_page != 1)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $current_page - 1) }}"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= $total_page; $i++)
                                @if ($current_page == $i)
                                    <li class="page-item active"><a class="page-link"
                                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $i) }}">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            @if ($current_page != $total_page)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $current_page + 1) }}"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="right">
                <span>{{ __('message.view') }} <span
                        class="page">{{ count($products) > 0 ? $total_product_per_page : 0 }}</span>/<span>{{ $category_detail->product_count }}</span>&nbsp;{{ __('message.all') }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                @if (!$products->isEmpty())
                    @foreach ($products->chunk(3) as $chunk)
                        <div class="d-flex flex-md-nowrap flex-wrap list">
                            @foreach ($chunk as $product)
                                @include('component.product.product-item', [
                                    'product' => $product,
                                ])
                            @endforeach
                        </div>
                    @endforeach

                    <div class="pag-bot">
                        <ul class="pagination">
                            @if ($current_page != 1)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $current_page - 1) }}"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= $total_page; $i++)
                                @if ($current_page == $i)
                                    <li class="page-item active"><a class="page-link"
                                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $i) }}">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            @if ($current_page != $total_page)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ url(App::getLocale() . '/product-category' . '/' . $category_detail->slug . '?page=' . $current_page + 1) }}"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @else
                    @include('component.noticationNoData.list.index', [])
                @endif
            </div>
        </div>
    </div>
</div>

@push('js')
    {{-- Import js if have --}}
@endpush
