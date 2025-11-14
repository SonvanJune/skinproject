const intro = document.querySelector('.intro-page');
const right = document.querySelector('.right-panel');
const oldValue = intro.offsetHeight;

function validateFormRegister(event){
    const fname = document.getElementById('firstName');
    const lname = document.getElementById('lastName');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const repassword = document.getElementById('repassword');
    const birthday = document.getElementById('birthday');
    const phone = document.getElementById('phone');

    let validFName = checkInput(fname);
    let validLName = checkInput(lname);
    let validEmail = validateEmail(email);
    let validatePassword = validatePass(password);
    let validRepassword = validateRePass(password,repassword);
    let validBirthday = checkInput(birthday);
    let validPhone = checkInput(phone);
    let valid = validFName && validLName && validEmail && validatePassword && validRepassword && validBirthday && validPhone;

    if (valid) {
        intro.style.height = oldValue + "px";
        right.style.height = oldValue + "px";
        document.getElementById("notiSuc").classList.remove("d-none");
        document.getElementById("myForm").submit();
    } else {
        intro.style.height = (oldValue + 100) + "px";
        right.style.height = (oldValue + 100) + "px";
        event.preventDefault();
    }
}

function checkInput(input) {
    let valid = true;
    if (input.value === "") {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        valid = false;
    } else {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        valid = true;
    }
    return valid;
}

function validateEmail(input) {
    const regex = /^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*\.[a-zA-Z]{2,}$/;
    if (regex.test(input.value.trim())) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        return true;
    } else {
        input.classList.add("is-invalid");
        return false;
    }
}

function validatePass(input) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;
    if (regex.test(input.value.trim())) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        return true;
    } else {
        input.classList.add("is-invalid");
        return false;
    }
}

function validateRePass(pass , repass) {
    if (pass.value.trim() == repass.value.trim()) {
        repass.classList.remove("is-invalid");
        repass.classList.add("is-valid");
        return true;
    } else {
        repass.classList.add("is-invalid");
        return false;
    }
}


