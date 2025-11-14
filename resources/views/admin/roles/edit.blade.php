@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Role')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.roles') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Role
                            {{ $duplicated ? '(Duplicated)' : '' }}</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Role {{ $duplicated ? '(Duplicated)' : '' }}</h1>

                <div class="mt-2">
                    @if (!$duplicated)
                        <a class="text-body me-2"
                            href="{{ route('admin.roles.edit', ['id' => $role->role_id, 'duplicated' => true]) }}">
                            <i class="bi-clipboard me-1"></i> Duplicate
                        </a>
                    @endif
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

    <form action="{{ route('admin.roles.update', ['duplicated' => $duplicated]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Role information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <input id="edit-role-id" name="role_id" hidden readonly value="{{ $role->role_id }}">

                    <label for="edit-role-name" class="form-label">Role Name</label>
                    <input oninput="updateRole(this.value)" type="text" class="form-control" id="edit-role-name"
                        value="{{ $role->role_name }}" name="role_name" required>
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
                            <input data-id={{ 'permission-id-' . $permission->permission_id }}
                                class="form-check-input pers-edit-role" type="checkbox"
                                {{ $role->permissions->pluck('permission_id')->contains($permission->permission_id) ? 'checked' : '' }}
                                value="{{ $permission->permission_id }}" name="permissions[]">
                            <label class="form-check-label">
                                {{ $permission->permission_name }}
                            </label>
                        </div>
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
        'action' => 'save after editing role',
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
@endsection
