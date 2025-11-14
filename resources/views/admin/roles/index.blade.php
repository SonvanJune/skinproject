@extends('layouts.admin')

@section('title', 'Admin-Roles')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Roles <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span></h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.roles.create') }}"><i class="bi bi-plus-circle me-2"></i> Add
                    Role</a>
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
        $paginationRoute = '/admin/roles?';
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
                @include('component.searchbar.index', [
                    'route' => 'admin.roles',
                ])
                <!-- End Search -->
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Per Page -->
                @include('component.perpage.index', [
                    'route' => 'admin.roles',
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

                    <tbody>
                        @foreach ($paginatedDTO->data as $index => $role)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    @if ($role->editable)
                                        <!-- Edit Link -->
                                        <a class="btn btn-white btn-sm"
                                            href="{{ route('admin.roles.edit', ['id' => $role->role_id]) }}">
                                            <i class="bi-pencil-fill me-1"></i>
                                        </a>
                                        <!-- End Edit Link -->

                                        <!-- Button Group -->
                                        @if (count($role->users) === 0)
                                            <!-- Delete Link -->
                                            <form action="{{ route('admin.roles.destroy', ['id' => $role->role_id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a class="btn btn-white btn-sm" href="#"
                                                    onclick="event.preventDefault(); confirmDelete(this);">
                                                    <i class="bi-trash dropdown-item-icon"></i>
                                                </a>
                                            </form>
                                            <!-- End Delete Link -->
                                        @endif
                                        <!-- End Button Group -->
                                    @else
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            NO ACTIONS
                                        </button>
                                    @endif
                                </td>

                                <td class="c0 search-cell">{{ $role->role_name }}</td>
                                <td class="c1">
                                    @if ($role->editable)
                                        @if (count($role->permissions) > 0)
                                            <ul class="row bg-soft-primary">
                                                @foreach ($role->permissions as $permission)
                                                    <li class="col-12">
                                                        <button type="button" class="btn btn-outline-primary py-1 px-2"
                                                            disabled>
                                                            {{ $permission->permission_name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                                NONE
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            SYSTEM
                                        </button>
                                    @endif
                                </td>
                                <td class="c2">
                                    <button type="button" class="btn btn-outline-primary py-1 px-2 text-center" disabled>
                                        {{ count($role->users) }}
                                    </button>
                                </td>
                                <td class="c3">{{ $role->created_at }}</td>
                                <td class="c4">{{ $role->updated_at }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table -->

    </div>

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
                            'subject' => 'roles'
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
        'subject' => 'tracking codes',
    ])
    <script src="{{ asset('js/confirm-delete.js') }}"></script>

    {{-- processing the filter columns --}}
    <script>
        //declare variables for columns 
        const columns = ['Role Name', 'Permissions', 'Owners', 'Created Date', 'Updated Date'];
        //key to save into localStorage
        const LOCAL_KEY = "SKIN_PROJECT_ROLE_TABLE_COLUMN_FILTER";
    </script>
    <script src="{{ asset('js/filter-column.js') }}"></script>

    {{-- processing searching --}}
    <script src="{{ asset('js/searchbar.js') }}"></script>

@endsection
