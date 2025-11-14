@extends('component.intro.layout')

@section('title', 'Register Sub-Admin')
@section('title-intro', 'Sign up to SkinShop')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('form')
    <div class="intro-form">
        <form action="{{ route('admin.subadmins.store') }}" method="POST" id="myForm" class="needs-validation" novalidate>
            @csrf
            <div class="input-group">
                <div class="col-md-6 fn">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required />
                    <div class="invalid-feedback">
                        Please enter first name
                    </div>
                </div>
                <div class="col-md-6 ln">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required />
                    <div class="invalid-feedback">
                        Please enter last name
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required />
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            <div class="input-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
                <div class="invalid-feedback">
                    Please enter password.
                </div>
            </div>
            <div class="input-group">
                <div class="col-md-6 fn">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" required />
                    <div class="invalid-feedback">
                        Please enter birthday.
                    </div>
                </div>
                <div class="col-md-6 ln">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone_number" required />
                    <div class="invalid-feedback">
                        Please enter phone.
                    </div>
                </div>
            </div>
            <div class="under-form">
                <div class="reset">
                    Already have account?
                    <a href="{{ url(App::getLocale() . '/login') }}">Click here</a>
                </div>
            </div>
            <button class="btn btnLog" type="submit">Sign Up</button>
            <div class="social-media">
                <div class="or">Or Sign Up With</div>
                <a class="bu google"><i class="fa-brands fa-google"></i></a>
                <a class="bu whatapp"><i class="fa-brands fa-whatsapp"></i></a>
                <a class="bu facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a class="bu likedin"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
        </form>
    </div>

    @if (session('register_failed'))
        <div class="alert alert-warning alert-dismissible fade show zoom-in" role="alert">
            {{ session('register_failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
