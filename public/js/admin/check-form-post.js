function checkInput(input) {
    let valid = true;
    if (input.value === "" || input.value == null) {
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

function checkInputDate(input) {
    let valid = true;
    const currentDate = new Date().toISOString().split("T")[0];

    if (input.value === "") {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        valid = false;
    } else if (new Date(input.value) < new Date(currentDate)) {
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

function handleSubmit(event) {
    event.preventDefault();
    valid = false;

    const nameInput = document.getElementById("postNameLabel");
    const slugInput = document.getElementById("postSlugLabel");
    let content = quill.root.innerHTML;
    if (content != "<p><br></p>") {
        document.querySelector("#postContent").value = content;
    } else {
        document.querySelector("#postContent").value = "";
    }
    const descriptionInput = document.getElementById("postContent");
    const imagePathInput = document.getElementById("imagePathInput");
    const imageAltInput = document.getElementById("imageAltInput");
    const statusInput = document.getElementById("availabilitySwitch1");
    const releaseInput = document.getElementById("releaseLabel");
    const action = document.getElementById("action");
    
    let validName = checkInput(nameInput);
    let validSlug = checkInput(slugInput);
    let validDes = checkInput(descriptionInput);
    let validImagePath = checkInput(imagePathInput);
    let validIamgeAlt = checkInput(imageAltInput);
    
    if(statusInput.value == 1 && action.value == "create")
    {
        let validReleaseDate = checkInputDate(releaseInput);
        valid = validName && validSlug && validDes && validImagePath && validIamgeAlt && validReleaseDate;
    }else{
        valid = validName && validSlug && validDes && validImagePath && validIamgeAlt;
    }

    if (valid) {
        confirmCreate(document.getElementById("postForm"));
    } else {
        event.preventDefault();
    }
}

function confirmCreate(form) {
    const modal = new bootstrap.Modal(
        document.getElementById("confirmationModal")
    );
    modal.show();

    document.getElementById("confirmBtn").onclick = function () {
        form.submit();
        modal.hide();
    };
}
