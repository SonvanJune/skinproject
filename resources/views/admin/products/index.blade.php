@extends('layouts.admin')

@section('title', 'Admin-Products')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Products <span id="total-products"
                        class="badge bg-soft-dark text-dark ms-2">{{ count($paginatedDTO->data) > 0 ? $paginatedDTO->totalArr : 0 }} items</span>
                </h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.products.create') }}"><i class="bi bi-plus-circle me-2"></i>
                    Add
                    product</a>
            </div>

        </div>
    </div>
    <!-- End Page Header -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
                <form id="searchBox" data-route-search="{{ route('admin.products.search') }}"
                    data-csrf-search="{{ csrf_token() }}"
                    data-route-edit="{{ route('admin.products.edit', ['post_slug' => ':slug']) }}"
                    data-route-delete="{{ route('admin.products.delete', ['post_slug' => ':slug']) }}">
                    <!-- Search -->
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="search-input" type="search" class="form-control" placeholder="Search Product..."
                            aria-label="Search..." oninput="searchProductApi()" name="key" value="{{ request()->get('key') }}">

                        <div id="searchResultCount"
                            class="bg-light border border-primary rounded px-3 py-2 mt-2 text-primary small"
                            style="display: none;">
                            🔍 Found <span id="result-count-number" class="fw-semibold text-dark">0</span> matching
                            products.
                        </div>
                    </div>
                    <!-- End Search -->
                </form>
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <form class="input-per-pages" action="{{ route('admin.products') }}" id="form-page">
                    @foreach (request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <button type="button" class="btn btn-white w-100" aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi-list-task me-1"></i> <input type="number" step="1" min="1" max="100"
                            class="text-end border-0 form2-control" name="per_page"
                            value="{{ count($paginatedDTO->data) > 0 ? $paginatedDTO->total : 0 }}">
                        /
                        <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $paginatedDTO->totalArr }}</span>
                    </button>
                </form>

                <!-- Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn btn-white w-100" id="showHideDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi-table me-1"></i> Columns <span class="badge bg-soft-dark text-dark rounded-circle ms-1"
                            id="count-column"></span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end dropdown-card" aria-labelledby="showHideDropdown"
                        style="width: 15rem;">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-grid gap-3" id="column-filter-container">
                                    <!-- Form Switch -->
                                    <label class="row form-check form-switch" for="toggleColumn_product">
                                        <span class="col-8 col-sm-9 ms-0">
                                            <span class="me-2">Column</span>
                                        </span>
                                        <span class="col-4 col-sm-3 text-end">
                                            <input type="checkbox" class="form-check-input" id="toggleColumn_product"
                                                checked="" onchange="checkColumn(this.checked)">
                                        </span>
                                    </label>
                                    <!-- End Form Switch -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Dropdown -->
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                <table id="productTable"
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer"
                    role="grid" aria-describedby="datatable_info" style="width: 1271px;">
                    <thead class="thead-light">
                        <tr id="column-in-tabel-filter-container">
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="product-search">
                    </tbody>

                    <tbody id="product-table">
                        @foreach ($paginatedDTO->data as $index => $product)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>
                                <td>
                                    <!-- Edit Link -->
                                    <a class="btn btn-white btn-sm"
                                        href="{{ route('admin.products.edit', ['post_slug' => $product->product_slug]) }}">
                                        <i class="bi-pencil-fill me-1"></i>
                                    </a>
                                    <!-- End Edit Link -->

                                    <!-- Delete Link -->
                                    <form
                                        action="{{ route('admin.products.delete', ['post_slug' => $product->product_slug]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-white btn-sm" href="#"
                                            onclick="event.preventDefault(); confirmDelete(this);">
                                            <i class="bi-trash dropdown-item-icon"></i>
                                        </a>
                                    </form>
                                    <!-- End Delete Link -->
                                    <button class="btn btn-white btn-sm view-product-btn"
                                        data-product='@json($product)'
                                        onclick="openModalViewProduct(JSON.parse(this.dataset.product))">
                                        <i class="bi-eye-fill me-1"></i>
                                    </button>
                                </td>
                                <td class="c0">
                                    <a class="d-flex align-items-center justify-content-center"
                                        href="{{ route('get.file', ['filename' => $product->post_image_path]) }}">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-lg"
                                                onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"
                                                src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                                alt={{ $product->post_image_alt }}>
                                        </div>
                                    </a>
                                </td>
                                <td class="c1">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($product->product_name))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $product->product_name }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c2">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($product->product_slug))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $product->product_slug }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c3">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($product->product_file_path))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $product->product_file_path }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c4">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($product->post_release))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $product->post_release }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c5">
                                    <a class="d-flex align-items-center justify-content-center">
                                        @if ($product->post_status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-primary">Disable</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="c6">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($product->updated_at))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $product->updated_at }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="not-found"
                    class="py-2 d-flex flex-column align-items-center justify-content-center text-muted fade-in w-100">
                </div>
                <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">Product Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4 text-center">
                                        <img id="productThumbnail" src="" class="img-fluid rounded border"
                                            alt="Thumbnail">
                                    </div>
                                    <div class="col-md-8">
                                        <h4 id="productName"></h4>
                                        <p><strong>Slug:</strong> <span id="productSlug"></span></p>
                                        <p><strong>File:</strong> <span id="productFilePath"></span></p>
                                        <p><strong>Release Date:</strong> <span id="productRelease"></span></p>
                                        <p><strong>Status:</strong> <span id="productStatus" class="badge"></span></p>
                                        <p><strong>Price: </strong> $<span id="productPrice"></span></p>
                                        <p><strong>Discount:</strong> <span id="productDiscount"></span></p>
                                        <p><strong>Price Sale: </strong> $<span id="productPriceSale"></span></p>
                                        <p><strong>Coupon:</strong> <span id="productCoupon"></span></p>
                                        <p><strong>View:</strong> <span id="productView"></span> (Fake view: <span
                                                id="productFakeView"></span>)</p>
                                        <p><strong>Status View:</strong> <span id="productStatusView"
                                                class="badge"></span></p>
                                        <p><strong>Categories:</strong> <span id="productCategories"
                                                class="d-flex flex-wrap"></span></p>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                        <h5>Content</h5>
                                        <div id="productContent" class="p-2 border rounded"
                                            style="max-height: 200px; overflow-y: auto;"></div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                        <h5>Images</h5>
                                        <div id="productImages" class="row g-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer">
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-sm mb-2 mb-sm-0">
                </div>
                <!-- End Col -->

                <div class="col-sm-auto" id="pagination-product">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        @include('component.pagination.index', [
                            'paginatedDTO' => $paginatedDTO,
                            'subject' => 'products',
                        ])
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Footer -->
    </div>

    <!-- Modal -->
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'product',
    ])
    <script src="{{ asset('js/admin/product-table.js') }}"></script>
    <script>
        function openModalViewProduct(product) {
            document.getElementById('productName').innerText = product.product_name || '';
            document.getElementById('productSlug').innerText = product.product_slug || '';
            document.getElementById('productFilePath').innerText = product.product_file_path || '';
            document.getElementById('productRelease').innerText = product.post_release || '';
            if (product.post_status == 1) {
                document.getElementById('productStatus').innerText = 'Active';
                document.getElementById('productStatus').classList.add('badge-success');
                document.getElementById('productStatus').classList.remove('badge-primary');
            } else {
                document.getElementById('productStatus').innerText = 'Disable';
                document.getElementById('productStatus').classList.add('badge-primary');
                document.getElementById('productStatus').classList.remove('badge-success');
            }
            document.getElementById('productPrice').innerText = product.product_price || '';
            document.getElementById('productDiscount').innerText = product.product_discount || 'No discount';
            document.getElementById('productPriceSale').innerText = product.product_price_sale || '';
            document.getElementById('productView').innerText = product.product_views || '0';
            document.getElementById('productFakeView').innerText = product.product_fake_views || '0';
            if (product.product_status_views == 1) {
                document.getElementById('productStatusView').innerText = 'View';
                document.getElementById('productStatusView').classList.add('badge-success');
                document.getElementById('productStatusView').classList.remove('badge-primary');
            } else {
                document.getElementById('productStatusView').innerText = 'Fake View';
                document.getElementById('productStatusView').classList.add('badge-primary');
                document.getElementById('productStatusView').classList.remove('badge-success');
            }
            const url = routeGetFileTemplate.replace(":filename", product.post_image_path) || ''
            document.getElementById('productThumbnail').src = url;
            document.getElementById('productThumbnail').alt = product.post_image_alt || '';

            const container = document.getElementById('productCategories');
            container.innerHTML = '';
            const categories = product.categories || [];

            const couponEl = document.getElementById('productCoupon');
            couponEl.innerHTML = '';

            if (product.coupon_detail) {
                const parts = product.coupon_detail.split('/');
                const code = parts[0] || '';
                const detail = parts[1] || '';

                const badge = document.createElement('span');
                badge.className = 'badge bg-success me-2';
                badge.innerText = code;

                const detailSpan = document.createElement('span');
                detailSpan.className = 'text-muted';
                detailSpan.innerText = '/ ' + detail;

                couponEl.appendChild(badge);
                couponEl.appendChild(detailSpan);
            } else {
                couponEl.innerText = 'No coupon';
            }

            categories.forEach(cat => {
                const wrapper = document.createElement('div');
                wrapper.className = 'd-flex align-items-center border rounded px-2 py-1';
                wrapper.style.maxWidth = '160px';

                const img = document.createElement('img');
                const urlCate = routeGetFileTemplate.replace(":filename", cat.image_path) || ''
                img.src = urlCate;
                img.width = 30;
                img.height = 30;
                img.className = 'me-2 rounded';

                const name = document.createElement('span');
                name.innerText = cat.name;

                wrapper.appendChild(img);
                wrapper.appendChild(name);
                container.appendChild(wrapper);
            });

            document.getElementById('productContent').innerHTML = product.post_content || '';

            const imagesContainer = document.getElementById('productImages');
            imagesContainer.innerHTML = '';
            (product.product_images || []).forEach(url => {
                const img = document.createElement('img');
                const urlProduct = routeGetFileTemplate.replace(":filename", url.product_image_path) || ''
                img.src = urlProduct;
                img.className = 'col-3 img-fluid rounded border';
                imagesContainer.appendChild(img);
            });

            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        }
    </script>
    <script src="{{ asset('js/admin/highlight.js') }}"></script>
    <script src="{{ asset('js/admin/search-product.js') }}"></script>
@endsection
