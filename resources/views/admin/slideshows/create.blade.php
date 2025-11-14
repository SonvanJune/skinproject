@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/image-range.css') }}">
@endsection

@section('title', 'Add Slideshow')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.slideshows') }}">SlideShows</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Slideshow</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Slideshow</h1>

                <div class="mt-2">
                    <a class="text-body me-3" href="javascript:;">
                        <i class="bi-clipboard me-1"></i> Duplicate
                    </a>
                    <a class="text-body" href="javascript:;">
                        <i class="bi-eye me-1"></i> Preview
                    </a>
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->
    @if (session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="page-header">
        <div class="slide-range">
            @foreach ($slideshowImages as $slideImage)
                <div class="slide-item">
                    <div class="slide-image">
                        <img src="{{ route('get.file', ['filename' => $slideImage->slideshow_image_url]) }}" alt="">
                    </div>
                    <div class="slide-dot"></div>
                    <div class="slide-index">{{ $slideImage->slideshow_image_index }}</div>

                </div>
            @endforeach
        </div>
    </div>

    <form class="row needs-validation justify-content-center" novalidate action="{{ route('admin.slideshows.store') }}"
        method="POST">
        @csrf
        @method('POST')
        <div class="col-lg-8 mb-3 mb-lg-0">
            <!-- Card -->
            <div class="card mb-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Index</h4>
                    <p>Please enter a large number to allow space if you want to insert an image in the middle</p>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <!-- Status -->
                    <div class="mb-4">
                        <input type="number" class="form-control" name="slideshow_image_index" id="statusLabel"
                            placeholder="eg. 10" aria-label="eg. 10">
                        <div class="invalid-feedback"> Please enter slideshow index.</div>
                    </div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card -->

            <!-- Card Images -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title">Image</h4>
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

                            <span class="btn btn-white btn-manager btn-sm" id="openImageModal">File
                                Manager</span>
                        </div>

                        <div id="attachFile">
                        </div>

                        <div id="productImagesHidden"></div>
                        {{-- <input type="hidden" name="productImages" id="productImages"> --}}
                    </div>
                    <!-- End Dropzone -->
                    <!-- Product name -->

                </div>
                <!-- Body -->
            </div>
            <!-- End Card Images -->

        </div>

        <!-- Button submit -->
        <div class="col-lg-8">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary w-full">Save</button>
            </div>
        </div>
    </form>
    <!-- Modal file manager Container -->
    <div id="modalContainer"></div>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    <script>
        // Open modal
        document.getElementById('openImageModal').addEventListener('click', () => {
            renderModal('selectImageModal');
        });

        // Render files if there are any in sessionStorage
        const renderFile = (selectedFile) => {
            const attachFileContainer = document.getElementById("attachFile");
            attachFileContainer.innerHTML = "";

            if (selectedFile) {
                const url = routeGetFileTemplate.replace(":filename", selectedFile.filePath);
                const path = selectedFile.filePath;

                const item = document.createElement('div');
                item.classList.add('col-12', 'm-1', 'dz-preview', 'dz-file-preview');

                const fileItem = `
            <div class="d-flex justify-content-end dz-close-icon">
               <small class="bi-x delete-selected-file"></small>
            </div>
            <div class="dz-details d-flex flex-column align-items-center">
                <div class="dz-img flex-shrink-0">
                    <img class="img-fluid dz-img-inner" alt="${selectedFile.name}" src="${url}">
                </div>
                <div class="dz-file-wrapper flex-grow-1 mt-2">
                    <h6 class="dz-filename">
                        <span class="dz-title" data-dz-name="">${selectedFile.name}</span>
                    </h6>
                    <div class="dz-size" data-dz-size=""><strong>${selectedFile.size}</strong></div>
                </div>
            </div>
            <div>
                <input type="hidden" class="form-control" name="slideshow_image_url" value="${path}" required>
                <input type="text" class="form-control text-center" name="slideshow_image_alt" placeholder="Enter image alt..." value="${selectedFile.name}" required>
                <div class="invalid-feedback"> Please enter image alt.</div>
            </div>`;

                // Set innerHTML for the item
                item.innerHTML = fileItem;

                // Append the item to the container
                attachFileContainer.appendChild(item);

                // Add event listener to the close icon
                const closeIcon = item.querySelector('.delete-selected-file');
                closeIcon.addEventListener('click', function() {
                    selectedFile = null;
                    // Re-render the files after deletion
                    renderFile();
                });
            }
        };

        // Listen for the selectedFilesUpdated event to update when files change
        window.addEventListener("selectedFileUpdated", function() {
            const selectedFile = event.detail;
            renderFile(selectedFile); // Re-render the files list
        });
    </script>
@endsection
