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
        <div class="otp-wrapper" id="otpWrapper">
            <input type="text"
                   name="one_time_password_code"
                   id="otpInput"
                   class="otp-master-input"
                   maxlength="{{ $count }}"
                   inputmode="numeric"
                   pattern="[0-9]*"
                   autocomplete="one-time-code"
                   autofocus>
            <div class="otp-boxes-container" id="otpBoxesContainer">
                @for ($i = 0; $i < $count; $i++)
                    <div class="otp-box {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}">
                        <span class="otp-digit"></span>
                    </div>
                @endfor
            </div>
        </div>
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
        const otpInput = document.getElementById('otpInput');
        const otpBoxes = document.querySelectorAll('.otp-box');

        function updateOtpDisplay(val) {
            errorMessage.classList.add('d-none');
            const digits = val.split('');

            otpBoxes.forEach((box, idx) => {
                const digitSpan = box.querySelector('.otp-digit');
                if (idx < digits.length) {
                    digitSpan.textContent = digits[idx];
                    box.classList.add('has-value');
                } else {
                    digitSpan.textContent = '';
                    box.classList.remove('has-value');
                }

                // Focus/active indicator
                if (idx === Math.min(digits.length, otpLength - 1) && document.activeElement === otpInput) {
                    box.classList.add('is-active');
                } else {
                    box.classList.remove('is-active');
                }
            });
        }

        if (otpInput) {
            // Support normal typing
            otpInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, otpLength);
                updateOtpDisplay(this.value);

                if (this.value.length === otpLength) {
                    submitOtpForm();
                }
            });

            // Support smooth Copy & Paste
            otpInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text') || '';
                const cleanDigits = pasteData.replace(/\D/g, '').slice(0, otpLength);
                
                this.value = cleanDigits;
                updateOtpDisplay(cleanDigits);

                if (cleanDigits.length === otpLength) {
                    submitOtpForm();
                }
            });

            // Focus and blur visual effects
            otpInput.addEventListener('focus', function() {
                updateOtpDisplay(this.value);
            });

            otpInput.addEventListener('blur', function() {
                otpBoxes.forEach(b => b.classList.remove('is-active'));
            });

            // Auto focus on load
            setTimeout(() => {
                otpInput.focus();
                updateOtpDisplay(otpInput.value);
            }, 100);
        }

        function submitOtpForm() {
            let validPassword = true;
            if (only_otp == false) {
                validPassword = validatePasswords();
            }

            if (validPassword) {
                const otp = otpInput ? otpInput.value.trim() : '';
                
                if (otp.length !== otpLength) {
                    if (typeof event !== 'undefined' && event) event.preventDefault();
                    errorMessage.classList.remove('d-none');
                    return;
                }
                document.getElementById('notiSuc').classList.remove('d-none');
                document.getElementById('otpForm').submit();
            } else {
                if (typeof event !== 'undefined' && event) event.preventDefault();
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
