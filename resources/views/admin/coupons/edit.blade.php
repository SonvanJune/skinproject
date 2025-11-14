@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
    <style>
        .form-control:disabled {
            background-color: #e9ecef !important;
            opacity: 1;
        }
    </style>
@endsection

@section('title', 'Create coupons')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.coupons') }}">Coupons
                                Manage</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Coupons</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Coupons</h1>

                <div class="mt-2">
                    {{-- <a class="text-body me-3" href="javascript:;">
                        <i class="bi-clipboard me-1"></i> Duplicate
                    </a>
                    <a class="text-body" href="javascript:;">
                        <i class="bi-eye me-1"></i> Preview
                    </a> --}}
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <form id="addCouponForm" class="row needs-validation" action="{{ route('admin.coupons.update') }}" method="POST">
        @csrf
        @method('POST')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card Product information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Coupon information</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Product name -->
                    <div class="mb-4">
                        <label for="productNameLabel" class="form-label">Coupon Name</label>

                        <input type="text" class="form-control" name="coupon_name" id="nameInput"
                            placeholder="Enter coupon name..." required value="{{ $coupon->coupon_name }}">
                        <div class="invalid-feedback"> Please enter coupon name.</div>
                    </div>

                    <!-- Post Slug -->
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label">Coupon code <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Enter coupon code if you want to make a coupon, if you not enter it will be a discount"
                                data-bs-original-title="Enter coupon code if you want to make a coupon, if you not enter it will be a discount"></i></label>
                        <input type="text" class="form-control" name="coupon_code" id="codeInput"
                            placeholder="Enter coupon code..." aria-label="Enter coupon code..."
                            value="{{ $coupon->coupon_code }}">
                        <div class="invalid-feedback"> Please enter coupon code.</div>
                    </div>

                    <!-- Release -->
                    <div class="mb-4">
                        <label for="couponRelease" class="form-label">Coupon Release</label>
                        <input type="datetime-local" class="form-control" name="coupon_release" id="couponRelease" required
                            value="{{ \Carbon\Carbon::parse($coupon->coupon_release)->format('Y-m-d H:i:s') }}" disabled>
                        <div class="invalid-feedback"> Please enter valid coupon release.</div>
                    </div>

                    <input type="datetime-local" class="form-control" name="coupon_release" style="display: none"
                            value="{{ \Carbon\Carbon::parse($coupon->coupon_release)->format('Y-m-d H:i:s') }}">

                    <div class="mb-4">
                        <label for="couponExpired" class="form-label">Coupon Expired</label>
                        <input type="datetime-local" class="form-control" name="coupon_expired" id="couponExpired" required
                            value="{{ \Carbon\Carbon::parse($coupon->coupon_expired)->format('Y-m-d H:i:s') }}">
                        <div class="invalid-feedback"> Please enter valid coupon expired. The expired day not smaller than
                            release day</div>
                    </div>

                </div>
                <!-- Body -->
            </div>
            <!-- End Card Product information -->
        </div>
        <!-- End Col -->

        <div class="col-lg-4">

            <!-- Card -->
            <div class="card mb-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Coupon value</h4>
                </div>
                <!-- End Header -->

                <input type="hidden" name="coupon_id" value="{{ $coupon->coupon_id }}">

                <!-- Body -->
                <div class="card-body">
                    <!-- Post type -->
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label">Coupon price <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="If you enter coupon price, you can not enter coupon per_hundred"
                                data-bs-original-title="If you enter coupon price, you can not enter coupon per_hundred"></i></label>
                        <input type="number" class="form-control" name="coupon_price" id="couponPrice" placeholder="0"
                            aria-label="0" required value="{{ $coupon->coupon_price }}">
                        <div class="invalid-feedback"> Please enter coupon price.</div>
                    </div>
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label">Coupon per_hundred <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="If you enter coupon per_hundred, you can not enter coupon price"
                                data-bs-original-title="If you enter coupon per_hundred, you can not enter coupon price"></i></label>
                        <input type="number" class="form-control" name="coupon_per_hundred" id="couponPerHundred"
                            placeholder="0" aria-label="0" required value="{{ $coupon->coupon_per_hundred }}">
                        <div class="invalid-feedback"> Please enter coupon per_hundred</div>
                    </div>

                    @isset($productSelected)
                        @if ($productSelected != null)
                            <div class="mb-4" id="pselected">
                                <label for="productSlugLabel" class="form-label">Product Selected <i
                                        class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top" aria-label="This is the product has been selected"
                                        data-bs-original-title="This is the product has been selected"></i></label>
                                <a class="pselect-item" href="#">
                                    <img src="{{ route('get.file', ['filename' => $productSelected->post_image_path]) }}"
                                        alt="{{ $productSelected->post_image_alt }}" class="dropdown-item-img">
                                    {{ $productSelected->product_name }}
                                </a>
                                <input type="hidden" id="product_idd" value="{{ $productSelected->product_id }}">
                            </div>
                        @endif
                    @endisset

                    <div class="mb-4">
                        <label for="productDropdown" class="form-label">Select Product <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                aria-label="If you want apply coupon for product, choose product here"
                                data-bs-original-title="If you want apply coupon for product, choose product here"></i></label>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Choose a product
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownList">
                                <li>
                                    <a class="dropdown-item" href="#" data-id="">
                                        <span>Clear Selection</span>
                                    </a>
                                </li>
                                @foreach ($products->data as $product)
                                    <li>
                                        <a class="dropdown-item" href="#" data-id="{{ $product->product_id }}">
                                            <img src="{{ route('get.file', ['filename' => $product->post_image_path]) }}"
                                                alt="{{ $product->post_image_alt }}" class="dropdown-item-img">
                                            {{ $product->product_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="product_id" id="selectedProductId">
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card -->
        </div>
        <!-- End Col -->

        <!-- Button submit -->
        <div class="col-lg-8">
            <div class="d-grid gap-2">
                <button class="btn btn-primary w-full" onclick="handleSubmit(event)">Save</button>
            </div>
        </div>
    </form>

    <script src="{{ asset('js/admin/mutil-select-search.js') }}"></script>
    <script>
        const nameInput = document.getElementById('nameInput');
        const codeInput = document.getElementById('codeInput');
        const priceInput = document.getElementById('couponPrice');
        const percentInput = document.getElementById('couponPerHundred');
        const releaseDate = document.getElementById('couponRelease');
        const expiredDate = document.getElementById('couponExpired');
        const pselected = document.getElementById('pselected');

        priceInput.addEventListener('input', function() {
            checkInputValue();
        });

        percentInput.addEventListener('input', function() {
            checkInputValue();
        });

        function checkInputValueWhenReload() {
            if (priceInput.value === "" && percentInput.value === "") {
                percentInput.disabled = false;
                percentInput.setAttribute('required', true);
                priceInput.disabled = false;
                priceInput.setAttribute('required', true);
            }

            if (priceInput.value) {
                percentInput.disabled = true;
                percentInput.removeAttribute('required');
            }

            if (percentInput.value) {
                priceInput.disabled = true;
                priceInput.removeAttribute('required');
            }
        }

        function checkInputValue() {
            if (priceInput.value === "" && percentInput.value === "") {
                priceInput.classList.add("is-invalid");
                priceInput.classList.remove("is-valid");
                percentInput.classList.add("is-invalid");
                percentInput.classList.remove("is-valid");
                percentInput.disabled = false;
                percentInput.setAttribute('required', true);
                priceInput.disabled = false;
                priceInput.setAttribute('required', true);
                return false;
            }

            if (priceInput.value) {
                priceInput.classList.add("is-valid");
                priceInput.classList.remove("is-invalid");
                percentInput.disabled = true;
                percentInput.removeAttribute('required');
                percentInput.classList.remove("is-invalid");
                percentInput.classList.remove("is-valid");
                return true;
            }

            if (percentInput.value) {
                percentInput.classList.add("is-valid");
                percentInput.classList.remove("is-invalid");
                priceInput.disabled = true;
                priceInput.removeAttribute('required');
                priceInput.classList.remove("is-invalid");
                priceInput.classList.remove("is-valid");
                return true;
            }
        }

        function handleSubmit(event) {
            event.preventDefault()
            event.stopPropagation()

            let valid = true;
            let validName = checkInput(nameInput);
            let validPrice = checkInputValue();
            let validExpired = checkInputDate(expiredDate);

            // Validate that the expired date is after the release date
            if (new Date(expiredDate.value) < new Date(releaseDate.value)) {
                expiredDate.classList.add('is-invalid');
                expiredDate.classList.remove("is-valid");
                valid = false;
            }

            valid = validName && validExpired && validPrice;

            if (valid) {
                document.getElementById("addCouponForm").submit();
            } else {
                event.preventDefault();
            }
        }

        function checkInput(input) {
            let valid = true;
            if (input.value === "") {
                input.classList.add("is-invalid");
                input.classList.remove("is-valid");
                valid = false;
            } else {
                input.classList.add("is-valid");
                input.classList.remove("is-invalid");
                valid = true;
            }
            return valid;
        }

        function checkInputDate(input) {
            let valid = true;
            const currentDate = new Date().toISOString().split('T')[0];

            if (input.value === "") {
                input.classList.add("is-invalid");
                input.classList.remove("is-valid");
                valid = false;
            } else if (new Date(input.value) < new Date(currentDate)) {
                input.classList.add("is-invalid");
                input.classList.remove("is-valid");
                valid = false;
            } else {
                input.classList.add('is-valid')
                input.classList.remove("is-invalid");
                valid = true;
            }

            return valid;
        }

        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(event) {
                const productId = this.getAttribute('data-id');
                const productName = this.textContent.trim();

                if (productId === "") {
                    document.getElementById('selectedProductId').value = document.getElementById(
                        'product_idd').value;
                    document.getElementById('dropdownMenuButton').textContent =
                        "Choose a product";
                    pselected.style.display = 'flex';
                } else {
                    document.getElementById('selectedProductId').value = productId;
                    document.getElementById('dropdownMenuButton').textContent = productName;
                    pselected.style.display = 'none';
                }
            });
        });

        document.addEventListener('click', function(event) {
            if (!document.getElementById('dropdownMenuButton').contains(event.target) && !document.getElementById(
                    'dropdownList').contains(event.target)) {
                document.getElementById('dropdownList').classList.remove('show');
            }
        });

        window.onload = function() {
            checkInputValueWhenReload();
            document.getElementById('selectedProductId').value = document.getElementById(
                'product_idd').value;
        }
    </script>
@endsection
