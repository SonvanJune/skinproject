document.addEventListener("DOMContentLoaded", function () {
    const newPasswordInput = document.getElementById("inputNewPassword");
    let canSubmit = document.getElementById("canSubmit");
    const passwordForm = document.getElementById("changePasswordLevel2Form");
    const answers = document.querySelectorAll(".answer-input");

    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
    let isNewPasswordValid = false;
    let isSelectFormValid = false;

    function validateForm() {
        const newPasswordValid = passwordRegex.test(newPasswordInput.value);

        if (newPasswordValid) {
            isNewPasswordValid = true;
        } else {
            isNewPasswordValid = false; 
        }

        newPasswordInput.classList.toggle("is-invalid", !newPasswordValid);
        newPasswordInput.classList.toggle("is-valid", newPasswordValid);
        check()
    }

    function validateSelectForm(selectElement){
        if (checkSelectForm()) {
            isSelectFormValid = true; 
        } else {
            isSelectFormValid = false; 
        }

        if (selectElement.classList) {
            selectElement.classList.toggle("is-invalid", selectElement.value == "");
            selectElement.classList.toggle("is-valid", !(selectElement.value == ""));
        }
        check()
    }

    function check(){
        if (isNewPasswordValid == true && isSelectFormValid == true) {
            canSubmit.value = "true";  
        } else {
            canSubmit.value = "false"; 
        }      
        checkCaptchaForm();
    }

    newPasswordInput.addEventListener("input", validateForm);
    answers.forEach((select) => {
        select.addEventListener("input", (event) => {
            validateSelectForm(event.target);
        });
    });

    passwordForm.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!passwordRegex.test(newPasswordInput.value)) {
            newPasswordInput.classList.add("is-invalid");
            return;
        }
    });

    function checkSelectForm() {
        let isValid = true;

        answers.forEach((answer) => {
            if (answer.value == "") {
                isValid = false;
            }
            else{
                isValid = true;
            }
        });

        return isValid;
    }
});
