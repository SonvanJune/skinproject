@extends('layouts.admin')

@section('title', 'Admin home')

@section('content')
    <div class="admin-home">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-header-title">Dashboard</h1>
                </div>
            </div>
        </div>
        {{-- End Page Header --}}
        <div class="row mb-3">
            <a class="col-md-3" href="#">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-person-fill"></i>
                            <div>
                                <h6 class="card-title">Total User</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalUser }}</h2>
                                <small class="text-muted">Subadmins: {{ $total->totalSubadmin }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.roles') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-person-fill-lock"></i>
                            <div>
                                <h6 class="card-title">Total Role</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalRole }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.questions') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-patch-question-fill"></i>
                            <div>
                                <h6 class="card-title">Questions</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalSecurityQuestion }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.slideshows') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-images"></i>
                            <div>
                                <h6 class="card-title">Slide Show</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalSlideShow }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="row mb-3">
            <a class="col-md-3" href="{{ route('admin.products') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-box-fill"></i>
                            <div>
                                <h6 class="card-title">Total Product</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalProduct }}</h2>
                                <small class="text-muted">Release: {{ $total->totalProductRelease }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.posts') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <div>
                                <h6 class="card-title">Total Post</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalPost }}</h2>
                                <small class="text-muted">Release: {{ $total->totalPostRelease }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.categories') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-list-task"></i>
                            <div>
                                <h6 class="card-title">Total Category</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalCategory }}</h2>
                                <small class="text-muted">Release: {{ $total->totalCategoryRelease }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="{{ route('admin.categories') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-substack"></i>
                            <div>
                                <h6 class="card-title">Total Brand</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalBrand }}</h2>
                                <small class="text-muted">Release: {{ $total->totalBrandRelease }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="row mb-3">
            <a class="col-md-3" href="{{ route('admin.coupons') }}">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-ticket-perforated-fill"></i>
                            <div>
                                <h6 class="card-title">Total Coupon</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalCoupon }}</h2>
                                <small class="text-muted">Release: {{ $total->totalCouponRelease }}</small>
                                <br>
                                <small class="text-muted">Expired: {{ $total->totalCouponExpired }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-3" href="#">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="bi bi-file-text-fill"></i>
                            <div>
                                <h6 class="card-title">Total Order</h6>
                                <h2 class="card-text text-inherit">{{ $total->totalOrder }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-header-title">Revenue Table</h1>
                </div>
            </div>
        </div>

        <div class="revenue-table">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-4">
                        <label for="exportOption" class="form-label">Select time period for export</label>
                        <input type="datetime-local" id="startDate"
                            value="{{ now()->startOfMonth()->setTime(0, 0)->format('Y-m-d\TH:i') }}">
                        <input type="datetime-local" id="endDate"
                            value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                        <button class="btn btn-success btn-export" onclick="fetchOrders()">Filter Order</button>
                    </div>

                    <!-- Bảng Doanh Thu -->
                    <table class="table table-striped table-hover" id="revenueTable">
                        <thead>
                            <tr>
                                <th scope="col">Number</th>
                                <th scope="col">Date</th>
                                <th scope="col">Coupon</th>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Price Sale</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">User</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <div id="productModal" class="modalProduct" style="display: none;">
                        <div class="modalProduct-content">
                            <span class="closeBtn" onclick="closeModal()">&times;</span>
                            <h2>List Product</h2>
                            <div id="product-list"></div>
                        </div>
                    </div>

                    <!-- Nút xuất Excel -->
                    <button class="btn btn-success btn-export mt-3" onclick="exportToExcel()" id="btn-export" disabled>
                        Export to Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        const webName = '{{ __('message.webName') }}';
    </script>
    <script src="{{ asset('js/excel/excel.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchOrders();
        });

        function fetchOrders() {
            const startDate = document.getElementById("startDate").value;
            const endDate = document.getElementById("endDate").value;
            const url = '{{ route('getOrderByAdmin') }}' + '/' + `?startDate=${startDate}&endDate=${endDate}`;
            const csrfGetOrder = '{{ csrf_token() }}';
            const exportBtn = document.getElementById('btn-export');
            fetch(url, {
                    method: "Get",
                    headers: {
                        "X-CSRF-TOKEN": csrfGetOrder,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        renderOrderTable(data);
                        exportBtn.disabled = false;
                    } else {
                        exportBtn.disabled = true;
                        const tableBody = document.querySelector('#revenueTable tbody');
                        tableBody.innerHTML = `
                        <tr>
                        <td colspan="11" class="text-center text-danger">
                        ${typeof data === 'string' ? data : 'No data found or unexpected response.'}
                        </td>
                        </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }

        function renderOrderTable(orders) {
            const tableBody = document.querySelector('#revenueTable tbody');
            tableBody.innerHTML = '';

            let totalRevenue = 0;

            orders.forEach((order, index) => {
                const number = index + 1;
                const date = new Date(order.updated_at).toLocaleString('vi-VN', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                let coupon = 'No Coupon';

                if (order.coupon) {
                    if (order.coupon.product) {
                        if (order.coupon.coupon_price) {
                            coupon =
                                `${order.coupon.coupon_code} (-$${order.coupon.coupon_price} for ${order.coupon.product.product_name})`;
                        } else {
                            coupon =
                                `${order.coupon.coupon_code} (-${order.coupon.coupon_per_hundred}% for ${order.coupon.product.product_name})`;
                        }
                    } else {
                        if (order.coupon.coupon_price) {
                            coupon = `${order.coupon.coupon_code} (-$${order.coupon.coupon_price} for Order)`;
                        } else {
                            coupon = `${order.coupon.coupon_code} (-${order.coupon.coupon_per_hundred}% for Order)`;
                        }
                    }
                }

                // Gộp tên các sản phẩm
                const productNames = order.cart.products.map(p => p.product_name).join(', ');
                const productPrices = order.cart.products.map(p => `$${p.product_price}`).join(' + ');
                const productSalePrices = order.cart.products.map(product => {
                    let salePrice = product.product_price_sale;

                    if (order.discounts.hasOwnProperty(product.product_id)) {
                        salePrice = order.discounts[product.product_id];
                    }

                    return '$' + salePrice;
                }).join(' + ');

                const userName = order.user ? order.user.user_email : 'N/A';
                const totalPrice = '$' + parseFloat(order.total_price);
                totalRevenue += parseFloat(order.total_price);

                let statusText = '';
                switch (order.status) {
                    case "1":
                        statusText = "Pending";
                        break;
                    case "2":
                        statusText = "Completed";
                        break;
                    default:
                        statusText = "Unknown";
                }

                let payment = '';
                switch (order.order_payment) {
                    case "1":
                        payment = "Paypal";
                        break;
                    default:
                        payment = "Unknown";
                }

                // Hành động (ví dụ có thể là nút chi tiết hoặc sửa)
                const action = `<button class="btn btn-info btn-sm"
                onclick='showOrderDetails(${JSON.stringify(order.cart.products)}, ${JSON.stringify(order.discounts)})'>
                Details
                </button>`;

                // Tạo hàng mới
                const row = document.createElement('tr');
                row.innerHTML = `
               <td class="fw-bold">${number}</td>
               <td class="fw-bold">${date}</td>
               <td>${coupon}</td>
               <td>${productNames}</td>
               <td class="revenue">${productPrices}</td>
               <td class="revenue">${productSalePrices}</td>
               <td class="revenue">${totalPrice}</td>
               <td>${userName}</td>
               <td><span class="badge badge-success">${statusText}</span></td>
               <td>${payment}</td>
               <td>${action}</td>
                `;
                tableBody.appendChild(row);
            });

            totalRevenue = "$" + totalRevenue;

            const totalRow = document.createElement('tr');
            totalRow.classList.add('total-row');
            totalRow.innerHTML = `
            <td colspan="4" class="text-end">Total Revenue</td>
            <td colspan="3" id="totalRevenue">${totalRevenue}</td>
            <td colspan="4"></td>
            `;
            tableBody.appendChild(totalRow);
        }
    </script>
    <script src="{{ asset('js/export.js') }}"></script>
    <script>
        function closeModal() {
            document.getElementById("productModal").style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("productModal")) {
                document.getElementById("productModal").style.display = "none";
            }
        }

        function showOrderDetails(products, discounts) {
            var modalProduct = document.getElementById("productModal");
            var spanClose = document.getElementsByClassName("closeBtn")[0];
            var productList = '';

            products.forEach(function(product) {
                productList += '<div class="card w-100 mb-3" style="max-width: 100%;">';
                productList += '<div class="d-flex align-items-center">';
                const getFileUrl = routeGetFileTemplate.replace(":filename", product.post_image_path);
                productList += '<img src="' + getFileUrl + '" class="card-img-top" alt="' +
                    product.post_image_alt + '" style="width: 100px; height: 100px; object-fit: cover;">';

                productList += '<div class="card-body w-60">';
                productList += '<a href="' + '{{ url(App::getLocale()) }}' + '/product/' + product.product_slug +
                    '" class="card-title">' + product.product_name + '</a>';

                let salePrice = product.product_price_sale;
                if (discounts.hasOwnProperty(product.product_id)) {
                    salePrice = discounts[product.product_id];
                }

                productList += '<p class="card-text">{{ __('message.productPriceColumn') }}: $' + product
                    .product_price + '</p>';
                if (product.product_price != salePrice) {
                    productList += '<p class="card-text">{{ __('message.productPriceSaleColumn') }}: $' +
                        salePrice + '</p>';
                }
                productList += '</div>';
                productList += '</div>';
                productList += '</div>';

            });

            document.getElementById('product-list').innerHTML = productList;
            modalProduct.style.display = "block";
        }
    </script>
@endpush
