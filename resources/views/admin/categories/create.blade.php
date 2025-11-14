@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Category')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.categories') }}">Categories</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Categories</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Category</h1>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <form id="productForm" class="row needs-validation" novalidate action="{{ route('admin.categories.store') }}"
        method="POST">
        @csrf
        @method('POST')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card Product information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Category information</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- category name -->
                    <div class="mb-4">
                        <label for="categoryNameLabel" class="form-label required">Name</label>

                        <input type="text" class="form-control" name="category_name" id="categoryNameLabel"
                            placeholder="Enter category name..." aria-label="Enter category name..." value="">
                        <div class="invalid-feedback"> Please enter category name.</div>
                    </div>

                    <!-- Category Slug -->
                    <div class="mb-4">
                        <label for="categorySlugLabel" class="form-label required">Slug <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label=""
                                data-bs-original-title="This input auto generate from category name"></i></label>

                        <input type="text" class="form-control" name="category_slug" id="categorySlugLabel"
                            placeholder="Enter slug name..." aria-label="Enter slug name..." value="">
                        <div class="invalid-feedback"> Please enter category slug.</div>
                    </div>
                    <div class="mb-4">
                        <label for="categoryDescriptionContent" class="form-label required">Description</label>
                        <input type="hidden" name="category_description" id="categoryDescriptionContent">
                        @include('component.editor.editor')
                        <div class="invalid-feedback"> Please enter category description.</div>
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Product information -->

            <!-- Card -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title" id="image-title">Image<label for="categorySlugLabel" class="form-label"><i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label=""
                                data-bs-original-title="If you choose the type as brand, then an image is required. If you choose the type as category, then an image is optional."></i></label>
                    </h4>

                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div id="attachFilesNewProjectLabel" class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message">
                            <img class="avatar avatar-xl avatar-4x3 mb-3" src="{{ asset('images/upload-file.svg') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-2"></p>

                            <span class="btn btn-white btn-manager btn-sm" id="openImageModal">File Manager</span>
                        </div>
                        <!-- Selected image-->
                        <div id="attachFile"></div>
                    </div>
                    <div>
                        <input type="hidden" class="form-control" name="category_image_path" id="imagePathInput">
                        <input type="hidden" class="form-control text-center" name="category_image_alt"
                            placeholder="Enter image alt..." id="imageAltInput">
                        <div class="invalid-feedback"> Please choose image.</div>
                    </div>
                    <!-- End Dropzone -->
                </div>
                <!-- Body -->
            </div>
            <!-- End Card -->
        </div>
        <!-- End Col -->

        <div class="col-lg-4">
            <!-- Card Category -->
            <div class="card mb-3">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Parent Category<label for="categorySlugLabel" class="form-label"><i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label=""
                                data-bs-original-title="If this category has no parent category, it will be the top level category."></i></label>
                    </h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <h6 class="text-cap">Options</h6>
                    <div class="mb-4">
                        <select id="category" name="parent_slug" data-placeholder="Select a category"
                            data-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" data-name="{{ $category->name }}"
                                    {{ isset($parent_slug) && $category->slug == $parent_slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                    <small class="text-info">(level: {{ $category->level }})</small>
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Category -->

            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Other</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="row form-check form-switch" for="availabilitySwitch1">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">Status <i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="If you turn this button on, your category will be visible, if you turn it off, your category will be hidden."
                                        data-bs-original-title="If you turn this button on, your category will be visible, if you turn it off, your category will be hidden."></i></span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input type="checkbox" name="category_status" class="form-check-input"
                                    id="availabilitySwitch1" checked>
                            </span>
                        </label>
                    </div>

                    <hr class="my-4">


                    <!-- Release -->
                    <div id="release-in" style="display: none">
                        <div class="mb-4">
                            <label for="releaseLabel" class="form-label">Release</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="category_release"
                                    id="releaseLabel" value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                                <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                    onclick="resetRelease()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label class="form-label required d-block mb-2">Category type</label>
                        <div class="btn-group" role="group" aria-label="Category type">
                            <input type="radio" class="btn-check"  id="categoryBtn" autocomplete="off"
                                onchange="setCategoryType(1)" checked>
                            <label class="btn btn-outline-primary" for="categoryBtn">Category</label>
                    
                            <input type="radio" class="btn-check" id="brandBtn" autocomplete="off"
                                onchange="setCategoryType(2)">
                            <label class="btn btn-outline-primary" for="brandBtn">Brand</label>
                        </div>
                    
                        <!-- Hidden input để submit giá trị -->
                        <input type="hidden" name="category_type" id="categoryTypeInput" value="1">
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
                <button type="submit" class="btn btn-primary w-full" onclick="handleSubmit(event)">Save</button>
            </div>
        </div>
    </form>

    <script src="{{ asset('js/admin/single-select-search.js') }}"></script>
    <script src="{{ asset('js/admin/select-file-category.js') }}"></script>
    <script src="{{ asset('js/admin/category-create.js') }}"></script>
    <script src="{{ asset('js/admin/check-form-category.js') }}"></script>
@endsection
