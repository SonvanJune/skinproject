@push('css')
    <link rel="stylesheet" href="{{ asset('css/otp-form.css') }}">
@endpush

<p class="text-center">{{ __('message.sendEmailMess') }} {{ $email }}.</p>
<form action="{{ $functionSubmit }}" method="POST" id="otpForm">
    @csrf
    @if ($only_otp == 'false')
        <div class="input-group">
            <label for="password" class="form-label">{{ __('message.newPassword') }}</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <div class="invalid-feedback">
                {{ __('message.invalidPasswordMess') }}
            </div>
        </div>
        <div class="input-group">
            <label for="repassword" class="form-label">{{ __('message.confirmPassword') }}</label>
            <input type="password" id="repassword" class="form-control" required>
            <div class="invalid-feedback">
                {{ __('message.validateNewPasswordAndConfirmPassword') }}
            </div>
        </div>
    @endif
    <div class="input-group">
        <label for="otpInput" class="form-label">{{ __('message.otp') }}</label>
        <div class="otp-container" id="otpContainer"></div>
        <input type="hidden" name="one_time_password_code" id="otpInput">
        <input type="hidden" name="user_email" value="{{ $email }}">
        <p id="alert" class="text-center mt-2 error d-none">{{ __('message.invalidOtp') }}</p>
    </div>
    @if ($functionSubmit == route('activeRegister'))
        <div class="text-center mt-3">
            <a href="{{ route('resend.otp.active', ['email' => $email]) }}" class="btn btn-link"
                onclick="resendEmail()">
                {{ __('message.resendOtp') }}
            </a>
        </div>
    @else
        <div class="text-center mt-3">
            <a href="{{ route('resend.otp.forget.pass', ['email' => $email]) }}" class="btn btn-link"
                onclick="resendEmail()">
                {{ __('message.resendOtp') }}
            </a>
        </div>
    @endif
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary"
            onclick="submitOtpForm()">{{ __('message.submitButton') }}</button>
    </div>
</form>
@include('component.noticationNoData.loading.index', [])

@push('js')
    <script>
        const otpLength = {{ $count }};
        const only_otp = {{ $only_otp }};
        const errorMessage = document.getElementById('alert');
        const errorMessage1 = document.getElementById('alert-pass');
        const inputPassword = document.getElementById('password');

        function createOtpFields(count) {
            const otpContainer = document.getElementById('otpContainer');
            otpContainer.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const input = document.createElement('input');
                input.type = 'password';
                input.maxLength = 1;
                input.classList.add('otp-input');
                input.inputMode = 'numeric'
                input.pattern = '[0-9]*';

                input.onkeypress = function(e) {
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                    }
                };

                input.oninput = function() {
                    errorMessage.classList.add('d-none');
                    if (input.value.length === 1 && i < count - 1) {
                        otpContainer.children[i + 1].focus();
                    }

                    if (i === count - 1 && input.value.length === 1) {
                        submitOtpForm();
                    }
                };

                input.onkeydown = function(e) {
                    errorMessage.classList.add('d-none');
                    if (e.key === 'Backspace' && input.value === '' && i > 0) {
                        otpContainer.children[i - 1].focus();
                    }
                };
                otpContainer.appendChild(input);
            }
        }

        createOtpFields(otpLength);

        function submitOtpForm() {
            let validPassword = true;
            if (only_otp == false) {
                validPassword = validatePasswords();
            }

            if (validPassword) {
                const otpInputs = document.querySelectorAll('.otp-input');
                const otp = Array.from(otpInputs).map(input => input.value).join('');
                
                if (otp.length !== otpLength) {
                    event.preventDefault();
                    errorMessage.classList.remove('d-none');
                    return;
                }
                document.getElementById('notiSuc').classList.remove('d-none');
                document.getElementById('otpInput').value = otp;
                document.getElementById('otpForm').submit();
            } else {
                event.preventDefault();
                return;
            }
        }

        function validatePasswords() {
            const passwordInput = document.getElementById('password');
            const repasswordInput = document.getElementById('repassword');
            const password = passwordInput.value.trim();
            const repassword = repasswordInput.value.trim();

            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;

            let isValid = true;

            passwordInput.classList.remove('is-invalid');
            repasswordInput.classList.remove('is-invalid');

            if (!passwordRegex.test(password)) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            }

            if (repassword == "" || password !== repassword) {
                repasswordInput.classList.add('is-invalid');
                isValid = false;
            }
            passwordInput.classList.add('is-valid');
            repasswordInput.classList.add('is-valid');

            return isValid;
        }

        function resendEmail() {
            document.getElementById('notiSuc').classList.remove('d-none');
        }
    </script>
@endpush
