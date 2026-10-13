/**
 * ============================================================================
 * LUXURY PRODUCT DETAIL - INTERACTIONS & CART FORM CONTROLLER
 * Supports AJAX-friendly state, double-click prevention, and smooth UX
 * ============================================================================
 */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // 1. Safe Quantity Stepper (safeguarded against missing #count)
    window.increaseValue = function () {
        const input = document.getElementById("count");
        if (!input) return;
        let currentValue = parseInt(input.value, 10) || 1;
        const max = parseInt(input.getAttribute("max"), 10) || 99;
        if (currentValue < max) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event("change", { bubbles: true }));
        }
    };

    window.decreaseValue = function () {
        const input = document.getElementById("count");
        if (!input) return;
        let currentValue = parseInt(input.value, 10) || 1;
        const min = parseInt(input.getAttribute("min"), 10) || 1;
        if (currentValue > min) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event("change", { bubbles: true }));
        }
    };

    // 2. Add To Cart Submission with Luxury Loading Feedback
    const formAddToCart = document.getElementById("formAddToCart");
    const btnAddToCart = document.getElementById("addTocart");

    if (btnAddToCart && formAddToCart) {
        let isSubmitting = false;

        formAddToCart.addEventListener("submit", function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;

            // Apply loading state to luxury shimmer button
            btnAddToCart.classList.add("disabled", "btn-loading");
            const btnSpan = btnAddToCart.querySelector("span");
            if (btnSpan) {
                btnSpan.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Processing...';
            }

            // Reveal notification loading overlay if present
            const notiSuc = document.getElementById("notiSuc");
            if (notiSuc) {
                notiSuc.classList.remove("d-none");
            }
        });
    }

    // 3. Add to Cart for Unauthenticated Users (Modal Trigger & Haptic Pulse)
    const btnNoLogin = document.getElementById("addTocartNoLogin");
    if (btnNoLogin) {
        btnNoLogin.addEventListener("click", function (e) {
            this.classList.add("clicked-pulse");
            setTimeout(() => this.classList.remove("clicked-pulse"), 500);

            if (typeof openModalLoginRegis === "function") {
                openModalLoginRegis();
            }
        });
    }

    // 4. Instant Download Feedback for Purchased Products
    const downloadForms = document.querySelectorAll("form[action*='downloadProduct']");
    downloadForms.forEach((form) => {
        form.addEventListener("submit", function () {
            const btn = this.querySelector(".btn-down");
            if (btn) {
                btn.style.opacity = "0.7";
                btn.style.pointerEvents = "none";
                setTimeout(() => {
                    btn.style.opacity = "1";
                    btn.style.pointerEvents = "auto";
                }, 3000);
            }
        });
    });
});
