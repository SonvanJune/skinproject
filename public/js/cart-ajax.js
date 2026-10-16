/**
 * ============================================================================
 * GLOBAL AJAX ADD TO CART HANDLER - LUXURY UI / NO PAGE RELOAD
 * Intercepts all cart add submissions, updates navbar counters, bounces cart icon,
 * and renders luxury animated floating toast notifications.
 * ============================================================================
 */

(function () {
    "use strict";

    // Inject keyframes & toast styles if not already present
    function injectCartStyles() {
        if (document.getElementById("cart-ajax-style")) return;
        const style = document.createElement("style");
        style.id = "cart-ajax-style";
        style.textContent = `
            @keyframes cartBadgePop {
                0% { transform: scale(1); }
                40% { transform: scale(1.45); }
                70% { transform: scale(0.9); }
                100% { transform: scale(1); }
            }
            @keyframes cartIconBounce {
                0% { transform: scale(1) translateY(0); }
                25% { transform: scale(1.25) translateY(-4px) rotate(-6deg); }
                50% { transform: scale(1.1) translateY(2px) rotate(4deg); }
                75% { transform: scale(1.05) translateY(-1px) rotate(-2deg); }
                100% { transform: scale(1) translateY(0) rotate(0deg); }
            }
            .cart-bounce-effect {
                animation: cartIconBounce 0.65s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            }
            .cart-badge-pop {
                animation: cartBadgePop 0.4s ease-out !important;
            }
            .btn-success-temporary {
                background: linear-gradient(135deg, rgba(22, 163, 74, 0.9), rgba(34, 197, 94, 0.9)) !important;
                color: #ffffff !important;
                border-color: rgba(74, 222, 128, 0.6) !important;
                box-shadow: 0 0 15px rgba(34, 197, 94, 0.4) !important;
            }
        `;
        document.head.appendChild(style);
    }

    // Unified Toast Trigger (bridges to window.LuxuryToast)
    function showLuxuryCartToast(message, type = "success") {
        if (window.LuxuryToast) {
            window.LuxuryToast.show(message, type);
        }
    }

    // Update cart badge counts across navbar
    function updateCartBadges(cartCount) {
        if (typeof cartCount === "undefined") return;

        const badges = document.querySelectorAll(".quantity-item");
        badges.forEach((badge) => {
            const innerSpan = badge.querySelector("span");
            if (innerSpan) {
                innerSpan.textContent = cartCount;
            } else {
                badge.textContent = cartCount;
            }
            badge.classList.remove("cart-badge-pop");
            void badge.offsetWidth; // Trigger reflow
            badge.classList.add("cart-badge-pop");
        });

        // Bouncing animations for cart icons
        const cartIcons = document.querySelectorAll(
            ".icon.cart, .cart-icon-wrapper, .btbar-item .bi-cart-fill, .icon.cart .bi-cart3"
        );
        cartIcons.forEach((icon) => {
            icon.classList.remove("cart-bounce-effect");
            void icon.offsetWidth;
            icon.classList.add("cart-bounce-effect");
            setTimeout(() => icon.classList.remove("cart-bounce-effect"), 700);
        });
    }

    // Attach listener to handle form submits via event delegation
    document.addEventListener("DOMContentLoaded", function () {
        injectCartStyles();

        document.addEventListener("submit", function (e) {
            const form = e.target;
            if (!form || !form.tagName || form.tagName.toLowerCase() !== "form") {
                return;
            }

            const action = (form.getAttribute("action") || form.action || "").toLowerCase();
            const hasProductSlug = !!form.querySelector('input[name="product_slug"]');
            const isCartAdd = action.includes("/cart/add") || form.classList.contains("form-add-cart") || (hasProductSlug && form.id === "formAddToCart");

            if (!isCartAdd) {
                return;
            }

            // PREVENT FULL PAGE RELOAD
            e.preventDefault();
            e.stopPropagation();

            if (form.getAttribute("data-submitting") === "true") {
                return false;
            }
            form.setAttribute("data-submitting", "true");

            const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector("button");
            let originalContent = "";
            let btnSpan = null;
            let originalSpanContent = "";

            if (submitBtn) {
                originalContent = submitBtn.innerHTML;
                btnSpan = submitBtn.querySelector("span");
                if (btnSpan) {
                    originalSpanContent = btnSpan.innerHTML;
                    btnSpan.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Adding...';
                } else {
                    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
                }
                submitBtn.disabled = true;
                submitBtn.classList.add("btn-loading");
            }

            const formData = new FormData(form);

            fetch(form.action || action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
            .then(async (response) => {
                if (response.status === 401) {
                    let errData = {};
                    try {
                        errData = await response.json();
                    } catch (_) {}

                    if (typeof openModalLoginRegis === "function") {
                        openModalLoginRegis();
                    } else if (errData.redirect) {
                        window.location.href = errData.redirect;
                    }
                    showLuxuryCartToast(errData.message || "Please log in to add items to cart", "error");
                    return null;
                }

                return response.json();
            })
            .then((data) => {
                if (!data) {
                    resetButton();
                    return;
                }

                if (data.status === "success") {
                    // 1. Update counter badges & bounce icons
                    updateCartBadges(data.cart_count);

                    // 2. Synchronize Cart Modal dropdown items in real time
                    if (data.cart_html) {
                        updateCartModalContainer(data.cart_html);
                    } else if (typeof window.refreshCartModal === "function") {
                        window.refreshCartModal();
                    }

                    // 3. Button temporary success feedback
                    if (submitBtn) {
                        submitBtn.classList.remove("btn-loading");
                        submitBtn.classList.add("btn-success-temporary");
                        if (btnSpan) {
                            btnSpan.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Added!';
                        } else {
                            submitBtn.innerHTML = '<i class="bi bi-check2-circle"></i>';
                        }

                        setTimeout(() => {
                            resetButton();
                        }, 1400);
                    } else {
                        form.removeAttribute("data-submitting");
                    }

                    // 4. Floating toast
                    showLuxuryCartToast(data.message || "Product added to cart successfully!", "success");
                } else {
                    showLuxuryCartToast(data.message || "Could not add product to cart", "error");
                    resetButton();
                }
            })
            .catch((err) => {
                console.error("Cart AJAX error:", err);
                showLuxuryCartToast("An error occurred while adding to cart", "error");
                resetButton();
            });

            function resetButton() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove("btn-loading", "btn-success-temporary", "disabled");
                    if (btnSpan) {
                        btnSpan.innerHTML = originalSpanContent;
                    }
                    submitBtn.innerHTML = originalContent;
                }
                form.removeAttribute("data-submitting");
            }
        });
    });

    function updateCartModalContainer(html) {
        if (!html) return;
        const container = document.getElementById("cartModalContainer");
        if (container) {
            container.innerHTML = html;
        }
    }

    // Provide safe globals to eliminate any inline "onclick=addToCart()" errors
    window.addToCart = function (e) {
        if (e && typeof e.preventDefault === "function") {
            e.preventDefault();
        }
    };
    window.showLuxuryCartToast = showLuxuryCartToast;
    window.updateCartBadges = updateCartBadges;
    window.updateCartModalContainer = updateCartModalContainer;
})();

