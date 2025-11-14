@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Prodcuct')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.products') }}">Products</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Product</h1>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <form id="productForm" class="row needs-validation" novalidate action="{{ route('admin.products.store') }}"
        method="POST">
        @csrf
        @method('POST')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card Product information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Product information</h4>
                </div>
                <!-- End Header -->

                <input type="hidden" id="action" value="create">

                <!-- Body -->
                <div class="card-body">
                    <!-- Product name -->
                    <div class="mb-4">
                        <label for="productNameLabel" class="form-label required">Name</label>

                        <input type="text" class="form-control" name="product_name" id="productNameLabel"
                            placeholder="Enter product name..." required value="">
                        <div class="invalid-feedback"> Please enter product name.</div>
                    </div>

                    <!-- Post Slug -->
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label required">Slug <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="This input auto generate from product name"
                                data-bs-original-title="This input auto generate from product name"></i></label>

                        <input type="text" class="form-control" name="post_slug" id="productSlugLabel"
                            placeholder="Enter slug name..." aria-label="Enter slug name..." value="" required>
                        <div class="invalid-feedback"> Please enter product slug.</div>
                    </div>

                    <label class="form-label required">Product detail<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            aria-label="You can introduce product information here."
                            data-bs-original-title="You can introduce product information here."></i></label>
                    <input type="hidden" name="post_content" id="postContent">
                    @include('component.editor.editor')
                    <div class="invalid-feedback"> Please enter product detail.</div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Product information -->

            <!-- Card Thumbnail -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title required">Thumbnail<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Product representative image"
                            data-bs-original-title="Product representative image"></i></h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message mb-3">
                            <span class="btn btn-white btn-manager btn-sm" id="openImagePathModal">File Manager</span>
                        </div>

                        <div id="attachFile">
                        </div>
                    </div>
                    <div>
                        <input type="hidden" class="form-control" name="post_image_path" id="imagePathInput">
                        <input type="hidden" class="form-control text-center" name="post_image_alt"
                            placeholder="Enter image alt..." id="imageAltInput">
                        <div class="invalid-feedback"> Please choose image.</div>
                    </div>
                    <!-- End Dropzone -->
                    <!-- Product name -->

                </div>
                <!-- Body -->
            </div>
            <!-- End Card Thumbnail -->

            <!-- Card Folder -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title required">Product folder path<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            aria-label="Path to the folder containing sales documents related to the product"
                            data-bs-original-title="Path to the folder containing sales materials related to the product"></i>
                    </h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message mb-3">
                            <span class="btn btn-white btn-manager btn-sm mb-2" id="openFolderPathModal">File
                                Manager</span>
                            <p class="form-text">Please select the folder where you want to store the product files.</p>
                        </div>
                        <input type="text" class="form-control text-center" name="product_file_path"
                            id="productFilePathLabel" placeholder="Product folder path" value="" readonly>
                        <div class="invalid-feedback"> Please choose folders.</div>
                    </div>
                    <!-- End Dropzone -->
                    <!-- Product name -->

                </div>
                <!-- Body -->
            </div>
            <!-- End Card Folder -->

            <!-- Card Images -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title">Images<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            aria-label="Related images, you can choose more images here"
                            data-bs-original-title="Related images, you can choose more images here"></i></h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message mb-3">
                            <img class="avatar avatar-xl avatar-4x3 mb-3" src="{{ asset('images/upload-file.svg') }}"
                                alt="Image Description" data-hs-theme-appearance="default">

                            {{-- <h5>Drag and drop your file here</h5> --}}

                            <p class="mb-2"></p>

                            <span class="btn btn-white btn-manager btn-sm" id="openImagesPathModal">File
                                Manager</span>
                        </div>

                        <div id="attachFiles">

                        </div>

                        <div id="productImagesHidden"></div>
                    </div>
                    <!-- End Dropzone -->
                    <!-- Product name -->

                </div>
                <!-- Body -->
            </div>
            <!-- End Card Images -->
        </div>
        <!-- End Col -->

        <div class="col-lg-4">
            <!-- Card Category -->
            <div class="card mb-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title required">Category<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            aria-label="Select categories for the product, select at least one"
                            data-bs-original-title="Select a category for the product, select at least one"></i></h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body mb-2">
                    <input type="hidden" id="hasCategories">
                    <h6 class="text-cap">Options</h6>
                    <div class="mb-4">
                        <select id="categories" name="categories" data-placeholder="Select categories" multiple
                            data-multi-select>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}
                                    <small class="text-info">(level: {{ $category->level }})</small>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="invalid-feedback"> Please select categories for products.</div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Category -->

            <!-- Card Price -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Price && Discount</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body mb-2">
                    <!-- Form -->
                    <div class="mb-4">
                        <label for="priceNameLabel" class="form-label required">Price</label>

                        <div class="input-group">
                            <input type="number" class="form-control" name="product_price" id="priceNameLabel"
                                placeholder="0.00" aria-label="0.00" required>
                            <div class="invalid-feedback"> Please enter product price.</div>
                        </div>
                    </div>
                    <!-- End Form -->

                    <div class="mb-4">
                        <label class="row form-check form-switch" for="availabilityCoupon">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">Product Discount <i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="If you turn this button on, the product will integrate a discount."
                                        data-bs-original-title="If you turn this button on, the product will integrate a discount."></i></span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input type="checkbox" class="form-check-input" id="availabilityCoupon">
                            </span>
                        </label>
                    </div>

                    <div id="discount-in" style="display: none">
                        <div class="mb-4">
                            <label for="couponPriceLabel" class="form-label">Discount price <i
                                    class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="If you enter coupon price, you can not enter coupon per_hundred"
                                    data-bs-original-title="If you enter coupon price, you can not enter coupon per_hundred"></i></label>
                            <input type="number" class="form-control" name="coupon_price" id="couponPriceLabel"
                                placeholder="0" aria-label="0">
                            <div class="invalid-feedback"> Please enter discount price.</div>
                        </div>
                        <div class="mb-4">
                            <label for="perHundredLabel" class="form-label">Discount Percent <i
                                    class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="If you enter coupon per_hundred, you can not enter coupon price"
                                    data-bs-original-title="If you enter coupon per_hundred, you can not enter coupon price"></i></label>
                            <input type="number" class="form-control" name="coupon_per_hundred" id="perHundredLabel"
                                placeholder="0" aria-label="0">
                            <div class="invalid-feedback"> Please enter discount percent</div>
                        </div>

                        <div class="mb-4">
                            <label for="releaseDiscountLabel" class="form-label">Discount Release</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="coupon_release"
                                    id="releaseDiscountLabel">
                                <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                    onclick="resetDiscountRelease()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"> Please enter valid discount release.</div>
                        </div>

                        <div class="mb-4">
                            <label for="expiredDiscountLabel" class="form-label">Discount Expired</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="coupon_expired"
                                    id="expiredDiscountLabel">
                                <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                    onclick="resetDiscountExpired()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"> Please enter valid discount expired. The expired day not smaller
                                than
                                release day</div>
                        </div>
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Price-->

            <!-- Card Coupon-->
            <div class="card mb-5">
                <div class="card-header">
                    <h4 class="card-header-title">Status && Release && View</h4>
                </div>
                <!-- Body -->
                <div class="card-body">
                    <div class="mb-4">
                        <label class="row form-check form-switch" for="availabilitySwitch1">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">Product Status <i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="If you turn this button on, your product will be visible, if you turn it off, your category will be hidden."
                                        data-bs-original-title="If you turn this button on, your product will be visible, if you turn it off, your category will be hidden."></i></span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input type="checkbox" name="product_status" class="form-check-input"
                                    id="availabilitySwitch1" checked>
                            </span>
                        </label>
                    </div>

                    <div id="release-in" style="display: none">
                        <div class="mb-4">
                            <label for="releaseLabel" class="form-label">Release</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="product_release"
                                    id="releaseLabel" value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                                <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                    onclick="resetRelease()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                                <div class="invalid-feedback"> The release date can not smaller than now.</div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="mb-4">
                        <label class="row form-check form-switch" for="availabilitySwitchView">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">Product View Status <i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="If you enable this button, the product will show the actual number of views, if hidden, it will show fake views."
                                        data-bs-original-title="If you enable this button, the product will show the actual number of views, if hidden, it will show fake views."></i></span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input type="checkbox" name="product_status_views" class="form-check-input"
                                    id="availabilitySwitchView" checked>
                            </span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label for="fakeViewLabel" class="form-label">Fake Views</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="product_fake_views" id="fakeViewLabel"
                                placeholder="0" value="0" aria-label="0.00" required>
                            <div class="invalid-feedback"> Please enter product fake views.</div>
                        </div>
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Coupon-->
        </div>
        <!-- End Col -->

        <!-- Button submit -->
        <div class="col-lg-8">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary w-full" onclick="handleSubmit(event)">Save</button>
            </div>
        </div>
    </form>

    @include('component.modal.confirmModal', [
                'subject' => 'product',
                'action' => 'create'
    ])

    <script src="{{ asset('js/admin/mutil-select-search.js') }}"></script>
    <script src="{{ asset('js/admin/select-file-product.js') }}"></script>
    <script src="{{ asset('js/admin/product-create.js') }}"></script>
    <script src="{{ asset('js/admin/check-form-product.js') }}"></script>
@endsection
