@push('css')
    <style>
        .custom-modal-login-register {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .custom-modal-login-register .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .custom-modal-login-register .custom-close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .custom-modal-login-register .modal-actions a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 8px 0;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .custom-modal-login-register .btn-login {
            background-color: #007bff;
        }

        .custom-modal-login-register .btn-register {
            background-color: #28a745;
        }

        .custom-modal-login-register .modal-actions a:hover {
            opacity: 0.9;
        }
    </style>
@endpush
<div id="loginRegisterModal" class="custom-modal-login-register">
    <div class="custom-modal-content">
        <span class="custom-close">&times;</span>
        <h2>{{ __('message.needToLogin') }}</h2>

        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-3" style="max-height: 80px;">

        <div class="modal-actions">
            <a href="{{ route('login') }}" class="btn-login">{{ __('message.login') }}</a>
            <a href="{{ route('register') }}" class="btn-register">{{ __('message.register') }}</a>
        </div>
    </div>
</div>

