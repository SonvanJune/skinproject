@extends('component.intro.layout')

@section('title', __('message.login'))
@section('title-intro', __('message.signInTo') . ' ' . __('message.webName'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('form')
    <div class="intro-form">
        <form action="{{ route('login.submit') }}" method="POST" id="myForm" class="needs-validation" novalidate>
            @csrf
            <div class="input-group">
                <label for="email" class="form-label">{{ __('message.email') }}</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <div class="invalid-feedback">
                    {{ __('message.validEmail') }}
                </div>
            </div>
            <div class="input-group">
                <label for="password" class="form-label">{{ __('message.password') }}</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="invalid-feedback">
                    {{ __('message.requiredEnterPassword') }}
                </div>
            </div>
            <div class="under-form">
                <div class="reset">
                    {{ __('message.forgetPassword') }}
                    <a href="{{ url(App::getLocale() . '/forget-password') }}">{{ __('message.clickHere') }}</a>
                </div>
                <div class="register">
                    {{ __('message.newUser') }}
                    <a href="{{ url(App::getLocale() . '/register') }}">{{ __('message.registerHere') }}</a>
                </div>
            </div>
            <button class="btn btnLog" type="submit">{{ __('message.login') }}</button>
        </form>
        {{-- <div class="social-media">
            <div class="or">Or Login With</div>
            <a class="bu google"><i class="fa-brands fa-google"></i></a>
            <a class="bu whatapp"><i class="fa-brands fa-whatsapp"></i></a>
            <a class="bu facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a class="bu likedin"><i class="fa-brands fa-linkedin-in"></i></a>
        </div> --}}
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
