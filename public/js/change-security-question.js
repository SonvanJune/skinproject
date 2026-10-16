document.addEventListener("DOMContentLoaded", function () {
    const password = document.getElementById("inputPassword");
    const canSubmit = document.getElementById("canSubmit");
    const passwordForm = document.getElementById("changeSecurityQuestionForm");
    const questions = document.querySelectorAll(".form-question");
    const answers = document.querySelectorAll(".form-answer");
    const errorMessageSelect = document.querySelector(".error-message-select");
    let isPasswordValid = false;
    let isAnswerValid = false;
    let isQuestionValid = false;

    function validateForm() {
        const passwordValid = password.value != "";

        if (passwordValid) {
            isPasswordValid = true;
        } else {
            isPasswordValid = false;
        }

        password.classList.toggle("is-invalid", !passwordValid);
        password.classList.toggle("is-valid", passwordValid);
        check();
    }

    function validateAnswerForm(selectElement) {
        if (checkAnswerSelectForm()) {
            isAnswerValid = true;
        } else {
            isAnswerValid = false;
        }

        if (selectElement.classList) {
            selectElement.classList.toggle(
                "is-invalid",
                selectElement.value == ""
            );
            selectElement.classList.toggle(
                "is-valid",
                !(selectElement.value == "")
            );
        }
        check();
    }

    function updateAvailableQuestions() {
        const selectedValues = Array.from(questions)
            .map(q => q.value)
            .filter(val => val !== "" && val !== null);

        questions.forEach(select => {
            const currentVal = select.value;
            Array.from(select.options).forEach(option => {
                if (!option.value) return;

                if (selectedValues.includes(option.value) && option.value !== currentVal) {
                    option.hidden = true;
                    option.disabled = true;
                    option.style.display = "none";
                } else {
                    option.hidden = false;
                    option.disabled = false;
                    option.style.display = "";
                }
            });
        });
    }

    function validateQuestionForm(selectElement) {
        errorMessageSelect.style.display = "none";
        updateAvailableQuestions();
        if (checkQuestionSelectForm()) {
            isQuestionValid = true;
        } else {
            isQuestionValid = false;
        }

        if (selectElement.classList) {
            selectElement.classList.toggle(
                "is-invalid",
                selectElement.value == ""
            );
            selectElement.classList.toggle(
                "is-valid",
                !(selectElement.value == "")
            );
        }
        check();
    }

    function check() {
        if (
            isAnswerValid == true &&
            isQuestionValid == true &&
            isPasswordValid == true
        ) {
            canSubmit.value = "true";
        } else {
            canSubmit.value = "false";
        }
        checkCaptchaForm();
    }

    password.addEventListener("input", validateForm);
    answers.forEach((select) => {
        select.addEventListener("input", (event) => {
            validateAnswerForm(event.target);
        });
    });
    questions.forEach((select) => {
        select.addEventListener("input", (event) => {
            validateQuestionForm(event.target);
        });
        select.addEventListener("change", (event) => {
            validateQuestionForm(event.target);
        });
    });
    updateAvailableQuestions();

    passwordForm.addEventListener("submit", function (event) {
        event.preventDefault();

        if (password.value == "") {
            newPasswordInput.classList.add("is-invalid");
            return;
        }
    });

    function checkAnswerSelectForm() {
        let isValid = true;

        answers.forEach((answer, index) => {
            if (answer.value == "") {
                isValid = false;
            } else {
                isValid = true;
            }
        });

        return isValid;
    }

    function checkQuestionSelectForm() {
        let isValid = true;
        let selectedQuestions = [];
        questions.forEach((question, index) => {
            if (question.value == "") {
                isValid = false;
            } else {
                if (selectedQuestions.includes(question.value)) {
                    errorMessageSelect.style.display = "block";
                    isValid = false;
                } else {
                    selectedQuestions.push(question.value);
                }
            }
        });
        return isValid;
    }
});
