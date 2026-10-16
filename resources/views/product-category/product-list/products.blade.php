@push('css')
    <link rel="stylesheet" href="{{ asset('css/list-product.css') }}">
@endpush


<div class="products">
    <div class="container">
        <div class="note">{{ $noteOfCategory }}</div>
        <div class="header-list d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="left d-flex align-items-center gap-2">
                <button type="button" class="btn-select btn-grid active" aria-label="Grid View">
                    <i class="bi bi-grid"></i>
                </button>

                @if (!$products->isEmpty())
                    {{-- panigation --}}
                    <div class="pag-top d-flex align-items-center">
                        <ul class="pagination mb-0 d-flex align-items-center">
                            @if ($current_page != 1)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $category_detail->url . '?page=' . ($current_page - 1) }}"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= $total_page; $i++)
                                <li class="page-item {{ $current_page == $i ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $category_detail->url . '?page=' . $i }}">{{ $i }}</a>
                                </li>
                            @endfor
                            @if ($current_page != $total_page)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $category_detail->url . '?page=' . ($current_page + 1) }}"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="right d-flex align-items-center">
                <span>{{ __('message.view') }} <span
                        class="page">{{ count($products) > 0 ? $total_product_per_page : 0 }}</span>/<span>{{ $category_detail->product_count }}</span>&nbsp;{{ __('message.all') }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                @if (!$products->isEmpty())
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 products-grid">
                        @foreach ($products as $product)
                            <div class="col d-flex">
                                @include('component.product.product-item', [
                                    'product' => $product,
                                ])
                            </div>
                        @endforeach
                    </div>

                    <div class="pag-bot">
                        <ul class="pagination">
                            @if ($current_page != 1)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $category_detail->url . '?page=' . ($current_page - 1) }}"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            @for ($i = 1; $i <= $total_page; $i++)
                                @if ($current_page == $i)
                                    <li class="page-item active"><a class="page-link"
                                            href="{{ $category_detail->url . '?page=' . $i }}">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $category_detail->url . '?page=' . $i }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            @if ($current_page != $total_page)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $category_detail->url . '?page=' . ($current_page + 1) }}"
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
