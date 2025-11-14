@extends('layout')

@section('title', __('message.account'))

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
                            <h4>{{ __('message.myAccount') }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="/"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="">{{ $user->user_last_name }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="{{ url(App::getLocale() . '/account') }}">{{ __('message.account') }}</a>
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
                            <h4>{{ __('message.personalInfor') }}</h4>
                            <div class="info-item">
                                <div class="info-title">
                                    <i class="fa-solid fa-user"></i>
                                    <span>{{ __('message.account') }}</span>
                                </div>
                                <div class="info-content">
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                            class="col-sm-2 col-form-label">{{ __('message.email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" readonly class="form-control" id="staticEmail"
                                                value="{{ $user->user_email }}">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="inputPassword"
                                            class="col-sm-2 col-form-label">{{ __('message.password') }}</label>
                                        <div class="col-sm-10">
                                            <input type="password" readonly class="form-control" id="inputFakePassword">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="inputPassword"
                                            class="col-sm-2 col-form-label">{{ __('message.phone') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" readonly class="form-control" id="phone"
                                                value="{{ $user->user_phone }}">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="inputPassword"
                                            class="col-sm-2 col-form-label">{{ __('message.birthday') }}</label>
                                        <div class="col-sm-10">
                                            <input type="date" readonly class="form-control" id="date"
                                                value="{{ \Carbon\Carbon::parse($user->user_birthday)->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="info-item">
                                <div class="info-title">
                                    <i class="fa-solid fa-gear"></i>
                                    <span>Khác</span>
                                </div>
                                <div class="info-content">
                                    sdads
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('component.noticationNoData.user.index', [])
        @endif
    </div>
@endsection

@push('js')
    <script>
        const passwordLength = 8;
        const inputPassword = document.getElementById('inputFakePassword');

        inputPassword.placeholder = '*'.repeat(passwordLength);
    </script>
@endpush
