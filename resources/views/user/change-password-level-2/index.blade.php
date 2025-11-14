@extends('layout')

@section('title', __('message.changePasswordLevel2'))

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
                            <h4>{{ __('message.changePasswordLevel2') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user->user_last_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="{{ url(App::getLocale() . '/account/change-password-level-2') }}">{{ __('message.changePasswordLevel2') }}</a>
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
                            <h4>{{ __('message.changePasswordLevel2') }}</h4>
                            <div class="info-item">
                                <div class="info-title">
                                    <i class="fa-solid fa-key"></i>
                                    <span>{{ __('message.changePasswordLevel2') }}</span>
                                </div>
                                <p class="text-danger">{{ __('message.requiredChangePasswordLevel2') }}
                                </p>
                                <form id="changePasswordLevel2Form" method="POST"
                                    action="{{ route('changePasswordlv2.submit') }}">
                                    @csrf
                                    <div class="info-content">
                                        @isset($securityQuestions)
                                            @foreach ($securityQuestions as $i => $question)
                                                <div class="mb-2 row justify-content-center align-items-center">
                                                    <label for="inputPassword"
                                                        class="col-sm-2 col-form-label">{{ $question->question_text }}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" value="{{ $question->question_id }}" hidden
                                                            name="securityQuestion{{ $i + 1 }}">
                                                        <input type="text" class="form-control answer-input"
                                                            id="inputFakePassword" name="securityAnswer{{ $i + 1 }}"
                                                            placeholder="{{ __('message.securityQuestionAnswer') }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endisset
                                        <div class="mb-2 row justify-content-center align-items-center">
                                            <label for="inputPassword"
                                                class="col-sm-2 col-form-label">{{ __('message.newPassword') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="new_password_level_2"
                                                    id="inputNewPassword" placeholder="{{ __('message.newPassword') }}">
                                                <div class="invalid-feedback">{{ __('message.validateNewPassword') }}</div>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="canSubmit" hidden value="false">
                                        @include('component.captcha.index', [
                                            'formId' => 'changePasswordLevel2Form',
                                            'buttonId' => 'submitButton',
                                            'inputHide' => 'canSubmit',
                                        ])
                                        <div class="mb-3 row">
                                            <div class="col-sm-12 text-end">
                                                <button id="submitButton" type="submit" class="btn btn-primary w-10"
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
    <script src="{{ asset('js/change-password-lv2-form.js') }}"></script>
@endpush
