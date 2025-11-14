@extends('layouts.admin')

@section('title', 'Admin-SubAdmins')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Sub Admins <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span></h1>
            </div>

            <div class="col-sm-auto">
                <a id="btn-add-subamin" class="btn btn-primary" href="{{ route('admin.subadmins.create') }}"><i
                        class="bi bi-plus-circle me-2"></i>
                    Add
                    Sub Admin</a>
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

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @php
        $paginationRoute = '/admin/subadmins?per_page=' . $paginatedDTO->per_page . '&page=';
        $offset = 1;
        $bridge = 3;
        $has_first_offset = false;
        $has_last_offset = false;
    @endphp

    @if (session('restored_warning') && session('restored_email'))
        <div class="alert alert-soft-primary text-primary">
            {{-- {{ session('restored_warning') }} --}}
            The subadmin who owns the email '{{ session('restored_email') }}' has been deleted. Please click on
            <!-- Restore Link -->
            <form action="{{ route('admin.subadmins.restore', ['email' => session('restored_email')]) }}" method="POST"
                style="display:inline;">
                @csrf
                @method('PUT')
                <a class="btn btn-primary btn-sm" href="#"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Restore
                </a>
            </form>
            <!-- End Restore Link -->
            if you want to restore this account
        </div>
    @endif

    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
                <!-- Search -->
                @include('component.searchbar.index', [
                    'route' => 'admin.subadmins',
                ])
                <!-- End Search -->
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Per Page -->
                @include('component.perpage.index', [
                    'route' => 'admin.subadmins',
                    'paginatedDTO' => $paginatedDTO,
                ])
                <!-- End Per Page -->

                <!-- Dropdown -->
                @include('component.filter-column.index')
                <!-- End Dropdown -->
            </div>
        </div>
        <!-- End Header -->
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                <table id="productTable"
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer"
                    role="grid" aria-describedby="datatable_info" style="width: 1271px;">
                    <thead class="thead-light">
                        <tr id="column-in-tabel-filter-container">
                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">No.</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>

                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($paginatedDTO->data as $index => $user)
                            @if ($user->user_status !== $deletedStatus)
                                <tr role="row" class="odd">
                                    <td>
                                        <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                            {{ $index + 1 }}
                                        </button>
                                    </td>

                                    <td>
                                        {{-- Edit Link --}}
                                        <a class="btn btn-white btn-sm"
                                            href="{{ route('admin.subadmins.edit', ['id' => $user->user_id]) }}">
                                            <i class="bi-pencil-fill me-1"></i>
                                        </a>
                                        {{-- End Edit Link --}}

                                        <!-- Active Link -->
                                        <form
                                            action="{{ route('admin.subadmins.active', ['id' => $user->user_id, 'status' => $user->user_status]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <a class="btn btn-white btn-sm" href="#"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i
                                                    class="bi bi-toggle-{{ $user->user_status === $inActiveStatus ? 'off' : 'on text-primary' }} dropdown-item-icon"></i>

                                            </a>
                                        </form>
                                        <!-- End Active Link -->

                                        <!-- Delete Link -->
                                        <form action="{{ route('admin.subadmins.destroy', ['id' => $user->user_id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <a class="btn btn-white btn-sm" href="#"
                                                onclick="event.preventDefault(); confirmDelete(this);">
                                                <i class="bi-trash dropdown-item-icon"></i>
                                            </a>
                                        </form>
                                        <!-- End Delete Link -->
                                    </td>

                                    <td class="c0">
                                        <a class="d-flex align-items-center" href="{{ route('get.file', ['filename' => $user->user_avatar]) }}">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-lg"
                                                    onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}';"
                                                    src="{{ route('get.file', ['filename' => $user->user_avatar]) }}" alt="Image Description">
                                            </div>
                                        </a>
                                    </td>
                                    <td class="c1 search-cell">{{ $user->user_first_name }}</td>
                                    <td class="c2 search-cell">{{ $user->user_last_name }}</td>
                                    <td class="c3">{{ $user->user_birthday }}</td>
                                    <td class="c4 search-cell">{{ $user->user_email }}</td>
                                    <td class="c5 search-cell">{{ $user->user_phone }}</td>
                                    <td class="c6">
                                        @if (count($user->roles) > 0)
                                            <ul class="row bg-soft-primary">
                                                @foreach ($user->roles as $role)
                                                    <li class="col-12">
                                                        <button type="button"
                                                            class="btn btn-outline-primary py-1 px-2 search-cell" disabled>
                                                            {{ $role->role_name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                                NONE
                                            </button>
                                        @endif
                                    </td>
                                    <td class="c7">
                                        @if ($user->user_status === $activeStatus)
                                            <span class="badge badge-success">Active</span>
                                        @elseif ($user->user_status === $inActiveStatus)
                                            <span class="badge badge-primary">Inactive</span>
                                        @elseif ($user->user_status === $deletedStatus)
                                            <button class="btn btn-outline-danger p-1" disabled>Deleted</button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer mt-2">
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-sm mb-2 mb-sm-0">
                </div>
                <!-- End Col -->

                <div class="col-sm-auto">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <!-- Pagination -->
                        @include('component.pagination.index', [
                            'paginatedDTO' => $paginatedDTO,
                            'subject' => 'subadmins'
                        ])
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Footer -->
    </div>

    {{-- comfirm detete --}}
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'subadmins',
    ])
    <script src="{{ asset('js/confirm-delete.js') }}"></script>

    {{-- processing the filter columns --}}
    <script>
        //declare variables for columns 
        const columns = ['Avatar', 'First Name', 'Last Name', 'Birthday', 'Email', 'Phone', 'Roles', 'Status'];
        //key to save into localStorage
        const LOCAL_KEY = "SKIN_PROJECT_SUBADMIN_TABLE_COLUMN_FILTER";
    </script>
    <script src="{{ asset('js/filter-column.js') }}"></script>

    {{-- processing searching --}}
    <script src="{{ asset('js/searchbar.js') }}"></script>
@endsection
