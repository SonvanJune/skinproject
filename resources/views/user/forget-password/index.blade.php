@extends('component.intro.layout')

@section('title', __('message.forgetPasswordTitle'))
@section('title-intro', __('message.forgetPasswordTitle'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('form')
    <div class="intro-form">
        <form action="{{ route('forgetPassword.submit') }}" method="POST" id="forgetPasswordForm" class="needs-validation"
            novalidate>
            @csrf
            <div class="input-group">
                <label for="email" class="form-label">{{ __('message.email') }}</label>
                <input type="email" class="form-control" id="email" name="user_email" required />
                <div class="invalid-feedback">
                    {{ __('message.validEmail') }}
                </div>
            </div>
            <input type="text" class="form-control" id="canSubmit" hidden value="false">
            @include('component.captcha.index', [
                'formId' => 'forgetPasswordForm',
                'buttonId' => 'submitButton',
                'inputHide' => 'canSubmit',
            ])
            <div class="under-form">
                <div class="reset">
                    {{ __('message.backToLogin') }}
                    <a href="{{ url(App::getLocale() . '/login') }}">{{ __('message.clickHere') }}</a>
                </div>
            </div>
            <button class="btn btnLog" type="submit" id="submitButton" disabled>{{ __('message.submitButton') }}</button>
        </form>
    </div>
    @include('component.noticationNoData.loading.index', [])
@endsection

@push('js')
    <script src="{{ asset('js/form-forget-validate.js') }}"></script>
@endpush
