@push('css')
    <style>
        .custom-modal-login-register {
            display: none;
            position: fixed;
            z-index: 10005;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .custom-modal-login-register .custom-modal-content {
            background: var(--bg-card, #12100f);
            color: var(--text-heading, #fff6ed);
            margin: 12% auto;
            padding: 32px 25px;
            border-radius: 16px;
            width: 90%;
            max-width: 420px;
            border: 1px solid var(--border-bronze, rgba(223, 183, 124, 0.35));
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 25px var(--bronze-glow, rgba(197, 155, 99, 0.15));
            text-align: center;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            position: relative;
        }

        .custom-modal-login-register .custom-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid transparent;
            background: transparent;
            color: var(--text-muted, #9ca3af);
            font-size: 15px;
            line-height: 1;
            cursor: pointer;
            padding: 0;
            margin: 0;
            transform-origin: center center;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
            z-index: 10;
        }

        .custom-modal-login-register .custom-close:hover {
            color: var(--bronze-light, #dfb77c);
            background: rgba(223, 183, 124, 0.12);
            border-color: var(--border-bronze, rgba(223, 183, 124, 0.35));
            transform: rotate(90deg);
        }

        .custom-modal-login-register h2 {
            font-family: 'Italiana', serif;
            font-size: 1.5rem;
            color: var(--text-heading, #fff6ed);
            letter-spacing: 1px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .custom-modal-login-register .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }

        .custom-modal-login-register .modal-actions a {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .custom-modal-login-register .btn-login {
            background: var(--bronze-gradient, linear-gradient(135deg, #dfb77c, #c59b63));
            color: #070709 !important;
            box-shadow: 0 4px 15px rgba(223, 183, 124, 0.3);
        }

        .custom-modal-login-register .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(223, 183, 124, 0.5);
        }

        .custom-modal-login-register .btn-register {
            background: transparent;
            color: var(--bronze-light, #dfb77c) !important;
            border: 1px solid var(--border-bronze, rgba(223, 183, 124, 0.4));
        }

        .custom-modal-login-register .btn-register:hover {
            background: rgba(223, 183, 124, 0.12);
            border-color: var(--bronze-light, #dfb77c);
            transform: translateY(-2px);
        }

        /* Light Mode */
        :root[data-theme="light"] .custom-modal-login-register .custom-modal-content {
            background: rgba(255, 255, 255, 0.98) !important;
            border-color: rgba(168, 121, 59, 0.35) !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15), 0 0 25px rgba(168, 121, 59, 0.1) !important;
            color: var(--text-heading, #1f1f23) !important;
        }

        :root[data-theme="light"] .custom-modal-login-register h2 {
            color: var(--text-heading, #1f1f23) !important;
        }

        :root[data-theme="light"] .custom-modal-login-register .custom-close {
            color: #6b7280 !important;
        }

        :root[data-theme="light"] .custom-modal-login-register .custom-close:hover {
            color: var(--bronze-primary, #a8793b) !important;
            background: rgba(168, 121, 59, 0.12) !important;
            border-color: rgba(168, 121, 59, 0.35) !important;
        }
    </style>
@endpush
<div id="loginRegisterModal" class="custom-modal-login-register">
    <div class="custom-modal-content">
        <button type="button" class="custom-close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        <h2>{{ __('message.needToLogin') }}</h2>

        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-3" style="max-height: 80px;">

        <div class="modal-actions">
            <a href="{{ route('login') }}" class="btn-login">{{ __('message.login') }}</a>
            <a href="{{ route('register') }}" class="btn-register">{{ __('message.register') }}</a>
        </div>
    </div>
</div>
