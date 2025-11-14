@extends('layout')

@section('title', __('message.changePassword'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/my-account.css') }}">
@endpush

@section('content')
    <div class="my-account">
        @if (!empty($user_name))
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ __('message.changePassword') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user->user_last_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="{{ url(App::getLocale() . '/account/change-password') }}">{{ __('message.changePassword') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container content my-5">
                <div class="row">
                    <div class="col-md-3">
                        @include('component.sidebar.sidebar-user-page', [
                            'user' => $user,
                        ])
                    </div>
                    <div class="col-md-9">
                        <div class="info">
                            <h4>{{ __('message.changePassword') }}</h4>
                            <div class="info-item">
                                <div class="info-title">
                                    <i class="fa-solid fa-key"></i>
                                    <span>{{ __('message.changePassword') }}</span>
                                </div>
                                <form id="changePasswordForm" method="POST" action="{{ route('changePassword.submit') }}">
                                    @csrf
                                    <div class="info-content">
                                        <div class="mb-2 row justify-content-center align-items-center">
                                            <label for="inputPassword"
                                                class="col-sm-2 col-form-label">{{ __('message.oldPassword') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputPassword"
                                                    name="current_password" required placeholder="{{ __('message.oldPassword') }}">
                                                <div class="invalid-feedback">{{ __('message.invalidOldPassword') }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 row justify-content-center align-items-center">
                                            <label for="inputNewPassword"
                                                class="col-sm-2 col-form-label">{{ __('message.newPassword') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputNewPassword"
                                                    name="new_password" required
                                                    placeholder="{{ __('message.newPassword') }}">
                                                <div class="invalid-feedback">{{ __('message.validateNewPassword') }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 row justify-content-center align-items-center">
                                            <label for="inputReNewPassword"
                                                class="col-sm-2 col-form-label">{{ __('message.confirmPassword') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputReNewPassword"
                                                    required placeholder="{{ __('message.confirmPassword') }}">
                                                <div class="invalid-feedback">{{ __('message.validConfirmPassword') }}
                                                </div>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="canSubmit" hidden value="false">
                                        @include('component.captcha.index', [
                                            'formId' => 'changePasswordForm',
                                            'buttonId' => 'submitButton',
                                            'inputHide' => 'canSubmit',
                                        ])
                                        <div class="mb-1 row">
                                            <div class="col-sm-12 text-end">
                                                <button type="submit" class="btn btn-primary w-10" id="submitButton"
                                                    disabled>{{ __('message.submitButton') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('component.noticationNoData.user.index', [])
        @endif
        @include('component.noticationNoData.loading.index', [])
        @include('component.noticationNoData.notification.index', ['notification' => 'change_password_success' , 'type' => 'success'])
        @include('component.noticationNoData.notification.index', ['notification' => 'error' , 'type' => 'error'])
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/change-password-form.js') }}"></script>
@endpush
