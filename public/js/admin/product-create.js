document
    .getElementById("productNameLabel")
    .addEventListener("input", function () {
        const productName = this.value;

        const slug = productName
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "");

        document.getElementById("productSlugLabel").value = slug;
    });
window.onload = function () {
    const discountRelease = document.getElementById("releaseDiscountLabel");
    const discountExpired = document.getElementById("expiredDiscountLabel");
    if (document.getElementById("availabilitySwitch1").checked) {
        document.getElementById("release-in").style.display = "block";
        document.getElementById("releaseLabel").value = resetRelease();
        document.getElementById("availabilitySwitch1").value = 1;
    } else {
        document.getElementById("release-in").style.display = "none";
        document.getElementById("releaseLabel").value = resetRelease();
        document.getElementById("availabilitySwitch1").value = 0;
    }

    if (document.getElementById("availabilitySwitchView").checked) {
        document.getElementById("availabilitySwitchView").value = 1;
    } else {
        document.getElementById("availabilitySwitchView").value = 0;
    }

    if (document.getElementById("availabilityCoupon").checked) {
        document.getElementById("availabilityCoupon").value = 1;
        document.getElementById("discount-in").style.display = "block";
        discountRelease.value = resetDiscountRelease();
        discountExpired.value = resetDiscountExpired();
    } else {
        document.getElementById("availabilityCoupon").value = 0;
        document.getElementById("discount-in").style.display = "none";
        discountRelease.value = null;
        discountExpired.value = null;
    }
};

document
    .getElementById("availabilitySwitch1")
    .addEventListener("change", function () {
        if (this.checked) {
            document.getElementById("release-in").style.display = "block";
            document.getElementById("releaseLabel").value = resetRelease();
            this.value = 1;
        } else {
            document.getElementById("release-in").style.display = "none";
            document.getElementById("releaseLabel").value = resetRelease();
            this.value = 0;
        }
    });

function resetRelease() {
    const input = document.getElementById("releaseLabel");
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const hour = String(now.getHours()).padStart(2, "0");
    const minute = String(now.getMinutes()).padStart(2, "0");
    const second = String(now.getSeconds()).padStart(2, "0");

    const formatted = `${year}-${month}-${day}T${hour}:${minute}:${second}`;
    input.value = formatted;
    return formatted;
}

function resetDiscountRelease() {
    const input = document.getElementById("releaseDiscountLabel");
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const hour = String(now.getHours()).padStart(2, "0");
    const minute = String(now.getMinutes()).padStart(2, "0");
    const second = String(now.getSeconds()).padStart(2, "0");

    const formatted = `${year}-${month}-${day}T${hour}:${minute}:${second}`;
    input.value = formatted;
    return formatted;
}

function resetDiscountExpired() {
    const input = document.getElementById("expiredDiscountLabel");
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate() + 1).padStart(2, "0");
    const hour = String(now.getHours()).padStart(2, "0");
    const minute = String(now.getMinutes()).padStart(2, "0");
    const second = String(now.getSeconds()).padStart(2, "0");

    const formatted = `${year}-${month}-${day}T${hour}:${minute}:${second}`;
    input.value = formatted;
    return formatted;
}

document
    .getElementById("availabilitySwitchView")
    .addEventListener("change", function () {
        if (this.checked) {
            this.value = 1;
        } else {
            this.value = 0;
        }
    });

document
    .getElementById("availabilityCoupon")
    .addEventListener("change", function () {
        const discountRelease = document.getElementById("releaseDiscountLabel");
        const discountExpired = document.getElementById("expiredDiscountLabel");
        if (this.checked) {
            document.getElementById("discount-in").style.display = "block";
            this.value = 1;
            discountRelease.value = resetDiscountRelease();
            discountExpired.value = resetDiscountExpired();
        } else {
            document.getElementById("discount-in").style.display = "none";
            this.value = 0;
            discountRelease.value = null;
            discountExpired.value = null;
        }
    });

document
    .getElementById("perHundredLabel")
    .addEventListener("input", function () {
        onInputPrice();
    });

document
    .getElementById("couponPriceLabel")
    .addEventListener("input", function () {
        onInputPrice();
    });

function onInputPrice() {
    const priceInput = document.getElementById("couponPriceLabel");
    const percentInput = document.getElementById("perHundredLabel");
    const productPriceInput = document.getElementById("priceNameLabel");
    if (priceInput.value === "" && percentInput.value === "") {
        priceInput.classList.add("is-invalid");
        priceInput.classList.remove("is-valid");
        percentInput.classList.add("is-invalid");
        percentInput.classList.remove("is-valid");
        percentInput.disabled = false;
        percentInput.setAttribute("required", true);
        priceInput.disabled = false;
        priceInput.setAttribute("required", true);
    }

    if (priceInput.value) {
        if (priceInput.value <= 0 || priceInput.value >= productPriceInput.value) {
            priceInput.value = 1;
        }
        priceInput.classList.add("is-valid");
        priceInput.classList.remove("is-invalid");
        percentInput.disabled = true;
        percentInput.removeAttribute("required");
        percentInput.classList.remove("is-invalid");
        percentInput.classList.remove("is-valid");
        percentInput.value = null;
    }

    if (percentInput.value) {
        if (percentInput.value <= 0 || percentInput.value > 100) {
            percentInput.value = 100;
        }
        percentInput.classList.add("is-valid");
        percentInput.classList.remove("is-invalid");
        priceInput.disabled = true;
        priceInput.removeAttribute("required");
        priceInput.classList.remove("is-invalid");
        priceInput.classList.remove("is-valid");
        priceInput.value = null;
    }
}
