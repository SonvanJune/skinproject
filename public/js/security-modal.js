let captchaText = "";
const passwordInput = document.getElementById("level2Password");
const confirmPasswordInput = document.getElementById("confirmPassword");
const captchaInput = document.getElementById("captchaInput");
const submitButton = document.getElementById("submitCaptcha");
const next2 = document.getElementById("nextStep2");
const prev1 = document.getElementById("backStep1");
const next3 = document.getElementById("nextStep3");
const prev2 = document.getElementById("backStep2");
const questions = document.querySelectorAll(".form-question");
const answers = document.querySelectorAll(".form-answer");
const notiErr = document.getElementById("notiErr");
const notiSuc = document.getElementById("notiSuc");
const form = document.getElementById("securityForm");
const errorMessageSelect = document.querySelector(".error-message-select");
const errorMessageCaptcha = document.querySelector(".error-message");
// Regex kiểm tra mật khẩu
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;

// Auto-show modal on page load
document.addEventListener("DOMContentLoaded", () => {
    const securityModal = new bootstrap.Modal(
        document.getElementById("securityModal")
    );
    securityModal.show();
    generateCaptcha();
});

// Generate CAPTCHA
function generateCaptcha() {
    captchaInput.value = "";
    captchaText = "";
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (let i = 0; i < 6; i++) {
        captchaText += characters.charAt(
            Math.floor(Math.random() * characters.length)
        );
    }
    drawCaptcha();
}

// Draw CAPTCHA on canvas
function drawCaptcha() {
    const canvas = document.getElementById("captchaCanvas");
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#f2f2f2";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.font = "30px Arial";
    ctx.fillStyle = "#333";
    ctx.setTransform(
        1,
        Math.random() * 0.3,
        Math.random() * 0.3,
        1,
        Math.random() * 5,
        Math.random() * 5
    );
    ctx.fillText(captchaText, 20, 50);
}

// Validate CAPTCHA
function validateCaptcha() {
    const captchaInput = document.getElementById("captchaInput").value.trim();
    return captchaInput === captchaText;
}

function nextSlide2() {
    const carousel = document.getElementById("securityCarousel");
    const bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);

    // Kiểm tra xem instance của carousel có được tạo hay chưa, nếu chưa thì tạo lại
    if (!bootstrapCarousel) {
        bootstrapCarousel = new bootstrap.Carousel(carousel);
    }

    if (checkPasswordForm()) {
        bootstrapCarousel.next();
    }
}

// Kiểm tra form Password Level 2
function checkPasswordForm() {
    const isValidPassword =
        passwordInput.value && passwordRegex.test(passwordInput.value);
    const isValidConfirmPassword =
        confirmPasswordInput.value &&
        passwordInput.value === confirmPasswordInput.value;

    passwordInput.classList.toggle("is-invalid", !isValidPassword);
    confirmPasswordInput.classList.toggle(
        "is-invalid",
        !isValidConfirmPassword
    );
    passwordInput.classList.toggle("is-valid", isValidPassword);
    confirmPasswordInput.classList.toggle(
        "is-valid",
        isValidConfirmPassword
    );
    // Kiểm tra nếu các trường được điền đầy đủ
    if (isValidPassword && isValidConfirmPassword) {
        passwordInput.classList.remove("is-invalid");
        passwordInput.classList.add("is-valid");
        confirmPasswordInput.classList.remove("is-invalid");
        confirmPasswordInput.classList.add("is-valid");
        next2.disabled = false;
        const carousel = document.getElementById("securityCarousel");
        let bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);

        // Kiểm tra xem instance của carousel có được tạo hay chưa, nếu chưa thì tạo lại
        if (!bootstrapCarousel) {
            bootstrapCarousel = new bootstrap.Carousel(carousel);
        }
        return true;
    } else {
        next2.disabled = true;
        confirmPasswordInput.classList.remove("is-valid");
        confirmPasswordInput.classList.add("is-invalid");
    }
}

function nextSlide3() {
    const carousel = document.getElementById("securityCarousel");
    const bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);

    // Kiểm tra xem instance của carousel có được tạo hay chưa, nếu chưa thì tạo lại
    if (!bootstrapCarousel) {
        bootstrapCarousel = new bootstrap.Carousel(carousel);
    }
    bootstrapCarousel.next();
}

function prevSlide1() {
    const carousel = document.getElementById("securityCarousel");
    const bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);

    // Kiểm tra xem instance của carousel có được tạo hay chưa, nếu chưa thì tạo lại
    if (!bootstrapCarousel) {
        bootstrapCarousel = new bootstrap.Carousel(carousel);
    }
    bootstrapCarousel.prev();
}

// Hàm kiểm tra nhập trường select
function onInputSelectForm(selectElement) {
    errorMessageSelect.style.display = "none";
    if (checkSelectForm()) {
        next3.disabled = false;
    } else {
        next3.disabled = true;
    }
    if (selectElement.value !== "") {
        selectElement.classList.remove("is-invalid");
        selectElement.classList.add("is-valid");
    } else {
        selectElement.classList.remove("is-valid");
        selectElement.classList.add("is-invalid");
    }
}

function checkSelectForm() {
    let isValid = true;
    let selectedQuestions = [];

    questions.forEach((question, index) => {
        if (question.value == "") {
            isValid = false;
        } else {
            if (selectedQuestions.includes(question.value)) {
                isValid = false;
                errorMessageSelect.style.display = "block";
            } else {
                selectedQuestions.push(question.value);
            }
        }
    });

    answers.forEach((answer, index) => {
        if (answer.value == "") {
            isValid = false;
        } else {
        }
    });

    return isValid;
}

function prevSlide2() {
    const carousel = document.getElementById("securityCarousel");
    const bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);

    // Kiểm tra xem instance của carousel có được tạo hay chưa, nếu chưa thì tạo lại
    if (!bootstrapCarousel) {
        bootstrapCarousel = new bootstrap.Carousel(carousel);
    }
    bootstrapCarousel.prev();
}

// Verify CAPTCHA input
function submitForm(event) {
    event.preventDefault();
    const errorMessage = document.querySelector(".message.error-message");
    if (validateCaptcha()) {
        notiSuc.classList.remove("d-none");
        const securityModal = bootstrap.Modal.getInstance(
            document.getElementById("securityModal")
        );
        securityModal.hide();
        document.querySelector(".page-overlay").style.display = "none";
        form.submit();
    } else {
        errorMessage.style.display = "block";
        generateCaptcha();
    }
}

// Kiểm tra form CAPTCHA
function checkCaptchaForm() {
    errorMessageCaptcha.style.display = "none";
    submitButton.disabled = !captchaInput.value;
}

// Lắng nghe sự kiện nhập liệu để kiểm tra các form
document
    .getElementById("level2Password")
    .addEventListener("input", checkPasswordForm);
document
    .getElementById("confirmPassword")
    .addEventListener("input", checkPasswordForm);
document
    .getElementById("captchaInput")
    .addEventListener("input", checkCaptchaForm);

// Bắt sự kiện 'change' (hoặc 'input' nếu bạn muốn bắt khi thay đổi nội dung)
questions.forEach((select) => {
    select.addEventListener("change", (event) => {
        onInputSelectForm(event.target);
    });
});

answers.forEach((select) => {
    select.addEventListener("change", (event) => {
        onInputSelectForm(event.target);
    });
});
