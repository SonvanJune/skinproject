@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Subadmin')

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
                        <li class="breadcrumb-item active" aria-current="page">Add Subadmin</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Subadmin</h1>
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

    <form id="create-sub-admin-form" action="{{ route('admin.subadmins.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Subadmin information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <div class="input-group pb-2">
                        <div class="col-md-6 fn pe-1">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" oninput="onType()" class="form-control" id="firstName" name="first_name"
                                required />
                            <div class="invalid-feedback">
                                Please enter first name
                            </div>
                        </div>
                        <div class="col-md-6 ln ps-1">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" oninput="onType()" class="form-control" id="lastName" name="last_name"
                                required />
                            <div class="invalid-feedback">
                                Please enter last name
                            </div>
                        </div>
                    </div>

                    <div class="input-group pb-2">
                        <div class="col-md-6 fn pe-1">
                            <label for="email" class="form-label pe-1">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required />
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>

                        <div class="col-md-6 ln ps-1">
                            <label for="password" class="form-label pe-1">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required />
                            <div class="invalid-feedback">
                                Please enter password.
                            </div>
                        </div>
                    </div>

                    <div class="input-group pb-2">
                        <div class="col-md-6 fn pe-1">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" required />
                            <div class="invalid-feedback">
                                Please enter birthday.
                            </div>
                        </div>
                        <div class="col-md-6 ln ps-1">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone_number" required />
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
                                <input class="form-check-input create-sub-admin-role" type="checkbox"
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
            <button type="submit" onclick="event.preventDefault(); confirmSave(this);"
                class="btn btn-primary ms-2 btn-sm">Save changes</button>
        </div>
    </form>

    @include('component.modal.ConfirmModal', [
        'subject' => 'subadmins',
        'action' => 'save new subadmin',
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
@endsection
