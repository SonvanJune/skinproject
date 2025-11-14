const productImages = [];

document.querySelectorAll('input[id^="imageAltLabel_"]').forEach((input) => {
    input.addEventListener("input", function () {
        const index = this.id.split("_")[1];
        const altText = this.value;

        productImages[index].product_image_alt = altText;
    });
});

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

    selectedFiles.forEach((file, index) => {
        const altText = document.getElementById(`imageAltLabel_${index}`).value;
        productImages.push({
            product_image_path: file.filePath,
            product_image_alt: altText,
        });
    });

    const productImagesHidden = document.getElementById("productImagesHidden");
    productImages.forEach((image, index) => {
        const inputName = document.createElement("input");
        inputName.type = "hidden";
        inputName.name = `productImages[${index}][product_image_path]`;

        inputName.value = image.product_image_path;
        productImagesHidden.appendChild(inputName);

        const inputAlt = document.createElement("input");
        inputAlt.type = "hidden";
        inputAlt.name = `productImages[${index}][product_image_alt]`;
        inputAlt.value = image.product_image_alt;
        productImagesHidden.appendChild(inputAlt);
    });

    const nameInput = document.getElementById("productNameLabel");
    const slugInput = document.getElementById("productSlugLabel");
    let content = quill.root.innerHTML;
    if (content != "<p><br></p>") {
        document.querySelector("#postContent").value = content;
    } else {
        document.querySelector("#postContent").value = "";
    }
    const descriptionInput = document.getElementById("postContent");
    const imagePathInput = document.getElementById("imagePathInput");
    const imageAltInput = document.getElementById("imageAltInput");
    const fileInput = document.getElementById("productFilePathLabel");
    const categories = document.querySelectorAll(".multi-select-selected");
    const hasCategories = document.getElementById("hasCategories");
    if (categories.length > 0) {
        hasCategories.value = "1";
    } else {
        hasCategories.value = null;
    }
    const priceInput = document.getElementById("priceNameLabel");
    const statusInput = document.getElementById("availabilitySwitch1");
    const releaseInput = document.getElementById("releaseLabel");
    const action = document.getElementById("action");

    valid = false;
    let validName = checkInput(nameInput);
    let validSlug = checkInput(slugInput);
    let validDes = checkInput(descriptionInput);
    let validImagePath = checkInput(imagePathInput);
    let validIamgeAlt = checkInput(imageAltInput);
    let validFile = checkInput(fileInput);
    let validCategories = checkInput(hasCategories);
    let validPrice = checkInput(priceInput);
    let validDiscount = checkValidDiscount();
    let validReleaseDate = checkInputDate(releaseInput);

    if (statusInput.value == 1 && action.value == "create") {
        valid =
            validName &&
            validSlug &&
            validDes &&
            validIamgeAlt &&
            validImagePath &&
            validFile &&
            validCategories &&
            validPrice &&
            validDiscount &&
            validReleaseDate;
    } else {
        valid =
            validName &&
            validSlug &&
            validDes &&
            validIamgeAlt &&
            validImagePath &&
            validFile &&
            validCategories &&
            validPrice &&
            validDiscount;
    }

    if (valid) {
        confirmCreate(document.getElementById("productForm"));
    } else {
        event.preventDefault();
    }
}

function confirmCreate(form) {
    const modal = new bootstrap.Modal(
        document.getElementById("confirmationModal"),
    );
    modal.show();

    document.getElementById("confirmBtn").onclick = function () {
        form.submit();
        modal.hide();
    };
}

function checkValidDiscount() {
    let validDiscount = true;
    const hasDiscount = document.getElementById("availabilityCoupon");
    const discountPriceInput = document.getElementById("couponPriceLabel");
    const discountPercentInput = document.getElementById("perHundredLabel");
    const discountRelease = document.getElementById("releaseDiscountLabel");
    const discountExpired = document.getElementById("expiredDiscountLabel");
    const action = document.getElementById("action");

    if (hasDiscount.value == 1) {
        let validDiscountPrice = true;
        let validDiscountPriceInput = checkInput(discountPriceInput);
        let validDiscountPercentInput = checkInput(discountPercentInput);
        if (
            validDiscountPriceInput == false &&
            validDiscountPercentInput == false
        ) {
            validDiscountPrice = false;
        }

        let validDiscountDate = true;
        let validDiscountExpired = checkInputDate(discountExpired);
        if (action.value != "edit") {
            let validDiscountRelease = checkInputDate(discountRelease);
            validDiscountDate = validDiscountRelease && validDiscountExpired;
        } else {
            validDiscountDate = validDiscountExpired;
        }
        validDiscount = validDiscountPrice && validDiscountDate;
        return validDiscount;
    } else {
        return true;
    }
}
