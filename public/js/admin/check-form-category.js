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

function checkInputDate(input) {
    let valid = true;
    const currentDate = new Date().toISOString().split('T')[0];

    if (input.value === "") {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        valid = false;
    } else if (new Date(input.value) < new Date(currentDate)) {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        valid = false;
    } else {
        input.classList.add('is-valid')
        input.classList.remove("is-invalid");
        valid = true;
    }

    return valid;
}

function handleSubmit(event) {
    event.preventDefault()
    event.stopPropagation()
    const nameInput = document.getElementById('categoryNameLabel');
    const slugInput = document.getElementById('categorySlugLabel');
    let content = quill.root.innerHTML;
    if (content != '<p><br></p>') {
        document.querySelector('#categoryDescriptionContent').value = content;        
    }
    else{
        document.querySelector('#categoryDescriptionContent').value = '';
    }
    const descriptionInput = document.getElementById('categoryDescriptionContent');
    const statusInput = document.getElementById('availabilitySwitch1');
    const typeInput = document.getElementById('categoryTypeInput');
    const imagePathInput = document.getElementById('imagePathInput');
    const imageAltInput = document.getElementById('imageAltInput');

    if(document.getElementById('updateAt') != null){
        document.getElementById('updateAt').value = resetRelease();
    }

    valid = true;
    let validName = checkInput(nameInput);
    let validSlug = checkInput(slugInput);
    let validDes = checkInput(descriptionInput);
    let validStatus = checkInput(statusInput);
    
    if (typeInput.value == 2) {
        let validImagePath = checkInput(imagePathInput);
        let validIamgeAlt = checkInput(imageAltInput);

        valid = validName && validSlug && validDes && validStatus && validIamgeAlt && validImagePath
    } else {
        valid = validName && validSlug && validDes && validStatus
    }

    if (valid) {
        document.getElementById("productForm").submit();
    } else {
        event.preventDefault();
    }
}