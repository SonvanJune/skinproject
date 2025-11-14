@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Category')

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
                        <li class="breadcrumb-item active" aria-current="page">Edit Categories</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Category</h1>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->
    @if ($category)
        <form id="productForm" class="row needs-validation" novalidate action="{{ route('admin.categories.update') }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <input type="hidden" name="updated_at" value="" id="updateAt">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h4 class="card-header-title">Category information</h4>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <label for="categoryNameLabel" class="form-label required">Name</label>
                            <input type="text" class="form-control" name="category_name" id="categoryNameLabel"
                                placeholder="Enter category name..." aria-label="Enter category name..."
                                value="{{ $category->name }}">
                            <div class="invalid-feedback"> Please enter category name.</div>
                        </div>

                        <div class="mb-4">
                            <label for="categorySlugLabel" class="form-label required">Slug <i
                                    class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label=""
                                    data-bs-original-title="This input auto generate from category name"></i></label>

                            <input type="text" class="form-control" name="category_slug" id="categorySlugLabel"
                                placeholder="Enter slug name..." aria-label="Enter slug name..."
                                value="{{ $category->slug }}">
                            <div class="invalid-feedback"> Please enter category slug.</div>
                        </div>
                        <div class="mb-4">
                            <label for="categoryDescriptionContent" class="form-label required">Description</label>
                            <input type="hidden" name="category_description" id="categoryDescriptionContent"
                                value="{{ $category->category_description }}">
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
                        <h4 class="card-header-title" id="image-title">Image<label for="categorySlugLabel"
                                class="form-label"><i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label=""
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

                                <span class="btn btn-white btn-manager btn-sm mb-2" id="openImageModal">File Manager</span>
                            </div>
                            <!-- Selected image-->
                            <div id="attachFile">
                                @if (isset($category->image_path))
                                    <div class="col-12 m-1 dz-preview dz-file-preview">
                                        <div class="dz-details d-flex flex-column align-items-center">
                                            <div class="dz-img flex-shrink-0">
                                                <img class="img-fluid dz-img-inner" alt="{{ $category->image_alt }}"
                                                    src="{{ route('get.file', ['filename' => $category->image_path]) }}">
                                                <input type="hidden" name="category_image_path" class="form-control"
                                                    value="{{ $category->image_path }}" id="imagePathInput">
                                                <input type="hidden" name="category_image_alt" class="form-control"
                                                    value="{{ $category->image_alt }}" id="imageAltInput">
                                            </div>
                                            <div class="dz-file-wrapper flex-grow-1 mt-2">
                                                <h6 class="dz-filename">
                                                    <span class="dz-title" data-dz-name="">
                                                        {{ Str::afterLast($category->image_path, '/') }}</span>
                                                </h6>
                                                <div class="dz-size" data-dz-size="">
                                                    <strong>{{ getFileSizeFormatted(base_path($category->image_path)) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <input type="hidden" class="form-control" name="category_image_path"
                                    id="imagePathInput" value="{{$category->image_path}}">
                                <input type="hidden" class="form-control text-center" name="category_image_alt"
                                    placeholder="Enter image alt..." id="imageAltInput" value="{{$category->image_alt}}">
                                <div class="invalid-feedback"> Please enter image alt.</div>
                            </div>
                        </div>
                        <!-- End Dropzone -->
                    </div>
                    <!-- Body -->
                </div>
                <!-- End Card -->
            </div>
            <!-- End Col -->

            <div class="col-lg-4">
                @if ($category->level != 0)
                    <div class="card mb-3">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title">Parent Category<label for="categorySlugLabel"
                                    class="form-label"><i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top" aria-label=""
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
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->slug }}" data-name="{{ $c->name }}"
                                            {{ $category->parent_slug != null && $c->slug == $category->parent_slug ? 'selected' : '' }}>
                                            {{ $c->name }}
                                            <small class="text-info">(level: {{ $c->level }})</small>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Body -->
                    </div>
                @endif
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
                                    @if ($category->status == 0)
                                        <input type="checkbox" name="category_status" value="{{ $category->status }}"
                                            class="form-check-input" id="availabilitySwitch1">
                                    @else
                                        <input type="checkbox" name="category_status" value="{{ $category->status }}"
                                            class="form-check-input" id="availabilitySwitch1" checked>
                                    @endif
                                </span>
                            </label>
                        </div>

                        <hr class="my-4">

                        <div id="release-in" style="display: none">
                            <div class="mb-4">
                                <label for="releaseLabel" class="form-label">Release<span class="col-8 col-sm-9 ms-0">
                                        <span class="text-dark"><i class="bi-question-circle text-body ms-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label=""
                                                data-bs-original-title="You can choose new release date if the status turn on"></i></span>
                                    </span>
                                </label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" name="category_release"
                                        id="releaseLabel" value="{{ $category->release }}">
                                    <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                        onclick="resetRelease()">
                                        <i class="bi bi-calendar-plus-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label class="form-label required d-block mb-2">Category type</label>
                            <div class="btn-group" role="group" aria-label="Category type">
                                <input type="radio" class="btn-check"  id="categoryBtn" autocomplete="off"
                                    onchange="setCategoryType(1)" @if ($category->type == 1) checked @endif>
                                <label class="btn btn-outline-primary" for="categoryBtn">Category</label>
                        
                                <input type="radio" class="btn-check" id="brandBtn" autocomplete="off"
                                    onchange="setCategoryType(2)" @if ($category->type == 2) checked @endif>
                                <label class="btn btn-outline-primary" for="brandBtn">Brand</label>
                            </div>
                        
                            <!-- Hidden input để submit giá trị -->
                            <input type="hidden" name="category_type" id="categoryTypeInput" value="{{$category->type}}">
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
                    <button type="submit" class="btn btn-primary w-full" onclick="handleSubmit(event)">Update</button>
                </div>
            </div>
        </form>
    @endif

    <script src="{{ asset('js/admin/single-select-search.js') }}"></script>
    <script src="{{ asset('js/admin/select-file-category.js') }}"></script>
    <script src="{{ asset('js/admin/category-edit.js') }}"></script>
    <script>
        document.getElementById('availabilitySwitch1').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('release-in').style.display = 'block';
                document.getElementById('releaseLabel').value =
                    '{{ $category->release }}';
                this.value = 1;
            } else {
                document.getElementById('release-in').style.display = 'none';
                document.getElementById('releaseLabel').value =
                    '{{ $category->release }}';
                this.value = 0;
            }
        });
    </script>
    <script src="{{ asset('js/admin/check-form-category.js') }}"></script>
@endsection
