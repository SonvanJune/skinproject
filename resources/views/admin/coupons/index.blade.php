@extends('layouts.admin')

@section('title', 'Admin-Coupons')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Coupon manage <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span></h1>
            </div>

            <div class="col-sm-auto">
                <a id="btn-add-subamin" class="btn btn-primary" href="{{ route('admin.coupons.create') }}"><i
                        class="bi bi-plus-circle me-2"></i>
                    Add
                    Coupon</a>
            </div>

        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @php
        $paginationRoute = '/admin/coupons?per_page=' . $paginatedDTO->per_page . '&page=';
        $offset = 1;
        $bridge = 3;
        $has_first_offset = false;
        $has_last_offset = false;
    @endphp

    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
                <form id="searchBox" data-route-search="{{ route('admin.coupons.search') }}"
                    data-csrf-search="{{ csrf_token() }}" data-link={{ asset('') }}
                    data-route-edit="{{ route('admin.coupons.edit', ['id' => ':id']) }}"
                    data-route-delete="{{ route('admin.coupons.delete') }}">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="search-input" type="search" class="form-control" placeholder="Search Coupon..."
                            aria-label="Search..." oninput="searchCouponApi()" name="key"
                            value="{{ $paginatedDTO->key }}">
                        <div id="searchResultCount"
                            class="bg-light border border-primary rounded px-3 py-2 mt-2 text-primary small"
                            style="display: none;">
                            🔍 Found <span id="result-count-number" class="fw-semibold text-dark">0</span> matching
                            coupons.
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <form class="input-per-pages" action="{{ route('admin.coupons') }}" id="form-page">
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
                        <i class="bi-table me-1"></i> Columns <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">8</span>
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
                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">No.</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>

                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>
                        </tr>
                    </thead>

                    <tbody id="coupon-search">
                    </tbody>

                    <tbody id="coupon-table">
                        @foreach ($paginatedDTO->data as $index => $coupon)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    @if (\Carbon\Carbon::parse($coupon->coupon_expired)->isPast())
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            NO ACTIONS
                                        </button>
                                    @else
                                        @if ($coupon->is_used)
                                            <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                                USED ALREADY
                                            </button>
                                        @else
                                            <a class="btn btn-white btn-sm"
                                                href="{{ url(App::getLocale() . '/admin/coupons/edit/' . $coupon->coupon_id) }}">
                                                <i class="bi-pencil-fill me-1"></i>
                                            </a>

                                            <form action="{{ route('admin.coupons.delete') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a class="btn btn-white btn-sm" href="#"
                                                    onclick="event.preventDefault(); confirmDelete(this);">
                                                    <i class="bi-trash dropdown-item-icon"></i>
                                                </a>
                                                <input type="hidden" name="coupon_id" value="{{ $coupon->coupon_id }}">
                                            </form>
                                        @endif
                                    @endif
                                </td>
                                <td class="c0">{{ $coupon->coupon_name }}</td>
                                <td class="c1">{{ $coupon->coupon_code ?? '##' }}</td>
                                <td class="c2">{{ $coupon->coupon_price ?? '##' }}</td>
                                <td class="c3">{{ $coupon->coupon_per_hundred ?? '##' }}</td>
                                @if ($coupon->product)
                                    <td class="c4"> {{ $coupon->product->product_name }}</td>
                                @else
                                    <td class="c4">##</td>
                                @endif
                                <td class="c5">{{ $coupon->coupon_release }}</td>
                                <td
                                    class="c6 {{ \Carbon\Carbon::parse($coupon->coupon_expired)->isPast() ? 'text-danger' : '' }}">
                                    {{ $coupon->coupon_expired }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="not-found"
                    class="py-2 d-flex flex-column align-items-center justify-content-center text-muted fade-in w-100">
                </div>
            </div>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer mt-2">
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-sm mb-2 mb-sm-0">
                </div>
                <!-- End Col -->

                <div class="col-sm-auto" id="pagination-coupon">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <!-- Pagination -->
                        @include('component.pagination.index', [
                            'paginatedDTO' => $paginatedDTO,
                            'subject' => 'coupons',
                        ])
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Footer -->
    </div>
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'coupon',
    ])

    <script src="{{ asset('js/admin/coupon-table.js') }}"></script>
    <script>
        function confirmDelete(element) {
            const form = element.closest("form");

            const modal = new bootstrap.Modal(
                document.getElementById("deleteConfirmationModal")
            );
            modal.show();

            document.getElementById("confirmDeleteBtn").onclick = function() {
                form.submit();
                modal.hide();
            };
        }
    </script>
    <script src="{{ asset('js/admin/highlight.js') }}"></script>
    <script src="{{ asset('js/admin/search-coupon.js') }}"></script>
@endsection
