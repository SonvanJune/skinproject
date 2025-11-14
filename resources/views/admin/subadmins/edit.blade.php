@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Subadmin')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.subadmins') }}">Subadmins</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Subadmin</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Subadmin</h1>
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

    <form id="edit-sub-admin-form" action="{{ route('admin.subadmins.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Subadmin information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <input id="edit-sub-admin-id" name="sub_user_id" hidden readonly value="{{ $subadmin->user_id }}">

                    <div class="input-group pb-2">
                        <div class="col-md-6 fn pe-1">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" disabled
                                value="{{ $subadmin->user_first_name }}" />
                            <div class="invalid-feedback">
                                Please enter first name
                            </div>
                        </div>
                        <div class="col-md-6 ln ps-1">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" disabled
                                value="{{ $subadmin->user_last_name }}" />
                            <div class="invalid-feedback">
                                Please enter last name
                            </div>
                        </div>
                    </div>

                    <div class="input-group pb-2">
                        <div class="col-md-6 fn pe-1">
                            <label for="email" class="form-label pe-1">Email address</label>
                            <input type="email" class="form-control" id="email" disabled
                                value="{{ $subadmin->user_email }}" />
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>

                        <div class="col-md-6 fn ps-1">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="birthday" disabled
                                value="{{ $subadmin->user_birthday ? \Carbon\Carbon::parse($subadmin->user_birthday)->format('Y-m-d') : '' }}" />
                            <div class="invalid-feedback">
                                Please enter birthday.
                            </div>
                        </div>
                    </div>

                    <div class="input-group pb-2">
                        <div class="col-md-6 ln pe-1">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" disabled
                                value="{{ $subadmin->user_phone }}" />
                            <div class="invalid-feedback">
                                Please enter phone.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header d-flex">
                <h4 class="card-header-title">Role List</h4>

                <a type="button" class="btn btn-primary p-1 ms-auto" href="{{ route('admin.roles') }}">Manage
                    Roles</a>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3 row px-3">
                    @foreach ($roles as $role)
                        @if ($role->editable)
                            <div class="form-check col-xl-4 col-md-6 col-12">
                                <input class="form-check-input edit-sub-admin-role" type="checkbox"
                                    {{ $subadmin->roles->pluck('role_id')->contains($role->role_id) ? 'checked' : '' }}
                                    value="{{ $role->role_id }}" name="list_role[]">
                                <label class="form-check-label">
                                    {{ $role->role_name }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-3 text-end">
            <button type="submit" onclick="event.preventDefault(); confirmEdit(this);" class="btn btn-primary btn-sm">Save
                changes</button>
        </div>
    </form>

    @include('component.modal.ConfirmModal', [
        'subject' => 'roles',
        'action' => 'save new role',
    ])

    <script>
        /** 
         * to show modal to confirm saving
         */
        function confirmEdit(element) {
            // Submit the form after storing the tracking code
            const form = element.closest('form');

            const modal = new bootstrap.Modal(
                document.getElementById(
                    "confirmationModal"
                )
            );
            modal.show();

            document.getElementById(
                "confirmBtn"
            ).onclick = function() {
                form.submit();
                modal.hide();
            };
        }
    </script>

    <script src="{{ asset('js/admin/mutil-select-search.js') }}"></script>
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
        // Get the list of files from sessionStorage when the page loads
        let selectedFiles = JSON.parse(sessionStorage.getItem("filesSection")) || [];
        // Get images
        const productImages = [];

        // Render files if there are any in sessionStorage
        const renderFiles = () => {
            const attachFilesContainer = document.getElementById("attachFiles");
            attachFilesContainer.innerHTML = "";

            // Loop through each file and create an element to display it
            selectedFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.classList.add('col-12', 'm-1', 'dz-preview', 'dz-file-preview');

                const fileItem = `
                <div class="d-flex justify-content-end dz-close-icon">
               <small class="bi-x" data-index="${index}"></small>
            </div>
            <div class="dz-details d-flex">
                <div class="dz-img flex-shrink-0">
                    <img class="img-fluid dz-img-inner" alt="${file.name}" src="${file.filePath}">
                </div>
                <div class="dz-file-wrapper flex-grow-1">
                    <h6 class="dz-filename">
                        <span class="dz-title" data-dz-name="">${file.name}</span>
                    </h6>
                    <div class="dz-size" data-dz-size=""><strong>${file.size}</strong></div>
                </div>
            </div>
            <div>

                <input type="text" class="form-control" name="product_image_alt" id="imageAltLabel_${index}"
                    placeholder="Enter image alt..." value="${file.name}"
                    required>
                <div class="invalid-feedback"> Please enter image alt.</div>
            </div>
            `;

                // Set innerHTML for the item
                item.innerHTML = fileItem;

                // Append the item to the container
                attachFilesContainer.appendChild(item);

                // Add event listener to the close icon
                const closeIcon = item.querySelector('.bi-x');
                closeIcon.addEventListener('click', function() {
                    // Get the index from the data attribute
                    const index = parseInt(this.getAttribute('data-index'));

                    // Remove the item from the selectedFiles array
                    selectedFiles.splice(index, 1);

                    // Update the sessionStorage with the new array
                    sessionStorage.setItem("filesSection", JSON.stringify(selectedFiles));

                    // Re-render the files after deletion
                    renderFiles();
                });
            });
        };

        // Call the renderFiles function when the page loads to display files stored in sessionStorage
        renderFiles();

        // Listen for the selectedFilesUpdated event to update when files change
        window.addEventListener("selectedFilesUpdated", function() {
            selectedFiles = JSON.parse(sessionStorage.getItem("filesSection")) || [];
            renderFiles(); // Re-render the files list
        });

        // Lắng nghe sự kiện khi người dùng thay đổi nội dung của altText
        document.querySelectorAll('input[id^="imageAltLabel_"]').forEach(input => {
            input.addEventListener('input', function() {
                const index = this.id.split('_')[1]; // Lấy index từ ID
                const altText = this.value;

                // Cập nhật giá trị altText trong mảng productImages nếu cần
                productImages[index].product_image_alt = altText;
            });
        });

        // Collect product image data and submit via form
        document.getElementById("productForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission

            // Get content HTML from editor
            var content = quill.root.innerHTML;
            document.querySelector('#postContent').value = content;

            // Loop through selected files and collect the paths and alt texts
            selectedFiles.forEach((file, index) => {
                const altText = document.getElementById(`imageAltLabel_${index}`).value;
                productImages.push({
                    product_image_path: file.filePath,
                    product_image_alt: altText
                });
            });

            // Set the collected productImages data as a JSON string in the hidden input
            const productImagesHidden = document.getElementById("productImagesHidden");
            productImages.forEach((image, index) => {
                // Tạo input cho image_name
                const inputName = document.createElement("input");
                inputName.type = "hidden";
                inputName.name = `productImages[${index}][product_image_path]`;

                const url = new URL(image.product_image_path);
                inputName.value = url.pathname.slice(1);
                productImagesHidden.appendChild(inputName);

                // Tạo input cho image_alt
                const inputAlt = document.createElement("input");
                inputAlt.type = "hidden";
                inputAlt.name = `productImages[${index}][product_image_alt]`;
                inputAlt.value = image.product_image_alt;
                productImagesHidden.appendChild(inputAlt);
            });

            this.submit();
        });


        // Convert product name to slug
        document.getElementById('productNameLabel').addEventListener('input', function() {
            const productName = this.value;

            const slug = productName
                .toLowerCase()
                .normalize("NFD") // Convert accented characters to non-accented ones
                .replace(/[\u0300-\u036f]/g, '') // Remove accent marks
                .replace(/đ/g, 'd')
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/[^\w\-]+/g, ''); // Remove any characters that are not letters, numbers, or hyphens

            document.getElementById('productSlugLabel').value = slug;
        });

        const priceInput = document.getElementById('priceNameLabel');
        const percentInput = document.getElementById('perHundredLabel');
        const priceInputField = document.getElementById('couponPriceLabel');

        // Function to calculate discount amount from percentage
        function calculateDiscountByPercent(price, percent) {
            return price - (price * percent) / 100;
        }

        // Function to calculate discount percentage from discount amount
        function calculateDiscountPercent(price, discountPrice) {
            return (discountPrice / price) * 100;
        }

        // Listen for changes in the price input
        priceInput.addEventListener('input', function() {
            const price = parseFloat(priceInput.value); // Get the product price
            const discountPrice = parseFloat(priceInputField.value); // Get the discount amount

            if (!isNaN(price) && !isNaN(discountPrice)) {
                // Calculate discount percentage
                const percent = calculateDiscountPercent(price, discountPrice);
                percentInput.value = percent; // Update the discount percentage
            }
        });

        // Listen for changes in discount percentage input
        percentInput.addEventListener('input', function() {
            const price = parseFloat(priceInput.value);
            const percent = parseFloat(percentInput.value);

            if (!isNaN(price) && !isNaN(percent)) {
                const discountPrice = calculateDiscountByPercent(price, percent);
                priceInputField.value = discountPrice;
            }
        });

        // Listen for changes in discount amount input
        priceInputField.addEventListener('input', function() {
            const price = parseFloat(priceInput.value);
            const discountPrice = parseFloat(priceInputField.value);

            if (!isNaN(price) && !isNaN(discountPrice)) {
                const percent = calculateDiscountPercent(price, discountPrice);
                percentInput.value = percent;
            }
        });
    </script>

    {{-- Processing editing --}}
    <script>
        //elements
        const postTitle = document.querySelector("input[name='post_name']");
        const postSlug = document.querySelector("input[name='post_slug']");
        const postReleaseDate = document.querySelector("input[name='post_release']");
        const postContent = document.querySelector("input[name='post_content']");
        const postImageAlt = document.querySelector("input[name='post_image_alt']");

        //preview post
        const previewPostImage = document.querySelector('#view-post-modal .card-img-top');
        const previewPostTitle = document.querySelector('#view-post-modal .card-title');
        const previewPostContent = document.querySelector('#view-post-modal .card-text');
        const previewPostReleaseDate = document.querySelector('#view-post-modal #publish-date');

        //editor
        const editor = quill.root;

        //generate a default slug
        function generateSlugAndImageAlt(title) {
            const slug = title
                .toLowerCase()
                .normalize("NFD") // Convert accented characters to non-accented ones
                .replace(/[\u0300-\u036f]/g, '') // Remove accent marks
                .replace(/đ/g, 'd')
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/[^\w\-]+/g, ''); // Remove any characters that are not letters, numbers, or hyphens

            postSlug.value = slug;
            postImageAlt.value = title;
        }

        /**
         * Populates the details for viewing the post's content.
         * 
         * @param {string} title
         * @param {string} imagePath image path
         * @param {string} imageAlt image alt
         * @param {string} content
         * @param {string} releaseDate released date
         * @param {string} author
         */
        function openView() {
            // postImage.src = imagePath;
            // postImage.alt = imageAlt;
            previewPostTitle.textContent = postTitle.value;
            const content = quill.root.innerHTML;
            previewPostContent.innerHTML = content;
            previewPostReleaseDate.textContent = postReleaseDate.value || getDefaultDate();
        }

        //get the default as current date
        function getDefaultDate() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
            const day = ('0' + currentDate.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
        }

        document.getElementById("post-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission

            // Get content HTML from editor
            postContent.value = editor.innerHTML;

            this.submit(); // Submit
        });
    </script>
@endsection
