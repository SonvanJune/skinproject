document.addEventListener('DOMContentLoaded', function () {
    const oldPasswordInput = document.getElementById('inputPassword');
    const newPasswordInput = document.getElementById('inputNewPassword');
    const reNewPasswordInput = document.getElementById('inputReNewPassword');
    let canSubmit = document.getElementById('canSubmit');
    const passwordForm = document.getElementById('changePasswordForm');
    let isOldPasswordValid = false;
    let isNewPasswordValid = false;
    let isReNewPasswordValid = false;

    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;

    function validateOldPassword() {
        const oldPasswordValid = oldPasswordInput.value.trim() !== '';
        oldPasswordInput.classList.toggle('is-invalid', !oldPasswordValid);
        oldPasswordInput.classList.toggle('is-valid', oldPasswordValid);
        if (oldPasswordValid) {
            isOldPasswordValid = true;
        } else {
            isOldPasswordValid = false;
        }
        check()
    }

    function validateNewPassword() {
        const newPasswordValid = passwordRegex.test(newPasswordInput.value);
        newPasswordInput.classList.toggle('is-invalid', !newPasswordValid);
        newPasswordInput.classList.toggle('is-valid', newPasswordValid);
        if (newPasswordValid) {
            isNewPasswordValid = true
        } else {
            isNewPasswordValid = false
        }
        check()
    }

    function check(){
        if (isNewPasswordValid && isOldPasswordValid && isReNewPasswordValid) {
            canSubmit.value = "true";
        } else {
            canSubmit.value = "false";
        }      
        checkCaptchaForm();
    }

    function validateRenewPassword() {
        const reNewPasswordValid = newPasswordInput.value === reNewPasswordInput.value;
        reNewPasswordInput.classList.toggle('is-invalid', !reNewPasswordValid);
        reNewPasswordInput.classList.toggle('is-valid', reNewPasswordValid);
        if (reNewPasswordValid) {
            isReNewPasswordValid = true;
        } else {
            isReNewPasswordValid = false;
        }
        check()
    }

    oldPasswordInput.addEventListener('input', validateOldPassword);
    newPasswordInput.addEventListener('input', validateNewPassword);
    reNewPasswordInput.addEventListener('input', validateRenewPassword);

    passwordForm.addEventListener('submit', function(event) {
        event.preventDefault();

        if (oldPasswordInput.value.trim() === '') {
            oldPasswordInput.classList.add('is-invalid');
            return;
        }

        if (!passwordRegex.test(newPasswordInput.value)) {
            newPasswordInput.classList.add('is-invalid');
            return;
        }

        if (newPasswordInput.value !== reNewPasswordInput.value) {
            reNewPasswordInput.classList.add('is-invalid');
            return;
        }
    });
});
