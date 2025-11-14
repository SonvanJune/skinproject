@extends('component.intro.layout')

@section('title', 'Admin Login')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/login-admin.css') }}">
@endpush

@section('admin')
    <div class="login-admin container">
        <div class="row justify-content-center login-container">
            <div class="col-md-8">
                <div class="login-box">
                    <!-- Logo -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                    <h4 class="text-center mb-4 admin-text">Admin Login</h4>

                    <!-- Form đăng nhập cho Admin -->
                    <form action="{{ route('admin.login.submit') }}" method="POST" id="myForm" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-custom">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
