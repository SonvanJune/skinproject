@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Product')

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
                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Product</h1>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->
    @if ($product)
        <form id="productForm" class="row needs-validation" novalidate action="{{ route('admin.products.update') }}"
            method="POST">
            @csrf
            @method('PUT')
            <div class="col-lg-8 mb-3 mb-lg-0">
                <!-- Card Product information-->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">Product information</h4>
                    </div>
                    <!-- End Header -->
                    <input type="hidden" id="action" value="edit">

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Product name -->
                        <div class="mb-4">
                            <label for="productNameLabel" class="form-label required">Name</label>
                            @if ($product->is_sold)
                                <input type="text" class="form-control" name="product_name"
                                    placeholder="Enter product name..." required value="{{ $product->product_name }}"
                                    disabled>
                                <input type="hidden" name="product_name" id="productNameLabel"
                                    value="{{ $product->product_name }}">
                            @else
                                <input type="text" class="form-control" name="product_name" id="productNameLabel"
                                    placeholder="Enter product name..." required value="{{ $product->product_name }}">
                            @endif
                            <div class="invalid-feedback"> Please enter product name.</div>
                        </div>

                        <!-- Post Slug -->
                        <div class="mb-4">
                            <label for="productSlugLabel" class="form-label required">Slug <i
                                    class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="Edit slug if you want to edit"
                                    data-bs-original-title="Edit slug if you want to edit"></i></label>
                            @if ($product->is_sold)
                                <input type="text" class="form-control" name="post_slug" placeholder="Enter slug name..."
                                    aria-label="Enter slug name..." value="{{ $product->product_slug }}" required disabled>
                                <input type="hidden" name="post_slug" id="productSlugLabel"
                                    value="{{ $product->product_slug }}">
                            @else
                                <input type="text" class="form-control" name="post_slug" id="productSlugLabel"
                                    placeholder="Enter slug name..." aria-label="Enter slug name..."
                                    value="{{ $product->product_slug }}" required>
                            @endif
                            <div class="invalid-feedback"> Please enter product slug.</div>
                        </div>
                        @if ($product->is_sold)
                            <div class="alert alert-danger" role="alert">
                                Product Have Sold Already!!
                            </div>
                        @endif
                        <input type="hidden" class="form-control" name="post_id" value="{{ $product->post_id }}">
                        <input type="hidden" class="form-control" name="updated_at"
                            value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                        <label class="form-label required">Product detail</label>
                        <input type="hidden" name="post_content" id="postContent" value="{{ $product->post_content }}">
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
                                <span class="btn btn-white btn-manager btn-sm mb-2" id="openImagePathModal">File
                                    Manager</span>
                                <p class="form-text">Choose new thumbnail</p>
                            </div>
                            <div id="attachFile">
                                <!-- Dropzone -->
                                @if (isset($product->post_image_path))
                                    <div class="col-12 m-1 dz-preview dz-file-preview">
                                        <div class="d-flex justify-content-end dz-close-icon">
                                            <small class="bi-x remove-image"
                                                data-index="{{ $product->post_id }}"></small>
                                        </div>
                                        <div class="dz-details d-flex flex-column align-items-center">
                                            <div class="dz-img flex-shrink-0">
                                                <img class="img-fluid" alt="{{ $product->post_image_alt }}"
                                                    src="{{ route('get.file', ['filename' => $product->post_image_path]) }}">
                                                <input type="hidden" name="post_image_path"
                                                    value="{{ $product->post_image_path }}">
                                            </div>
                                            <div class="dz-file-wrapper flex-grow-1 mt-2">
                                                <h6 class="dz-filename">
                                                    <span class="dz-title" data-dz-name="">
                                                        {{ Str::afterLast($product->post_image_path, '/') }}</span>
                                                </h6>
                                                <div class="dz-size" data-dz-size="">
                                                    <strong>{{ getFileSizeFormatted(base_path($product->post_image_path)) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div>

                                            <input type="text" class="form-control text-center" name="post_image_alt"
                                                id="imageAltLabel_${index}" placeholder="Enter image alt..."
                                                value="{{ $product->post_image_alt }}" required>
                                            <div class="invalid-feedback"> Please enter image alt.</div>
                                        </div>
                                    </div>
                                @endif
                                <!-- End Dropzone -->
                            </div>
                            <div>
                                <input type="hidden" class="form-control" name="post_image_path" id="imagePathInput"
                                    value="{{ $product->post_image_path }}">
                                <input type="hidden" class="form-control text-center" name="post_image_alt"
                                    placeholder="Enter image alt..." id="imageAltInput"
                                    value="{{ $product->post_image_alt }}">
                                <div class="invalid-feedback"> Please choose image.</div>
                            </div>
                        </div>
                        <!-- End Dropzone -->
                        <!-- Product name -->

                    </div>
                    <!-- Body -->
                </div>
                <!-- End Card Thumbnail -->

                <!-- Card Images -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title required">Product folder path<i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                data-bs-placement="top"
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
                                @if ($product->is_sold)
                                    <span class="btn btn-white btn-manager btn-sm mb-2">Product Have Sold Already!!</span>
                                @else
                                    <span class="btn btn-white btn-manager btn-sm mb-2" id="openFolderPathModal">File
                                        Manager</span>
                                    <p class="form-text">Please select the folder where you want to store the product
                                        files.
                                    </p>
                                @endif
                            </div>
                            <input type="text" class="form-control text-center" name="product_file_path"
                                id="productFilePathLabel" placeholder="Product folder path..."
                                value="{{ $product->product_file_path }}" readonly>
                        </div>
                        <!-- End Dropzone -->
                        <!-- Product name -->

                    </div>
                    <!-- Body -->
                </div>
                <!-- End Card Images -->

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
                            <div class="current-images">
                                @foreach ($product->product_images as $image)
                                    <div class="col-12 m-1 dz-preview dz-file-preview">
                                        <div class="d-flex justify-content-end dz-close-icon">
                                            <small class="bi-x remove-image"
                                                data-index="{{ $image->product_image_id }}"></small>
                                        </div>
                                        <div class="dz-details d-flex flex-column align-items-center">
                                            <div class="dz-img flex-shrink-0">
                                                <img class="img-fluid" alt="{{ $image->product_image_alt }}"
                                                    src="{{ route('get.file', ['filename' => $image->product_image_path]) }}">
                                                <input type="hidden"
                                                    name="productImages[{{ $image->product_image_id }}][product_image_path]"
                                                    value="{{ $image->product_image_path }}">
                                            </div>
                                            <div class="dz-file-wrapper flex-grow-1 mt-2">
                                                <h6 class="dz-filename">
                                                    <span class="dz-title" data-dz-name="">
                                                        {{ Str::afterLast($image->product_image_path, '/') }}</span>
                                                </h6>
                                                <div class="dz-size" data-dz-size="">
                                                    <strong>{{ getFileSizeFormatted(base_path($image->product_image_path)) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div>

                                            <input type="text" class="form-control text-center"
                                                name="productImages[{{ $image->product_image_id }}][product_image_alt]"
                                                id="imageAltLabel_${index}" placeholder="Enter image alt..."
                                                value="{{ $image->product_image_alt }}" required>
                                            <div class="invalid-feedback"> Please enter image alt.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">

                            <div class="dz-message my-3">

                                <p class="mb-2"></p>

                                <span class="btn btn-white btn-manager btn-sm" id="openImagesPathModal">File
                                    Manager</span>
                                <p class="form-text">Choose new images</p>
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
                        <h4 class="card-header-title">Category<i class="bi-question-circle text-body ms-1"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Select categories for the product, select at least one"
                                data-bs-original-title="Select a category for the product, select at least one"></i></h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body mb-2">
                        <input type="hidden" id="hasCategories" @if (count($product->categories) > 0) value="1" @endif>
                        <h6 class="text-cap">Options</h6>
                        <div class="mb-4">
                            <select id="categories" name="categories" data-placeholder="Select categories" multiple
                                data-multi-select>
                                @if (isset($product->categories))
                                    @php
                                        $categorySlugs = collect($product->categories)->pluck('slug')->toArray();
                                    @endphp
                                @endif
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if (isset($categorySlugs) && in_array($category->slug, $categorySlugs)) selected @endif>
                                        {{ $category->name }}
                                        <small class="text-info">(level: {{ $category->level }})</small>
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"> Please select categories for products.</div>
                        </div>
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
                                    placeholder="0.00" aria-label="0.00" required value="{{ $product->product_price }}">
                                <div class="invalid-feedback"> Please enter product price.</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="row form-check form-switch" for="availabilityCoupon">
                                <span class="col-8 col-sm-9 ms-0">
                                    <span class="text-dark">Product Discount <i class="bi-question-circle text-body ms-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            aria-label="If you turn this button on, the product will integrate a discount."
                                            data-bs-original-title="If you turn this button on, the product will integrate a discount."></i></span>
                                </span>
                                <span class="col-4 col-sm-3 text-end">
                                    <input type="checkbox" class="form-check-input" id="availabilityCoupon"
                                        @if ($product->discount) checked @endif>
                                </span>
                            </label>
                        </div>
                        @if ($product->discount)
                            <div id="discount-in" style="display: none">
                                <div class="mb-4">
                                    <label for="couponPriceLabel" class="form-label">Product Price Sale</label>
                                    <input type="number" class="form-control"
                                        value="{{ $product->product_price_sale }}" disabled id="priceSale">
                                </div>
                                <div class="mb-4">
                                    <input type="hidden" class="form-control" name="coupon_id"
                                        value="{{ $product->discount->coupon_id }}">
                                </div>
                                <div class="mb-4">
                                    <label for="couponPriceLabel" class="form-label">Discount price <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="If you enter coupon price, you can not enter coupon per_hundred"
                                            data-bs-original-title="If you enter coupon price, you can not enter coupon per_hundred"></i></label>
                                    <input type="number" class="form-control" name="coupon_price" id="couponPriceLabel"
                                        placeholder="0" aria-label="0" value="{{ $product->discount->coupon_price }}">
                                    <div class="invalid-feedback"> Please enter discount price.</div>
                                </div>
                                <div class="mb-4">
                                    <label for="perHundredLabel" class="form-label">Discount Percent <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="If you enter coupon per_hundred, you can not enter coupon price"
                                            data-bs-original-title="If you enter coupon per_hundred, you can not enter coupon price"></i></label>
                                    <input type="number" class="form-control" name="coupon_per_hundred"
                                        id="perHundredLabel" placeholder="0" aria-label="0"
                                        value="{{ $product->discount->coupon_per_hundred }}">
                                    <div class="invalid-feedback"> Please enter discount percent</div>
                                </div>

                                <div class="mb-4">
                                    <label for="releaseDiscountLabel" class="form-label">Discount Release</label>
                                    <div class="input-group">
                                        <input type="datetime-local" class="form-control" name="coupon_release"
                                            id="releaseDiscountLabel" value="{{ $product->discount->coupon_release }}">
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
                                            id="expiredDiscountLabel" value="{{ $product->discount->coupon_expired }}">
                                        <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                            onclick="resetDiscountExpired()">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"> Please enter valid discount expired. The expired day not
                                        smaller
                                        than
                                        release day</div>
                                </div>
                            </div>
                        @else
                            <div id="discount-in" style="display: none">
                                <div class="mb-4">
                                    <label for="couponPriceLabel" class="form-label">Product Price Sale</label>
                                    <input type="number" class="form-control" readonly id="priceSale">
                                </div>
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
                                    <input type="number" class="form-control" name="coupon_per_hundred"
                                        id="perHundredLabel" placeholder="0" aria-label="0">
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
                                    <div class="invalid-feedback"> Please enter valid discount expired. The expired day not
                                        smaller
                                        than
                                        release day</div>
                                </div>
                            </div>
                        @endif
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
                                        id="availabilitySwitch1" @if ($product->post_status == 1) checked @endif>
                                </span>
                            </label>
                        </div>

                        <div id="release-in" style="display: none">
                            <div class="mb-4">
                                <label for="releaseLabel" class="form-label">Release</label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" name="product_release"
                                        id="releaseLabel"
                                        value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                                    <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                        onclick="resetRelease()">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="mb-4">
                            <label class="row form-check form-switch" for="availabilitySwitchView">
                                <span class="col-8 col-sm-9 ms-0">
                                    <span class="text-dark">Product View Status <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="If you enable this button, the product will show the actual number of views, if hidden, it will show fake views."
                                            data-bs-original-title="If you enable this button, the product will show the actual number of views, if hidden, it will show fake views."></i></span>
                                </span>
                                <span class="col-4 col-sm-3 text-end">
                                    <input type="checkbox" name="product_status_views" class="form-check-input"
                                        id="availabilitySwitchView" @if ($product->product_status_views == 1) checked @endif>
                                </span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label for="fakeViewLabel" class="form-label">Fake Views</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="product_fake_views" id="fakeViewLabel"
                                    placeholder="0" aria-label="0.00" required
                                    value="{{ $product->product_fake_views }}">
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
                    <button type="submit" class="btn btn-primary w-full" onclick="handleSubmit(event)">Update</button>
                </div>
            </div>
        </form>
    @endif
    @include('component.modal.confirmModal', [
        'subject' => 'product',
        'action' => 'update',
    ])

    <script>
        const product = {!! json_encode($product) !!};
        const discount = {!! json_encode($product->discount) !!};
        const productImagesData = @json($product->product_images);
    </script>
    <script src="{{ asset('js/admin/mutil-select-search.js') }}"></script>
    <script src="{{ asset('js/admin/select-file-product.js') }}"></script>
    <script src="{{ asset('js/admin/product-edit.js') }}"></script>
    <script src="{{ asset('js/admin/check-form-product.js') }}"></script>
@endsection
