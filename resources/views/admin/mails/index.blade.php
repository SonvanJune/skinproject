@extends('layouts.admin')

@section('title', 'Admin-Mails')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Mails <span class="badge bg-soft-dark text-dark ms-2">{{ count($mails) }}
                        items</span></h1>
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

    <div class="card">
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
                                No.</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Mail Name</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Mail File Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($mails as $index => $mail)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    <!-- Edit Link -->
                                    <a class="btn btn-white btn-sm"
                                        href="{{ route('admin.mails.edit', ['mail_id' => $mail->mail_id]) }}">
                                        <i class="bi-pencil-fill me-1"></i>
                                    </a>
                                    <!-- End Edit Link -->
                                </td>

                                <td>{{ $mail->mail_name }}</td>
                                <td class="link-primary">{{ $mail->mail_file_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table -->

    </div>
@endsection
