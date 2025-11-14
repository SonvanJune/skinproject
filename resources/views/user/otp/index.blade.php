@extends('component.intro.layout')

@section('title', $type)
@section('title-intro', $type)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('form')
    <div class="intro-form">
        @include('component.otp.otp-form', [
            'count' => $countOtp,
            'functionSubmit' => route($transaction),
            'email' => $email,
            'only_otp' => $only_otp,
        ])
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/form-validate.js') }}"></script>
@endpush
