document.addEventListener("DOMContentLoaded", function () {
    const email = document.getElementById("email");
    const emailRegex = /^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*\.[a-zA-Z]{2,}$/;

    function validateForm() {
        const isEmailValid = emailRegex.test(email.value);

        if (isEmailValid) {
            canSubmit.value = "true";
        } else {
            canSubmit.value = "false";
        }

        email.classList.toggle("is-invalid", !isEmailValid);
        email.classList.toggle("is-valid", isEmailValid);
        checkCaptchaForm();
    }

    email.addEventListener("input", validateForm);
});