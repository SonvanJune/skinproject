@extends('layout')

@section('title', __('message.orderHistory'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/my-account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-table.css') }}">
@endpush

@section('content')
    <div class="my-account">
        @if (!empty($user_name))
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ __('message.orderHistory') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user->user_last_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="{{ url(App::getLocale() . '/orders') }}">{{ __('message.orderHistory') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container content my-5">
                <div class="row">
                    <div class="col-md-3">
                        @include('component.sidebar.sidebar-user-page', [
                            'user' => $user,
                        ])
                    </div>
                    <div class="col-md-9">
                        <h2 class="text-center mb-4">{{ __('message.orderHistory') }}</h2>

                        @if ($orders != null)
                            <p class="note">{{ __('message.downloadNote') }}</p>
                            <div class="table-responsive">
                                <div class="header d-flex justify-content-between">
                                    <div class="left">
                                    </div>
                                    <div class="right">
                                        <span>{{ __('message.view') }} <span
                                                class="page">{{ $orders->total }}</span>/<span>{{ $orders->totalArr }}</span>{{ __('message.all') }}</span>
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('message.couponCode') }}</th>
                                            <th>{{ __('message.timePayment') }}</th>
                                            <th>{{ __('message.vatDetail') }}</th>
                                            <th>{{ __('message.vatValue') }}</th>
                                            <th>{{ __('message.totalPrice') }}</th>
                                            <th>{{ __('message.status') }}</th>
                                            <th>{{ __('message.productTitle') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders->data as $key => $order)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                @if ($order->coupon)
                                                    @if ($order->coupon->product)
                                                        <td><span
                                                                class="text-danger fw-bold">{{ $order->coupon->coupon_code }}</span>{{ ' -' . ($order->coupon->coupon_price ? $order->coupon->coupon_price . '$' : $order->coupon->coupon_per_hundred . '%') . ' ' . __('message.for') . ' ' . $order->coupon->product->product_name }}
                                                        </td>
                                                    @else
                                                        <td><span
                                                                class="text-danger fw-bold">{{ $order->coupon->coupon_code }}</span>{{ ' -' . ($order->coupon->coupon_price ? $order->coupon->coupon_price . '$' : $order->coupon->coupon_per_hundred . '%') . ' ' . __('message.forOrder') }}
                                                        </td>
                                                    @endif
                                                @else
                                                    <td>{{ __('message.notCouponCode') }}</td>
                                                @endif
                                                <td>{{ $order->updated_at }}</td>
                                                <td>{{ $order->vat_detail }}</td>
                                                <td>${{ $order->vat_value }}</td>
                                                <td>${{ $order->total_price }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-success">{{ $order->status == 2 ? __('message.orderSuccess') : __('message.orderNotProcessed') }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm"
                                                        onclick="showOrderDetails({{ json_encode($order->cart->products) }} , {{ json_encode($order->discounts) }})">
                                                        {{ __('message.viewDetailOrder') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                        <div id="productModal" class="modalProduct">
                                            <div class="modalProduct-content">
                                                <span class="closeBtn">&times;</span>
                                                <h2>{{ __('message.listProduct') }}</h2>
                                                <div id="product-list">

                                                </div>
                                            </div>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                            @if (count($orders->data) > 0)
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        @if ($orders->current_page != 1)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ url(App::getLocale() . '/account/orders' . '?page=' . $orders->current_page - 1) }}"
                                                    aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        @endif
                                        @for ($i = 1; $i <= $orders->last_page; $i++)
                                            @if ($orders->current_page == $i)
                                                <li class="page-item active"><a class="page-link"
                                                        href="{{ url(App::getLocale() . '/account/orders' . '?page=' . $i) }}">{{ $i }}</a>
                                                </li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ url(App::getLocale() . '/account/orders' . '?page=' . $i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor
                                        @if ($orders->current_page != $orders->last_page)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ url(App::getLocale() . '/account/orders' . '?page=' . $orders->current_page + 1) }}"
                                                    aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        @else
                            @include('component.noticationNoData.list.index', [])
                        @endif
                    </div>
                </div>
            </div>
        @else
            @include('component.noticationNoData.user.index', [])
        @endif
    </div>
@endsection
@include('component.noticationNoData.notification.index', ['notification' => 'error', 'type' => 'error'])

@push('js')
    <script>
        var modalProduct = document.getElementById("productModal");
        var spanClose = document.getElementsByClassName("closeBtn")[0];

        spanClose.onclick = function() {
            modalProduct.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modalProduct) {
                modalProduct.style.display = "none";
            }
        }

        function showOrderDetails(products, discounts) {
            var productList = '';

            products.forEach(function(product) {
                productList += '<div class="card w-100 mb-3" style="max-width: 100%;">';
                productList += '<div class="d-flex align-items-center">';
                const url = routeGetFileTemplate.replace(":filename", product.post_image_path);
                productList += '<img src="' + url +
                    '" class="card-img-top" alt="' +
                    product.post_image_alt + '" style="width: 100px; height: 100px; object-fit: cover;">';

                productList += '<div class="card-body w-60">';
                productList += '<a href="' + '{{ url(App::getLocale()) }}' + '/product/' + product.product_slug +
                    '" class="card-title">' + product.product_name + '</a>';

                let salePrice = product.product_price_sale;
                if (discounts.hasOwnProperty(product.product_id)) {
                    salePrice = discounts[product.product_id];
                }

                // productList += '<p class="card-text">{{ __('message.productPriceColumn') }}: $' + product
                //     .product_price + '</p>';
                // if (product.product_price != salePrice) {
                //     productList += '<p class="card-text">{{ __('message.productPriceSaleColumn') }}: $' +
                //         salePrice + '</p>';
                // }
                productList += '</div>';
                productList += '</div>';
                productList += '<div class="down">';
                productList += '<form action="{{ route('downloadProduct') }}" method="post">';
                productList += '@csrf';
                productList += '<input type="hidden" name="src_product" value="' + product.product_file_path + '">';
                productList += '<input type="hidden" name="zip_file_name" value="' + product.product_name + '">';
                productList += '<button type="submit" class="btn-down">';
                productList += '<img src="{{ asset('images/download.png') }}" alt="">';
                productList += '</button>';
                productList += '</form>';
                productList += '</div>';
                productList += '</div>';

            });

            document.getElementById('product-list').innerHTML = productList;
            modalProduct.style.display = "block";
        }
    </script>
@endpush
