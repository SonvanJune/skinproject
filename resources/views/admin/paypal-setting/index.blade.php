@extends('layouts.admin')

@section('title', 'Admin PayPal Setting')

@section('content')
    <style>
        .paypal-settings {
            background: #fdfdfd;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
        }

        .paypal-settings .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .paypal-settings .form-control {
            border-radius: 8px;
            font-size: 15px;
            font-family: monospace;
            resize: none;
            overflow: hidden;
            min-height: 42px;
        }

        .paypal-settings .btn {
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 6px;
        }

        .paypal-settings .card-header {
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #0070ba, #003087);
        }

        .paypal-settings .card-header h4 {
            font-weight: 600;
        }

        .copy-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #555;
            font-size: 18px;
            cursor: pointer;
        }

        .input-copy-wrapper {
            position: relative;
        }

        .alert {
            font-size: 14px;
        }

        .refresh-btn {
            font-size: 16px;
            border-radius: 6px;
            background: none;
            color: white;
            border: none;
            cursor: pointer;
            position: absolute;
            top: 9%;
            right: 10%;
        }

        .paypal-settings .btn[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    @isset($paypalData)
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Button to Refresh Page -->


                    <div class="card paypal-settings">
                        <div class="card-header text-white text-center">
                            <h4 class="mb-0">⚙️ PayPal Settings</h4>
                            <button type="button" class="refresh-btn" onclick="refreshPage()">
                                🔄
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.paypal.save') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="client_id" class="form-label">🔑 Client ID</label>
                                    <div class="input-copy-wrapper">
                                        <textarea rows="1" class="form-control" name="client_id" id="client_id" oninput="autoResize(this)"
                                            placeholder="Enter your PayPal Client ID">{{ $paypalData['client_id'] }}</textarea>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('client_id')"
                                            title="Copy">
                                            📋
                                        </button>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="client_secret" class="form-label">🕵️‍♂️ Client Secret</label>
                                    <div class="input-copy-wrapper">
                                        <textarea rows="1" class="form-control" name="client_secret" id="client_secret" oninput="autoResize(this)"
                                            placeholder="Enter your PayPal Secret">{{ $paypalData['client_secret'] }}</textarea>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('client_secret')"
                                            title="Copy">
                                            📋
                                        </button>
                                        @error('client_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="app_id" class="form-label">🔑 App ID</label>
                                    <div class="input-copy-wrapper">
                                        <textarea rows="1" class="form-control" name="app_id" id="app_id" oninput="autoResize(this)"
                                            placeholder="Enter your PayPal App ID">{{ $paypalData['app_id'] }}</textarea>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('app_id')"
                                            title="Copy">
                                            📋
                                        </button>
                                        @error('app_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success shadow-sm">
                                        💾 Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endisset


    <script>
        function autoResize(el) {
            el.style.height = 'auto';
            el.style.height = (el.scrollHeight) + 'px';
        }

        function copyToClipboard(id) {
            const el = document.getElementById(id);
            el.select();
            el.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(el.value).then(() => {
                el.classList.add('border-success');
                setTimeout(() => el.classList.remove('border-success'), 1500);
            });
        }

        // Function to refresh the page
        function refreshPage() {
            location.reload(); // Reload the page to reflect changes
        }

        window.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            const saveBtn = form.querySelector('button[type="submit"]');
            const inputs = form.querySelectorAll('textarea');

            const initialValues = {};
            inputs.forEach(input => {
                autoResize(input);
                initialValues[input.name] = input.value.trim();
            });

            function checkChanges() {
                let changed = false;
                inputs.forEach(input => {
                    if (input.value.trim() !== initialValues[input.name]) {
                        changed = true;
                    }
                });
                saveBtn.disabled = !changed;
            }

            inputs.forEach(input => input.addEventListener('input', checkChanges));

            saveBtn.disabled = true;
        });
    </script>
@endsection
