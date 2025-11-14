@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Post')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.posts') }}">Posts</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Post</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Post</h1>

                <div class="mt-2">
                    <a class="text-body" href="#" data-bs-toggle="modal" data-bs-target="#view-post-modal"
                        onclick="openView()">
                        <i class="bi-eye me-1"></i> Preview
                    </a>
                </div>

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

    <form id="postForm" class="row needs-validation" novalidate action="{{ route('admin.posts.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card Post information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Post information</h4>
                </div>
                <!-- End Header -->
                <input type="hidden" id="action" value="edit">

                <!-- Body -->
                <div class="card-body">
                    <!-- Post name -->
                    <div class="mb-4">
                        <label for="post-title" class="form-label required">Title</label>

                        <input type="text" oninput="generateSlug(this.value)" class="form-control" name="post_name"
                            id="postNameLabel" placeholder="Enter post title..." value="{{ $postDTO->name }}">
                        <div class="invalid-feedback"> Please enter post title.</div>
                    </div>

                    <!-- Post Slug -->
                    <div class="mb-4">
                        <label for="productSlugLabel" class="form-label required">Slug <i
                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Edit slug if you want to edit"
                                data-bs-original-title="Edit slug if you want to edit"></i></label>

                        <input type="text" class="form-control" name="post_slug" id="postSlugLabel"
                            placeholder="Enter slug name..." aria-label="Enter slug name..." value="{{ $postDTO->slug }}"
                            required>
                        <div class="invalid-feedback"> Please enter post slug.</div>
                    </div>
                    <input type="hidden" class="form-control" name="post_id" value="{{ $postDTO->id }}">
                    <input type="hidden" class="form-control" name="updated_at"
                        value="{{ now()->toDateString() }}T{{ now()->toTimeString() }}">

                    <label class="form-label required">Post content<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Write post content here."
                            data-bs-original-title="Write post content here."></i></label>
                    <input type="hidden" name="post_content" id="postContent" value="{{ $postDTO->content }}">
                    @include('component.editor.editor')
                    <div class="invalid-feedback"> Please enter post content.</div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Post information -->

            <!-- Card Thumbnail -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title required">Image<i class="bi-question-circle text-body ms-1"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Post thumbnail image"
                            data-bs-original-title="Post thumbnail image"></i></h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Dropzone -->
                    <div class="mb-4 dz-dropzone dz-dropzone-card dz-clickable">
                        <div class="dz-message mb-3">
                            <span class="btn btn-white btn-sm mb-2 btn-manager" id="openImageModal">File Manager</span>
                            <p class="form-text">Choose new thumbnail</p>
                        </div>
                        <div id="attachFile">
                            <!-- Dropzone -->
                            @if (isset($postDTO->image_path))
                                <div class="col-12 m-1 dz-preview dz-file-preview">
                                    <div class="d-flex justify-content-end dz-close-icon">
                                        <small class="bi-x remove-image" data-index="{{ $postDTO->slug }}"></small>
                                    </div>
                                    <div class="dz-details d-flex flex-column align-items-center">
                                        <div class="dz-img flex-shrink-0">
                                            <img class="img-fluid" alt="{{ $postDTO->image_alt }}"
                                                src="{{ route('get.file', ['filename' => $postDTO->image_path]) }}">
                                            <input type="hidden" name="post_image_path"
                                                value="{{ $postDTO->image_path }}">
                                        </div>
                                        <div class="dz-file-wrapper flex-grow-1 mt-2">
                                            <h6 class="dz-filename">
                                                <span class="dz-title" data-dz-name="">
                                                    {{ Str::afterLast($postDTO->image_path, '/') }}</span>
                                            </h6>
                                            <div class="dz-size" data-dz-size="">
                                                <strong>{{ getFileSizeFormatted(base_path($postDTO->image_path)) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control text-center" name="post_image_alt"
                                            id="imageAltLabel_${index}" placeholder="Enter image alt..."
                                            value="{{ $postDTO->image_alt }}" required>
                                        <div class="invalid-feedback"> Please enter image alt.</div>
                                    </div>
                                </div>
                            @endif
                            <!-- End Dropzone -->
                        </div>
                    </div>
                    <div>
                        <input type="hidden" class="form-control" name="post_image_path" id="imagePathInput"
                            value="{{ $postDTO->image_path }}">
                        <input type="hidden" class="form-control text-center" name="post_image_alt"
                            placeholder="Enter image alt..." id="imageAltInput" value="{{ $postDTO->image_alt }}">
                        <div class="invalid-feedback"> Please choose image.</div>
                    </div>
                </div>
                <!-- Body -->
            </div>
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
                                    id="availabilitySwitch1" @if ($postDTO->status == 1) checked @endif>
                            </span>
                        </label>
                    </div>

                    <div id="release-in" style="display: none">
                        <div class="mb-4">
                            <label for="releaseLabel" class="form-label">Post Release</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" name="post_release" id="releaseLabel"
                                    value="">
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
                <button type="submit" class="btn btn-primary w-full" onclick="handleSubmit(event)">Update</button>
            </div>
        </div>
    </form>

    {{-- Modal to view post --}}
    <div class="modal fade" id="view-post-modal" tabindex="-1" aria-labelledby="View Post" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" style="height: auto">
                <div class="row p-3">
                    <div class="text-end">
                        <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="col-12">
                        <div class="card mb-4 shadow-sm">
                            <img src="" class="card-img-top" alt="">
                            <div class="card-body">
                                <h1 class="card-title"></h1>
                                <p class="text-muted">Posted at <span id="publish-date"></span> by <span
                                        id="author-name">{{ $user->user_first_name . ' ' . $user->user_last_name }}</span>
                                </p>
                                <p class="card-text">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('component.modal.confirmModal', [
        'subject' => 'post',
        'action' => 'update',
    ])
    <script>
        const getPostReleaseDate = @json($postDTO->release);
    </script>
    <script src="{{ asset('js/admin/select-file-post.js') }}"></script>
    <script src="{{ asset('js/admin/post-edit.js') }}"></script>
    <script src="{{ asset('js/admin/check-form-post.js') }}"></script>
    <script>
        //elements
        const postTitle = document.querySelector("input[name='post_name']");
        const postSlug = document.querySelector("input[name='post_slug']");
        const postReleaseDate = document.querySelector("input[name='post_release']");
        const postContent = document.querySelector("input[name='post_content']");
        const imagePath = document.querySelector("input[name='post_image_path']");
        const imageAlt = document.querySelector("input[name='post_image_alt']");

        //preview post
        const previewPostImage = document.querySelector('#view-post-modal .card-img-top');
        const previewPostTitle = document.querySelector('#view-post-modal .card-title');
        const previewPostContent = document.querySelector('#view-post-modal .card-text');
        const previewPostReleaseDate = document.querySelector('#view-post-modal #publish-date');

        //editor
        const editor = quill.root;
        editor.innerHTML = postContent.value;

        function openView() {
            previewPostImage.src = routeGetFileTemplate.replace(":filename", imagePath.value);
            previewPostImage.alt = imageAlt.value;
            previewPostTitle.textContent = postTitle.value;
            previewPostContent.innerHTML = editor.innerHTML;
            previewPostReleaseDate.textContent = postReleaseDate.value;
        }
    </script>
@endsection
