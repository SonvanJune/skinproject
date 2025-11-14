@extends('layouts.admin')

@section('title', 'Admin-Posts')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/post-detail.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Posts <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span>
                </h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.posts.create') }}"><i class="bi bi-plus-circle me-2"></i> Add
                    Post</a>
            </div>

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

    @php
        $paginationRoute = '/admin/posts?';
        $paginationRoute .= 'per_page=' . $paginatedDTO->per_page;
        $paginationRoute .= '&key=' . $paginatedDTO->key;
        $paginationRoute .= '&page=';

        $offset = 1;
        $bridge = 3;
        $has_first_offset = false;
        $has_last_offset = false;
    @endphp

    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
                <!-- Search -->
                <form id="searchBox" data-route-search="{{ route('admin.posts.search') }}"
                    data-csrf-search="{{ csrf_token() }}" data-link={{ asset('') }}
                    data-route-edit="{{ route('admin.posts.edit', ['slug' => ':slug']) }}"
                    data-route-delete="{{ route('admin.posts.destroy', ['slug' => ':slug']) }}">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>

                        <div class="input-group input-group-merge input-group-flush">
                            <div class="input-group-prepend input-group-text">
                                <i class="bi-search"></i>
                            </div>
                            <input id="search-input" type="search" class="form-control" placeholder="Search Post..."
                                aria-label="Search..." oninput="searchPostApi()" name="key" value="{{ request()->get('key') }}">

                            <div id="searchResultCount"
                                class="bg-light border border-primary rounded px-3 py-2 mt-2 text-primary small"
                                style="display: none;">
                                🔍 Found <span id="result-count-number" class="fw-semibold text-dark">0</span> matching
                                posts.
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End Search -->
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Per Page -->
                <form class="input-per-pages" action="{{ route('admin.posts') }}" id="form-page">
                    @foreach (request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <button type="button" class="btn btn-white w-100" aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi-list-task me-1"></i> <input type="number" step="1" min="1" max="100"
                            class="text-end border-0 form2-control" name="per_page"
                            value="{{ count($paginatedDTO->data) > 0 ? $paginatedDTO->total : 0 }}">
                        /
                        <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $paginatedDTO->totalArr }}</span>
                    </button>
                </form>
                <!-- End Per Page -->

                <!-- Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn btn-white w-100" id="showHideDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi-table me-1"></i> Columns <span class="badge bg-soft-dark text-dark rounded-circle ms-1"
                            id="count-column"></span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end dropdown-card" aria-labelledby="showHideDropdown"
                        style="width: 15rem;">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-grid gap-3" id="column-filter-container">
                                    <!-- Form Switch -->
                                    <label class="row form-check form-switch" for="toggleColumn_product">
                                        <span class="col-8 col-sm-9 ms-0">
                                            <span class="me-2">Column</span>
                                        </span>
                                        <span class="col-4 col-sm-3 text-end">
                                            <input type="checkbox" class="form-check-input" id="toggleColumn_product"
                                                checked="" onchange="checkColumn(this.checked)">
                                        </span>
                                    </label>
                                    <!-- End Form Switch -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Dropdown -->
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                <table id="role-table"
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer"
                    role="grid" aria-describedby="datatable_info" style="width: 1271px;">
                    <thead class="thead-light">
                        <tr id="column-in-tabel-filter-container">
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="post-search">
                    </tbody>

                    <tbody id="post-table">
                        @foreach ($paginatedDTO->data as $index => $post)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    <a class="btn btn-white btn-sm"
                                        href="{{ route('admin.posts.edit', ['slug' => $post->slug]) }}">
                                        <i class="bi-pencil-fill me-1"></i>
                                    </a>

                                    <form action="{{ route('admin.posts.destroy', ['slug' => $post->slug]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-white btn-sm" href="#"
                                            onclick="event.preventDefault(); confirmDelete(this);">
                                            <i class="bi-trash dropdown-item-icon"></i>
                                        </a>
                                    </form>

                                    <a class="btn btn-white btn-sm"
                                        onclick="openView(this.dataset.name, this.dataset.image_path, this.dataset.image_alt, this.dataset.content, this.dataset.release, this.dataset.author)"
                                        href="#" data-bs-toggle="modal" data-bs-target="#view-post-modal"
                                        data-name="{{ $post->name }}" data-image_path="{{ route('get.file', ['filename' => $post->image_path]) }}"
                                        data-image_alt="{{ $post->image_alt }}" data-content="{{ $post->content }}"
                                        data-release="{{ $post->release }}" data-author="{{ $post->author }}">
                                        <i class="bi bi-eye-fill me-1"></i>
                                    </a>
                                </td>

                                <td class="c0">
                                    <a class="d-flex align-items-center justify-content-center"
                                        href="{{ route('get.file', ['filename' => $post->image_path]) }}">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-lg" width="100%" onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'"
                                                src="{{ route('get.file', ['filename' => $post->image_path]) }}" alt={{ $post->image_alt }}>
                                        </div>
                                    </a>
                                </td>

                                <td class="c1">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($post->slug))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $post->slug }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c2">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($post->name))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $post->name }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td class="c3">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            <p
                                                style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                {{ $post->release }}
                                            </p>
                                        </div>
                                    </a>
                                </td>
                                <td class="c4">
                                    @if ($post->author)
                                        <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                            {{ $post->author }}
                                        </p>
                                    @else
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            SYSTEM POST
                                        </button>
                                    @endif
                                </td>
                                <td class="c5">
                                    <a class="d-flex align-items-center justify-content-center">
                                        @if ($post->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-primary">Disable</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="c6">
                                    <a class="d-flex align-items-center justify-content-center">
                                        <div class="flex-shrink-0 text-start">
                                            @if (isset($post->updated_at))
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    {{ $post->updated_at }}
                                                </p>
                                            @else
                                                <p
                                                    style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                                    NO TITLE
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="not-found"
                    class="py-2 d-flex flex-column align-items-center justify-content-center text-muted fade-in w-100">
                </div>
            </div>
        </div>
        <!-- End Table -->

    </div>

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
                                        id="author-name"></span></p>
                                <p class="card-text">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}

    <!-- Footer -->
    <div class="card-footer mt-2">
        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
            <div class="col-sm mb-2 mb-sm-0">
            </div>
            <!-- End Col -->

            <div class="col-sm-auto" id="pagination-post">
                <div class="d-flex justify-content-center justify-content-sm-end mt-3">
                    @include('component.pagination.index', [
                            'paginatedDTO' => $paginatedDTO,
                            'subject' => 'posts'
                        ])
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Footer -->
    </div>
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'post',
    ])
    <script src="{{ asset('js/admin/highlight.js') }}"></script>
    <script src="{{ asset('js/admin/post-table.js') }}"></script>
    <script src="{{ asset('js/admin/search-post.js') }}"></script>
@endsection
