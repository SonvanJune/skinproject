@push('css')
    <link rel="stylesheet" href="{{ asset('css/security-modal.css') }}">
@endpush

<div class="security-modal">
    <div class="page-overlay"></div>

    <!-- Modal -->
    <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="securityModalLabel">{{ __('message.securitySetup') }}</h5>
                </div>
                <div class="modal-body">
                    <form id="securityForm" action="{{ route('security.setup') }}" method="POST">
                        @csrf
                        <!-- Carousel -->
                        <div id="securityCarousel" class="carousel slide" data-bs-interval="false">
                            <!-- Carousel Items -->
                            <div class="carousel-inner">
                                <!-- Step 1: Password Level 2 -->
                                <div class="carousel-item active">
                                    <h5 class="mb-3">{{ __('message.securityStep1') }}</h5>
                                    <p class="note">{{ __('message.downloadNote') }}</p>
                                    <div class="mb-3">
                                        <label for="level2Password"
                                            class="form-label">{{ __('message.enterPassword') }}</label>
                                        <input type="password" id="level2Password" class="form-control"
                                            name="password_level_2" placeholder="{{ __('message.enterPassword') }}"
                                            required>
                                        <div class="invalid-feedback">{{ __('message.validPasswordMess') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword"
                                            class="form-label">{{ __('message.enterConfirmPassword') }}</label>
                                        <input type="password" id="confirmPassword" class="form-control"
                                            placeholder="{{ __('message.enterConfirmPassword') }}" required>
                                        <div class="invalid-feedback">{{ __('message.validConfirmPassword') }}</div>
                                    </div>
                                    <button class="btn btn-success w-100" id="nextStep2" onclick="nextSlide2()"
                                        disabled>{{ __('message.next') }}</button>
                                </div>

                                <!-- Step 2: Security Question -->
                                <div class="carousel-item">
                                    <h5 class="mb-3">{{ __('message.securityStep2') }}</h5>
                                    @isset($securityQuestions)
                                        @for ($i = 0; $i < $countQuestion; $i++)
                                            <div class="mb-3">
                                                <label for="{{ 'securityQuestion' . $i + 1 }}"
                                                    class="form-label">{{ __('message.selectSecurityQuestion') }}</label>
                                                <select id="{{ 'securityQuestion' . $i + 1 }}"
                                                    class="form-select form-question w-100 select-container" required
                                                    name="{{ 'securityQuestion' . $i + 1 }}">
                                                    <option value="" disabled selected>
                                                        {{ __('message.selectSecurityQuestion') }}</option>
                                                    @foreach ($securityQuestions as $question)
                                                        <option value="{{ $question->question_id }}">
                                                            {{ $question->question_text }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="{{ 'securityAnswer' . $i + 1 }}"
                                                    class="form-label">{{ __('message.securityQuestionAnswer') }}</label>
                                                <input type="text" id="{{ 'securityAnswer' . $i + 1 }}"
                                                    class="form-control form-answer" placeholder="Enter your answer"
                                                    required name="{{ 'securityAnswer' . $i + 1 }}">
                                            </div>
                                        @endfor
                                    @endisset

                                    <div class="message error-message-select text-center" style="display:none;">
                                        {{ __('message.haveSameQuestionNoti') }}
                                    </div>
                                    <div class="btn-contain d-flex gap-1">
                                        <button class="btn btn-warning w-100" id="backStep1"
                                            onclick="prevSlide1()">{{ __('message.previous') }}</button>
                                        <button class="btn btn-success w-100" id="nextStep3" onclick="nextSlide3()"
                                            disabled>{{ __('message.next') }}</button>
                                    </div>
                                </div>

                                <!-- Step 3: CAPTCHA Verification -->
                                <div class="carousel-item">
                                    <h5 class="mb-3">{{ __('message.securityStep3') }}</h5>
                                    <div class="captcha-container text-center mb-3">
                                        <div class="canvas-contain d-flex align-items-center justify-content-center">
                                            <canvas id="captchaCanvas" width="200" height="70"></canvas><br>
                                            <button type="button" id="refreshCaptcha" class="btn btn-light btn-refresh"
                                                onclick="generateCaptcha()">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                        <input type="text" id="captchaInput" class="form-control mb-3"
                                            placeholder="Enter the CAPTCHA" required>
                                        <div class="btn-contain d-flex gap-1">
                                            <button class="btn btn-warning w-100" id="backStep2"
                                                onclick="prevSlide2()">{{ __('message.previous') }}</button>
                                            <button class="btn btn-success w-100" id="submitCaptcha"
                                                onclick="submitForm(event)"
                                                disabled>{{ __('message.verifyCaptcha') }}</button>
                                        </div>
                                        <div class="message error-message" style="display:none;">
                                            {{ __('message.incorrectCaptcha') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('component.noticationNoData.loading.index', [])

<div id="notiErr" class="d-none">
    <div class="alert alert-danger alert-dismissible fade show zoom-in" role="alert">
        {{ __('message.invalidSecuritySetup') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

@push('js')
    <script src="{{ asset('js/security-modal.js') }}"></script>
@endpush
