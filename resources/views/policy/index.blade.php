@extends('layout')

@section('title', __('message.policyTitle'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/policy.css') }}">
@endpush

@section('content')
    <div class="policy-page">
        <div class="container my-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">

                    <h1 class="text-center fw-bold mb-2">
                        {{ __('message.policyHeading') }}
                    </h1>

                    <p class="text-center text-muted mb-4">
                        {{ __('message.policyLastUpdated') }}: 14/12/2025
                    </p>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyIntroTitle') }}</h4>
                        <p>{{ __('message.policyIntroContent') }}</p>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyProductTitle') }}</h4>
                        <p>{{ __('message.policyProductContent') }}</p>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyPaymentTitle') }}</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ __('message.policyPaymentItem1') }}</li>
                            <li class="list-group-item">{{ __('message.policyPaymentItem2') }}</li>
                            <li class="list-group-item">{{ __('message.policyPaymentItem3') }}</li>
                        </ul>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyRefundTitle') }}</h4>
                        <p>{{ __('message.policyRefundContent') }}</p>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyLicenseTitle') }}</h4>
                        <ul>
                            <li>{{ __('message.policyLicenseItem1') }}</li>
                            <li>{{ __('message.policyLicenseItem2') }}</li>
                            <li>{{ __('message.policyLicenseItem3') }}</li>
                        </ul>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyLiabilityTitle') }}</h4>
                        <p>{{ __('message.policyLiabilityContent') }}</p>
                    </section>

                    <section class="mb-4">
                        <h4 class="fw-semibold">{{ __('message.policyContactTitle') }}</h4>
                        <p>{{ __('message.policyContactContent') }}</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
