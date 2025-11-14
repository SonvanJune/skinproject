@extends('layout')

@section('title', __('message.changeSecurityQuestion'))

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
                            <h4>{{ __('message.changeSecurityQuestion') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user->user_last_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="{{ url(App::getLocale() . '/account/change-password-level-2') }}">{{ __('message.changeSecurityQuestion') }}</a>
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
                            <h4>{{ __('message.changeSecurityQuestion') }}</h4>
                            <div class="info-item">
                                <div class="info-title">
                                    <i class="fa-solid fa-key"></i>
                                    <span>{{ __('message.changeSecurityQuestion') }}</span>
                                </div>
                                <p class="text-danger">{{ __('message.requiredChangeSecurityQuestion') }}
                                </p>
                                <form id="changeSecurityQuestionForm" method="POST"
                                    action="{{ route('changeSecurityQuestion.submit') }}">
                                    @csrf
                                    <div class="info-content">
                                        <div class="mb-2 row justify-content-center align-items-center">
                                            <label for="inputPassword"
                                                class="col-sm-2 col-form-label">{{ __('message.passwordLevel2Title') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="user_password_level_2"
                                                    id="inputPassword" placeholder="{{ __('message.enterPassword') }}">
                                                <div class="invalid-feedback">{{ __('message.requiredPasswordLevel2') }}
                                                </div>
                                            </div>
                                        </div>
                                        @isset($securityQuestions)
                                            @for ($i = 0; $i < $countQuestion; $i++)
                                                <div class="mb-2 row justify-content-center align-items-center">
                                                    <label for="{{ 'securityQuestion' . $i + 1 }}"
                                                        class="col-sm-2 col-form-label">{{ __('message.selectSecurityQuestion') }}</label>
                                                    <div class="col-sm-10">
                                                        <select id="{{ 'securityQuestion' . $i + 1 }}"
                                                            class="form-select form-question w-100 select-container" required
                                                            name="{{ 'securityQuestion' . $i + 1 }}">
                                                            <option value="" disabled selected>
                                                                {{ __('message.selectSecurityQuestion') }}</option>
                                                            @foreach ($securityQuestions as $question)
                                                                <option value="{{ $question->question_id }}">
                                                                    {{ $question->question_text }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-2 row justify-content-center align-items-center">
                                                    <label for="{{ 'securityAnswer' . $i + 1 }}"
                                                        class="col-sm-2 col-form-label">{{ __('message.securityQuestionAnswer') }}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="{{ 'securityAnswer' . $i + 1 }}"
                                                            class="form-control form-answer"
                                                            placeholder="{{ __('message.enterSecurityQuestionAnswer') }}"
                                                            required name="{{ 'securityAnswer' . $i + 1 }}">
                                                    </div>
                                                </div>
                                            @endfor
                                        @endisset
                                        <div class="message error-message-select text-center" style="display:none;">
                                            {{ __('message.haveSameQuestionNoti') }}</div>
                                        <input type="text" class="form-control" id="canSubmit" hidden value="false">
                                        @include('component.captcha.index', [
                                            'formId' => 'changeSecurityQuestionForm',
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
        @include('component.noticationNoData.notification.index', ['notification' => 'change_success' , 'type' => 'success'])
        @include('component.noticationNoData.notification.index', ['notification' => 'error' , 'type' => 'error'])
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/change-security-question.js') }}"></script>
@endpush
