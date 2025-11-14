@extends('layout')

@section('title', __('message.contactUs'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/contact-us.css') }}">
@endpush

@section('content')
    <div class="contact-us">
        <div class="contact-section py-5">
            <div class="container">
                <h2 class="text-center mb-4">{{ __('message.contactUs') }}</h2>
                <p class="text-center mb-5">
                    {{ __('message.contactDetail') }}
                </p>
                <div class="contact-container row g-0">
                    <div class="col-lg-4 contact-info-bg">
                        <h3>{{ __('message.contactBusiness') }}</h3>
                        <p>
                            {{ __('message.contactQuestion') }}
                        </p>
                        <hr />
                        <div class="sales-info">
                            <strong>{{ __('message.webName') . ' ' . __('message.hotline') }} </strong>
                            <p><i class="fas fa-phone"></i> +84 {{ __('message.webPhone') }}</p>
                        </div>
                        <div class="sales-info">
                            <p>{{ __('message.contactNote') }}</p>
                        </div>
                        <hr />
                        <div class="social-icons">
                            <a href="https://www.facebook.com/binhcaochinh"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                        <div id="map" style="height: 200px"></div>
                        <div class="circle-1"></div>
                        <div class="circle-2"></div>
                    </div>
                    <div class="col-lg-8 contact-form-bg">
                        <form class="p-4" id="contactForm" action="{{ route('contact.send') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="firstName" class="form-label">{{ __('message.name') }}</label>
                                <input type="text" class="form-control" id="firstName" name="name" />
                                <div class="invalid-feedback">
                                    {{ __('message.invalidName') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('message.email') }}</label>
                                <input type="email" class="form-control" id="email" placeholder="you@yoursite.com"
                                    name="email" />
                                <div class="invalid-feedback">
                                    {{ __('message.validEmail') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('message.phone') }}</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="" />
                                <div class="invalid-feedback">
                                    {{ __('message.invalidPhone') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">{{ __('message.country') }}</label>
                                <input type="text" class="form-control" id="country" name="country" />
                                <div class="invalid-feedback">
                                    {{ __('message.invalidCountry') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">{{ __('message.subject') }}</label>
                                <input type="text" class="form-control" id="subject" name="subject" />
                                <div class="invalid-feedback">
                                    {{ __('message.invalidSubject') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">{{ __('message.message') }}</label>
                                <textarea class="form-control form-textarea" id="message" rows="4" name="content"></textarea>
                                <div class="invalid-feedback">
                                    {{ __('message.invalidMessage') }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ __('message.submitButton') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('component.noticationNoData.loading.index', [])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'error',
        'type' => 'error',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'success',
        'type' => 'success',
    ])
@endsection

@push('js')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/contact.js') }}"></script>
@endpush
