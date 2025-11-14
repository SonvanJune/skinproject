@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Post')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.posts') }}">Posts</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Post</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Post</h1>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
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

    <form id="postForm" class="row needs-validation" novalidate action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card Post information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Post information</h4>
                </div>
                <!-- End Header -->
                <input type="hidden" id="action" value="create">

                <!-- Body -->
                <div class="card-body">
                    <!-- Post name -->
                    <div class="mb-4">
                        <label for="post-title" class="form-label required">Title</label>

                        <input type="text" oninput="generateSlug(this.value)" class="form-control" name="post_name"
                            id="postNameLabel" placeholder="Enter post title..." value="">
                        <div class="invalid-feedback"> Please enter post title.</div>
                    </div>

                    <!-- Post Slug -->
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label required">Slug <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Edit slug if you want to edit"
                                data-bs-original-title="Edit slug if you want to edit"></i></label>

                        <input type="text" class="form-control" name="post_slug" id="postSlugLabel"
                            placeholder="Enter slug name..." aria-label="Enter slug name..." value="" required>
                        <div class="invalid-feedback"> Please enter post slug.</div>
                    </div>

                    <label class="form-label required">Post content<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Write post content here."
                            data-bs-original-title="Write post content here."></i></label>
                    <input type="hidden" name="post_content" id="postContent">
                    @include('component.editor.editor')
                    <div class="invalid-feedback"> Please enter post content.</div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Post information -->

            <!-- Card Images -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title required">Image<i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="Post thumbnail image"
                            data-bs-original-title="Post thumbnail image"></i></h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message mb-3">
                            <img class="avatar avatar-xl avatar-4x3 mb-3" src="{{ asset('images/upload-file.svg') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-2"></p>

                            <span class="btn btn-white btn-sm btn-manager" id="openImageModal">File
                                Manager</span>
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
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Images -->
        </div>
        <!-- End Col -->

        <div class="col-lg-4">

            <!-- Card -->
            <div class="card mb-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Status && Release</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <div class="mb-4">
                        <label class="row form-check form-switch" for="availabilitySwitch1">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">Post Status <i class="bi-question-circle text-body ms-1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        aria-label="If you turn this button on, your post will be visible, if you turn it off, your category will be hidden."
                                        data-bs-original-title="If you turn this button on, your post will be visible, if you turn it off, your category will be hidden."></i></span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input type="checkbox" name="post_status" class="form-check-input"
                                    id="availabilitySwitch1" checked>
                            </span>
                        </label>
                    </div>

                    <div id="release-in" style="display: none">
                        <div class="mb-4">
                            <label for="releaseLabel" class="form-label">Post Release</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="post_release"
                                    id="releaseLabel" value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">
                                <button type="button" class="btn btn-outline-secondary" title="Refresh to now"
                                    onclick="resetRelease()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                                <div class="invalid-feedback"> The release date can not smaller than now.</div>
                            </div>
                        </div>
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

    @include('component.modal.confirmModal', [
        'subject' => 'post',
        'action' => 'create',
    ])

    <div id="modalContainer"></div>

    <script src="{{ asset('js/admin/select-file-post.js') }}"></script>
    <script src="{{ asset('js/admin/post-create.js') }}"></script>
    <script src="{{ asset('js/admin/check-form-post.js') }}"></script>
@endsection
