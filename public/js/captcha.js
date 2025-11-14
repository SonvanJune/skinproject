document.addEventListener("DOMContentLoaded", () => {
    generateCaptcha();
});

const formId = document.getElementById("formId").value.trim();
const buttonId = document.getElementById("buttonId").value.trim();
const inputHide = document.getElementById("inputHide").value.trim();
const form = document.getElementById(formId);
const submitButton = document.getElementById(buttonId);
const canSubmitInput = document.getElementById(inputHide);
const errorMessageCaptcha = document.querySelector(".error-message");
const captchaInput = document.getElementById("captchaIn");
const notiSuc = document.getElementById("notiSuc");

captchaInput.addEventListener("input", checkCaptchaForm);

// Generate CAPTCHA
function generateCaptcha() {
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

function validateCaptcha() {
    return captchaInput.value.trim() === captchaText;
}

function checkCaptchaForm() {
    errorMessageCaptcha.style.display = "none";
    if (captchaInput.value.trim() != "" && canSubmitInput.value === "true") {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

submitButton.addEventListener("click", function (event) {
    event.preventDefault();
    if (validateCaptcha()) {
        notiSuc.classList.remove("d-none");
        form.submit();
    } else {
        errorMessageCaptcha.style.display = "block";
        generateCaptcha();
    }
});
