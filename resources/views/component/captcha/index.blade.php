@push('css')
    <link rel="stylesheet" href="{{ asset('css/captcha.css') }}">
@endpush
<div class="captcha-component">
    <div class="captcha-container">
        <div class="canvas-contain mb-3 d-flex align-items-center justify-content-center">
            <canvas id="captchaCanvas" width="200" height="70"></canvas><br>
            <button type="button" id="refreshCaptcha" class="btn btn-light btn-refresh" onclick="generateCaptcha()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        <div class="mb-3 row">
            <label for="captchaInput" class="col-sm-2 col-form-label">{{ __('message.verifyCaptcha') }}</label>
            <div class="col-sm-10">
                <input type="text" id="captchaIn" class="form-control mb-3" placeholder="{{ __('message.enterCaptcha') }}" required>
                <input type="text" id="formId" value="{{ $formId }}" hidden>
                <input type="text" id="buttonId" value="{{ $buttonId }}" hidden>
                <input type="text" id="inputHide" value="{{ $inputHide }}" hidden>
                <div class="invalid-feedback">{{ __('message.validateNewPasswordAndConfirmPassword') }}</div>
            </div>
            <div class="message error-message" style="display:none;">{{ __('message.incorrectCaptcha') }}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('js/captcha.js') }}"></script>
@endpush
