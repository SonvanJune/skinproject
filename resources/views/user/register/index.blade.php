@extends('component.intro.layout')

@section('title', __('message.register'))
@section('title-intro', __('message.signUpTo') . ' ' . __('message.webName'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('form')
    <div class="intro-form">
        <form action="{{ route('register.submit') }}" method="POST" id="myForm" class="needs-validation" novalidate>
            @csrf
            <div class="input-group d-flex flex-nowrap gap-3">
                <div class="flex-fill">
                    <label for="firstName" class="form-label">{{ __('message.firstNameTitle') }}</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required />
                    <div class="invalid-feedback">
                        {{ __('message.invalidFirstName') }}
                    </div>
                </div>
                <div class="flex-fill">
                    <label for="lastName" class="form-label">{{ __('message.lastNameTitle') }}</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required />
                    <div class="invalid-feedback">
                        {{ __('message.invalidLastName') }}
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label for="email" class="form-label">{{ __('message.email') }}</label>
                <input type="email" class="form-control" id="email" name="email" required />
                <div class="invalid-feedback">
                    {{ __('message.validEmail') }}
                </div>
            </div>
            <div class="input-group">
                <label for="password" class="form-label">{{ __('message.password') }}</label>
                <input type="password" class="form-control" id="password" name="password" required />
                <div class="invalid-feedback">
                    {{ __('message.invalidPasswordMess') }}
                </div>
            </div>
            <div class="input-group">
                <label for="repassword" class="form-label">{{ __('message.confirmPassword') }}</label>
                <input type="password" class="form-control" id="repassword" required />
                <div class="invalid-feedback">
                    {{ __('message.validateNewPasswordAndConfirmPassword') }}
                </div>
            </div>
            <div class="input-group d-flex flex-nowrap gap-3">
                <div class="flex-fill">
                    <label for="birthday" class="form-label">{{ __('message.birthday') }}</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" required />
                    <div class="invalid-feedback">
                        {{ __('message.invalidBirthday') }}
                    </div>
                </div>
                <div class="flex-fill">
                    <label for="phone" class="form-label">{{ __('message.phone') }}</label>
                    <input type="tel" class="form-control" id="phone" name="phone_number" required />
                    <div class="invalid-feedback">
                        {{ __('message.invalidPhone') }}
                    </div>
                </div>
            </div>
            <div class="under-form">
                <div class="reset">
                    {{ __('message.alreadyHaveAccount') }}
                    <a href="{{ url(App::getLocale() . '/login') }}">{{ __('message.clickHere') }}</a>
                </div>
            </div>
            <button class="btn btnLog" onclick="validateFormRegister(event)">{{ __('message.signUpButton') }}</button>
        </form>
    </div>
    @include('component.noticationNoData.loading.index', [])
@endsection

@push('js')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
