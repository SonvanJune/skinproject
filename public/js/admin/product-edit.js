const postContentInput = document.getElementById("postContent");
if (postContentInput.value) {
    quill.clipboard.dangerouslyPasteHTML(postContentInput.value);
    const length = quill.getLength();
    quill.setSelection(length, 0);
}

quill.on("text-change", function () {
    postContentInput.value = quill.root.innerHTML;
});

const closeIcons = document.querySelectorAll(".remove-image");
closeIcons.forEach((closeIcon) => {
    closeIcon.addEventListener("click", function () {
        const previewItem = this.closest(".dz-preview");
        if (previewItem) {
            previewItem.remove();
        }
    });
});

document.querySelectorAll('input[id^="imageAltLabel_"]').forEach((input) => {
    input.addEventListener("input", function () {
        const index = this.id.split("_")[1];
        const altText = this.value;

        productImages[index].product_image_alt = altText;
    });
});

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
    .getElementById("availabilityCoupon")
    .addEventListener("change", function () {
        let discountRelease = document.getElementById("releaseDiscountLabel");
        let discountExpired = document.getElementById("expiredDiscountLabel");
        onLoadInputPrice();
        if (discount) {
            let couponPriceLabel = document.getElementById("couponPriceLabel");
            let perHundredLabel = document.getElementById("perHundredLabel");

            if (this.checked) {
                document.getElementById("discount-in").style.display = "block";
                this.value = 1;

                discountRelease.value = discount.coupon_release ?? "";
                discountExpired.value = discount.coupon_expired ?? "";
                couponPriceLabel.value = discount.coupon_price ?? "";
                perHundredLabel.value = discount.coupon_per_hundred ?? "";
            } else {
                document.getElementById("discount-in").style.display = "none";
                this.value = 0;

                discountRelease.value = null;
                discountExpired.value = null;
                couponPriceLabel.value = discount.coupon_price;
                perHundredLabel.value = discount.coupon_per_hundred;
            }
        } else {
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
        }
    });
window.onload = function () {
    onLoadInputPrice();
    let discountRelease = document.getElementById("releaseDiscountLabel");
    let discountExpired = document.getElementById("expiredDiscountLabel");
    if (discount) {
        let couponPriceLabel = document.getElementById("couponPriceLabel");
        let perHundredLabel = document.getElementById("perHundredLabel");
        if (document.getElementById("availabilityCoupon").checked) {
            document.getElementById("discount-in").style.display = "block";
            document.getElementById("availabilityCoupon").value = 1;

            discountRelease.value = discount.coupon_release ?? "";
            discountExpired.value = discount.coupon_expired ?? "";
            couponPriceLabel.value = discount.coupon_price ?? "";
            perHundredLabel.value = discount.coupon_per_hundred ?? "";
        } else {
            document.getElementById("discount-in").style.display = "none";
            document.getElementById("availabilityCoupon").value = 0;

            discountRelease.value = discount.coupon_release ?? "";
            discountExpired.value = discount.coupon_expired ?? "";
            couponPriceLabel.value = discount.coupon_price ?? "";
            perHundredLabel.value = discount.coupon_per_hundred ?? "";
        }
    } else {
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
    }

    if (document.getElementById("availabilitySwitch1").checked) {
        document.getElementById("release-in").style.display = "block";
        document.getElementById("releaseLabel").value = product.post_release;
        document.getElementById("availabilitySwitch1").value = 1;
    } else {
        document.getElementById("release-in").style.display = "none";
        document.getElementById("releaseLabel").value = product.post_release;
        document.getElementById("availabilitySwitch1").value = 0;
    }

    if (document.getElementById("availabilitySwitchView").checked) {
        document.getElementById("availabilitySwitchView").value = 1;
    } else {
        document.getElementById("availabilitySwitchView").value = 0;
    }
};

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
    matchPriceSale();
    const priceInput = document.getElementById("couponPriceLabel");
    const percentInput = document.getElementById("perHundredLabel");
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

function onLoadInputPrice() {
    matchPriceSale();
    const priceInput = document.getElementById("couponPriceLabel");
    const percentInput = document.getElementById("perHundredLabel");
    percentInput.classList.remove("is-invalid");
    percentInput.classList.remove("is-valid");
    priceInput.classList.remove("is-invalid");
    priceInput.classList.remove("is-valid");
    if (priceInput.value === "" && percentInput.value === "") {
        percentInput.disabled = false;
        percentInput.setAttribute("required", true);
        priceInput.disabled = false;
        priceInput.setAttribute("required", true);
    }

    if (priceInput.value) {
        if (priceInput.value <= 0) {
            priceInput.value = 1;
        }
        percentInput.disabled = true;
        percentInput.removeAttribute("required");
        percentInput.value = null;
    }

    if (percentInput.value) {
        if (percentInput.value <= 0 || percentInput.value > 100) {
            percentInput.value = 100;
        }
        priceInput.disabled = true;
        priceInput.removeAttribute("required");
        priceInput.value = null;
    }
}

function matchPriceSale() {
    const priceNameLabel = document.getElementById("priceNameLabel");
    const priceSale = document.getElementById("priceSale");
    const priceInput = document.getElementById("couponPriceLabel");
    const percentInput = document.getElementById("perHundredLabel");

    if (priceInput.value === "" && percentInput.value === "") {
        priceSale.value = 0;
    }

    if (priceInput.value) {
        if (
            parseFloat(priceInput.value) <= 0 ||
            parseFloat(priceInput.value) >= parseFloat(priceNameLabel.value)
        ) {
            priceInput.value = 1;
        }
        priceSale.value = priceNameLabel.value - priceInput.value;
    }

    if (percentInput.value) {
        if (
            parseFloat(percentInput.value <= 0) ||
            parseFloat(percentInput.value) > 100
        ) {
            percentInput.value = 100;
        }
        priceSale.value =
            priceNameLabel.value -
            (priceNameLabel.value * percentInput.value) / 100;
    }
}

document
    .getElementById("availabilitySwitch1")
    .addEventListener("change", function () {
        if (this.checked) {
            document.getElementById("release-in").style.display = "block";
            document.getElementById("releaseLabel").value =
                product.post_release;
            this.value = 1;
        } else {
            document.getElementById("release-in").style.display = "none";
            document.getElementById("releaseLabel").value = resetRelease();
            this.value = 0;
        }
    });

document
    .getElementById("availabilitySwitchView")
    .addEventListener("change", function () {
        if (this.checked) {
            this.value = 1;
        } else {
            this.value = 0;
        }
    });
