@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Role')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.roles') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Role</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Role</h1>
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

    <form id="create-role-form" action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Role information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <label for="roleName" class="form-label">Role Name</label>
                    <input oninput="createNewRole(this.value)" type="text" class="form-control" id="roleName"
                        name="role_name" required>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="roleNameError"></div>
            </div>
        </div>

        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Permission List</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3 row">
                    @foreach ($permissions as $permission)
                        <div class="form-check col-4">
                            <input class="form-check-input" type="checkbox" value="{{ $permission->permission_id }}"
                                name="permissions[]">
                            <label class="form-check-label">
                                {{ $permission->permission_name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-3 text-end">
            <button type="submit" onclick="event.preventDefault(); confirmSave(this);" class="btn btn-primary btn-sm">Save
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
        function confirmSave(element) {
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
@endsection
