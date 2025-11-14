@extends('layouts.admin')

@section('title', 'Admin-Questions')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Security Questions <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span></h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.questions.create') }}"><i class="bi bi-plus-circle me-2"></i>
                    Add
                    Question</a>
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
        $paginationRoute = '/admin/questions?';
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
                    'route' => 'admin.questions',
                ])
                <!-- End Search -->
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Per Page -->
                @include('component.perpage.index', [
                    'route' => 'admin.questions',
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
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="list">
                        @foreach ($paginatedDTO->data as $index => $question)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    @if ($question->quantity_of_users === 0)
                                        <a class="btn btn-white btn-sm"
                                            href="{{ route('admin.questions.edit', ['id' => $question->question_id]) }}">
                                            <i class="bi-pencil-fill me-1"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.questions.destroy', ['id' => $question->question_id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <a class="btn btn-white btn-sm" href="#"
                                                onclick="event.preventDefault(); confirmDelete(this);">
                                                <i class="bi-trash dropdown-item-icon"></i>
                                            </a>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            NO ACTIONS
                                        </button>
                                    @endif
                                </td>

                                <td class="c0 text-start" colspan="1" style="min-width: 400px">
                                    <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;"
                                        class="text-start search-cell bg-soft-primary p-2">{{ $question->question_text }}
                                    </p>
                                </td>
                                <td class="c1">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-outline-primary py-1 px-2" disabled>
                                            {{ $question->quantity_of_users }}
                                        </button>
                                    </div>
                                </td>
                                <td class="c2">{{ $question->created_at }}</td>
                                <td class="c3">{{ $question->updated_at }}</td>
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
                            'subject' => 'questions'
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
        const columns = ['Question', 'Quantity Of Users Answering', 'Created Date', 'Updated Date'];
        //key to save into localStorage
        const LOCAL_KEY = "SKIN_PROJECT_SECURITY_QUESTION_TABLE_COLUMN_FILTER";
    </script>
    <script src="{{ asset('js/filter-column.js') }}"></script>

    {{-- processing searching --}}
    <script src="{{ asset('js/searchbar.js') }}"></script>
@endsection
