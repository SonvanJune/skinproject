@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/admin-category.css') }}">
@endsection

@section('title', 'Categories Admin')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Categories <span
                        class="badge bg-soft-dark text-dark ms-2">{{ count($categories) }} items</span></h1>
            </div>
            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.categories.create', ['category_slug' => null]) }}">
                    <i class="bi bi-plus-circle me-2"></i> Add category
                </a>
            </div>
        </div>
        <div id="routeWrapper" data-route-edit="{{ route('admin.categories.edit', ['category_slug' => ':slug']) }}"
            data-route-create="{{ route('admin.categories.create', ['category_slug' => ':slug']) }}"
            data-route-delete="{{ route('admin.categories.delete', ['category_slug' => ':slug']) }}">
        </div>
        <div class="search-cate">
            <div class="search-container text-center">
                <button id="toggleSearch" class="btn btn-primary search-btn mb-3">
                    <i class="bi bi-search me-2"></i><span id="titleSearch">Search Category</span>
                </button>

                <div id="searchBox" class="search-box" data-route-search="{{ route('admin.categories.search') }}"
                    data-csrf-search="{{ csrf_token() }}">
                    <input type="text" id="search-input" class="form-control search-input"
                        placeholder="Search Categories..." oninput="searchCategoryApi()">
                </div>

                <div id="searchResultCount"
                    class="bg-light border border-primary rounded px-3 py-2 mt-2 text-primary small"
                    style="display: none;">
                    🔍 Found <span id="result-count-number" class="fw-semibold text-dark">0</span> matching categories.
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    @if (session('success'))
        <div class="alert alert-success" style="width: 100%; margin-left: 32px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="width: 100%; margin-left: 32px;">
            {{ session('error') }}
        </div>
    @endif

    <div id="category-search" class="category-search" style="width: 100%;">
    </div>

    <!-- Categories -->
    <div id="category-tree" class="category-tree" style="width: 100%;">
    </div>
    <!-- End Categories -->

    @include('component.modal.deleteConfirmModal', [
        'subject' => 'category',
    ])

    <script>
        const categories = @json($categories);
    </script>
    <script src="{{ asset('js/admin/highlight.js') }}"></script>
    <script>
        function renderCategories(categories) {
            const ul = document.createElement("ul");

            categories.forEach((category) => {
                const li = document.createElement("li");
                li.classList.add("category-item");

                // Nội dung category
                const div = document.createElement("div");
                if (category.children && category.children.length > 0) {
                    div.innerHTML = `
                                <i class="fa-solid fa-caret-right toggle-icon"></i>
                                <span>${category.name}</span>
                                <span class="ms-2 text-muted">
                                <i class="fa-solid fa-box toggle-icon"></i> ${category.product_count} 
                                </span>
                                <span class="ms-2 text-muted">
                                <i class="bi bi-list-ul toggle-icon"></i> ${category.children_category_count}
                                </span>
                            `;
                } else {
                    div.innerHTML = `
                                <i class="fa-solid fa-ban toggle-icon"></i>
                                <span>${category.name}</span>
                                <span class="ms-2 text-muted">
                                <i class="fa-solid fa-box toggle-icon"></i> ${category.product_count}
                                </span>
                            `;
                }

                li.appendChild(div);

                // Xử lý children
                if (category.children && category.children.length > 0) {
                    const childrenContainer = renderCategories(category.children);
                    childrenContainer.classList.add("category-children");
                    li.appendChild(childrenContainer);

                    // Toggle mở/đóng khi click
                    div.addEventListener("click", () => {
                        const icon = div.querySelector(".toggle-icon");
                        icon.classList.toggle("rotate");
                        childrenContainer.classList.toggle("open");
                    });
                }

                ul.appendChild(li);

                li.addEventListener("contextmenu", function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const deleteUrl =
                        "{{ route('admin.categories.delete', ['category_slug' => ':category_slug']) }}"
                        .replace(
                            ":category_slug",
                            category.slug
                        );

                    // Remove any existing menu
                    const existingMenu = document.querySelector("#menu-files-box");
                    if (existingMenu) {
                        existingMenu.remove();
                    }

                    // Create a new context menu
                    const menuBox = document.createElement("div");
                    menuBox.className = "menu-box";
                    menuBox.id = "menu-files-box";
                    menuBox.innerHTML = `
                        <ul>
                            <li id="create-category" class="menu-item">
                                
                                <div class="item-icon">
                                <i class="bi bi-plus-square"></i>
                                </div>
                                <div class="item-text">
                                    Add
                                </div>
                            </li>
                            <li id="edit-category" class="menu-item">
                                
                                <div class="item-icon">
                                <i class="bi-pencil-fill"></i>
                                </div>
                                <div class="item-text">
                                    Edit
                                </div>
                            </li>
                            <li id="delete-category" class="menu-item">
                                <div class="item-icon">
                                <i class="bi-trash"></i>
                                </div>
                                <div class="item-text">
                                Delete
                                </div>
                                <form id="delete-category-form" action="${deleteUrl}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </li>
                        </ul>
                    `;

                    const x = event.clientX;
                    const y = event.clientY;

                    menuBox.style.position = "absolute";
                    menuBox.style.top = `${y}px`;
                    menuBox.style.left = `${x}px`;

                    document.body.appendChild(menuBox);

                    // Prevent clicks on the menu from closing it
                    menuBox.addEventListener("click", function(event) {
                        event.stopPropagation();
                    });

                    // Add click event listeners to menu items
                    menuBox.querySelectorAll(".menu-item").forEach((item) => {
                        item.addEventListener("click", function() {
                            switch (item.id) {
                                case "create-category":
                                    const urlCreate =
                                        "{{ route('admin.categories.create', ':category_slug') }}"
                                        .replace(
                                            ":category_slug",
                                            category.slug
                                        );
                                    window.location.href = urlCreate;
                                    break;
                                case "edit-category":
                                    const urlEdit =
                                        "{{ route('admin.categories.edit', ':category_slug') }}"
                                        .replace(
                                            ":category_slug",
                                            category.slug
                                        );
                                    window.location.href = urlEdit;
                                    break;
                                case "delete-category":
                                    const form = document.getElementById(
                                        "delete-category-form"
                                    );

                                    const modal = new bootstrap.Modal(
                                        document.getElementById(
                                            "deleteConfirmationModal"
                                        )
                                    );
                                    modal.show();

                                    document.getElementById(
                                        "confirmDeleteBtn"
                                    ).onclick = function() {
                                        form.submit();
                                        modal.hide();
                                    };
                                    break;
                                default:
                                    break;
                            }
                        });
                    });

                    // Tắt dropdown khi bấm ra ngoài
                    document.addEventListener("click", () => {
                        menuBox.remove();
                    });
                });
            });

            return ul;
        }

        // Render tree ra HTML
        document
            .getElementById("category-tree")
            .appendChild(renderCategories(categories));
    </script>
    <script src="{{ asset('js/admin/search-cate.js') }}"></script>
@endsection
