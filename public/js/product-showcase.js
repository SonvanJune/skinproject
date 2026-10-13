/**
 * ============================================================================
 * LUXURY PRODUCT IMAGE SHOWCASE & CAD INSPECTOR
 * Applied dynamically to ANY real product image ($mainImage)
 *
 * Bước 1 (Đập vào mắt): 3D Parallax Tilt & Ánh kim loại lướt qua thân sản phẩm
 * Bước 2 (Khám phá): CAD Dark Mode & Viền mí vàng đồng sáng rực (Vector Blueprint)
 *                    + High-Precision Inspection Loupe (±0.05mm)
 * Bước 3 (Thử chất liệu): Click "Matte Stealth" ➔ Xoay 360°, đổi nền xanh đen lịch lãm
 * Bước 4 (An tâm & Mua hàng): Badges SVG, DXF, AI né nhẹ theo chuột (Magnetic Repel)
 *                             + Nút Add to Cart ánh kim đồng quét qua
 * ============================================================================
 */

(function () {
    "use strict";

    let showcaseEl, viewportEl, cardEl, sheenEl, mainImgEl;
    let modeBtns, finishCards, spinBtn, zoomToggleBtn;
    let loupeEl, loupeCircleEl, loupeCoordsEl;
    let repelBadges = [];

    const state = {
        mode: "render", // 'render' | 'blueprint'
        finish: "gloss-clear", // 'gloss-clear' | 'matte-stealth' | 'forged-carbon' | 'golden-bronze'
        isSpinning: false,
        isLoupeActive: false,
        mouse: { x: 0, y: 0, targetX: 0, targetY: 0 },
    };

    function init() {
        showcaseEl = document.getElementById("productShowcaseStage");
        if (!showcaseEl) return;

        viewportEl = document.getElementById("showcaseViewport");
        cardEl = document.getElementById("showcaseCard");
        sheenEl = document.getElementById("metallicSheen");
        mainImgEl = document.getElementById("mainImage");

        modeBtns = showcaseEl.querySelectorAll(".btn-mode-toggle");
        finishCards = showcaseEl.querySelectorAll(".card-finish");
        spinBtn = document.getElementById("btn360Spin");
        zoomToggleBtn = document.getElementById("btnToggleZoom");
        loupeEl = document.getElementById("cadInspectLoupe");
        loupeCircleEl = document.getElementById("loupeCircle");
        loupeCoordsEl = document.getElementById("loupeCoords");
        repelBadges = document.querySelectorAll(".repel-badge");

        bindMouseParallax();
        bindModeSwitcher();
        bindMaterialFinishes();
        bindSpinButton();
        bindLoupeInspector();
        bindRepelPhysics();
        bindAddToCartShimmer();
        bindImageChangeListener();

        requestAnimationFrame(updateParallaxLoop);
    }

    /**
     * Bước 1: 3D Mouse Parallax & Metallic Sheen Sweep
     */
    function bindMouseParallax() {
        if (!viewportEl) return;

        viewportEl.addEventListener("mousemove", function (e) {
            if (state.isSpinning) return;
            const rect = viewportEl.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            state.mouse.targetX = (x - 0.5) * 2; // -1 to +1
            state.mouse.targetY = (y - 0.5) * 2; // -1 to +1

            if (state.isLoupeActive) {
                updateLoupe(e, rect, x, y);
            }
        });

        viewportEl.addEventListener("mouseleave", function () {
            state.mouse.targetX = 0;
            state.mouse.targetY = 0;
            if (loupeEl) {
                loupeEl.classList.add("d-none");
            }
        });

        viewportEl.addEventListener("mouseenter", function () {
            if (state.isLoupeActive && loupeEl) {
                loupeEl.classList.remove("d-none");
            }
        });
    }

    function updateParallaxLoop() {
        if (!state.isSpinning && cardEl) {
            // Smooth lerp
            state.mouse.x += (state.mouse.targetX - state.mouse.x) * 0.1;
            state.mouse.y += (state.mouse.targetY - state.mouse.y) * 0.1;

            // Don't tilt violently when loupe is actively inspecting
            const tiltScale = state.isLoupeActive ? 0.35 : 1.0;
            const tiltY = state.mouse.x * 12 * tiltScale;
            const tiltX = -state.mouse.y * 9 * tiltScale;

            cardEl.style.transform = `perspective(1000px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg)`;

            // Glide metallic sheen across the product surface
            if (sheenEl) {
                const shiftX = state.mouse.x * 60;
                const shiftY = state.mouse.y * 30;
                sheenEl.style.transform = `translate(${shiftX}px, ${shiftY}px)`;
            }
        }

        requestAnimationFrame(updateParallaxLoop);
    }

    /**
     * Bước 2: Nút gạt Switch sang "Vector Blueprint" (CAD Dark Mode & Viền vàng đồng)
     */
    function bindModeSwitcher() {
        if (!modeBtns.length) return;

        modeBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
                const targetMode = this.dataset.mode;
                if (targetMode === state.mode) return;

                state.mode = targetMode;
                modeBtns.forEach((b) => b.classList.remove("active"));
                this.classList.add("active");

                showcaseEl.setAttribute("data-mode", targetMode);
            });
        });
    }

    /**
     * Bước 2.5: High-Precision CAD Loupe / Inspection Magnifier (±0.05mm)
     */
    function bindLoupeInspector() {
        if (!zoomToggleBtn) return;

        zoomToggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            state.isLoupeActive = !state.isLoupeActive;

            this.classList.toggle("active", state.isLoupeActive);

            if (loupeEl) {
                if (state.isLoupeActive) {
                    loupeEl.classList.remove("d-none");
                    updateLoupeImage();
                } else {
                    loupeEl.classList.add("d-none");
                }
            }
        });
    }

    function updateLoupeImage() {
        if (!loupeCircleEl || !mainImgEl) return;
        loupeCircleEl.style.backgroundImage = `url("${mainImgEl.src}")`;
    }

    function updateLoupe(e, rect, ratioX, ratioY) {
        if (!loupeEl || !loupeCircleEl || !mainImgEl) return;

        const posX = e.clientX - rect.left;
        const posY = e.clientY - rect.top;

        // Position loupe centered at cursor
        loupeEl.style.transform = `translate(${posX}px, ${posY}px)`;

        // 2.5x high-precision optical magnification
        const zoom = 2.5;
        const imgW = mainImgEl.clientWidth || rect.width;
        const imgH = mainImgEl.clientHeight || rect.height;

        loupeCircleEl.style.backgroundSize = `${imgW * zoom}px ${imgH * zoom}px`;
        loupeCircleEl.style.backgroundPosition = `${ratioX * 100}% ${ratioY * 100}%`;

        if (loupeCoordsEl) {
            const cadX = (ratioX * 100).toFixed(1);
            const cadY = (ratioY * 100).toFixed(1);
            loupeCoordsEl.innerText = `CAD X:${cadX}% Y:${cadY}% • ±0.05mm`;
        }
    }

    /**
     * Bước 3: Thử chất liệu (Matte Stealth xoay 360° & nền xanh đen lịch lãm)
     */
    function bindMaterialFinishes() {
        if (!finishCards.length) return;

        finishCards.forEach((card) => {
            card.addEventListener("click", function () {
                const finish = this.dataset.finish;
                state.finish = finish;

                finishCards.forEach((c) => c.classList.remove("active"));
                this.classList.add("active");

                showcaseEl.setAttribute("data-finish", finish);

                // Kích hoạt xoay 360°
                trigger360Spin();

                // Nếu là Matte Stealth ➔ chuyển nền website sang xanh đen lịch lãm (Midnight Petrol Teal)
                if (finish === "matte-stealth") {
                    document.documentElement.setAttribute("data-studio-theme", "stealth-midnight");
                } else {
                    document.documentElement.removeAttribute("data-studio-theme");
                }
            });
        });
    }

    /**
     * Xoay 360° Animation
     */
    function trigger360Spin() {
        if (state.isSpinning || !cardEl) return;
        state.isSpinning = true;

        cardEl.classList.add("spinning-360");

        setTimeout(() => {
            cardEl.classList.remove("spinning-360");
            state.isSpinning = false;
        }, 1250);
    }

    function bindSpinButton() {
        if (!spinBtn) return;
        spinBtn.addEventListener("click", function (e) {
            e.preventDefault();
            trigger360Spin();
        });
    }

    /**
     * Bước 4: Huy hiệu File SVG, DXF, AI, PDF Né nhẹ khi chuột lướt qua (Magnetic Dodge Physics)
     */
    function bindRepelPhysics() {
        if (!repelBadges.length) return;

        const maxDist = 65;
        const repelPower = 14;

        document.addEventListener("mousemove", function (e) {
            repelBadges.forEach((badge) => {
                const rect = badge.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;

                const dx = e.clientX - centerX;
                const dy = e.clientY - centerY;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < maxDist && dist > 0) {
                    const ratio = 1 - dist / maxDist;
                    const pushX = -(dx / dist) * repelPower * ratio;
                    const pushY = -(dy / dist) * repelPower * ratio;

                    badge.style.transform = `translate(${pushX.toFixed(2)}px, ${pushY.toFixed(2)}px) scale(1.05)`;
                    badge.classList.add("repelling");
                } else {
                    badge.style.transform = "translate(0px, 0px) scale(1)";
                    badge.classList.remove("repelling");
                }
            });
        });
    }

    /**
     * Bước 4: Add to Cart ánh kim đồng quét qua
     */
    function bindAddToCartShimmer() {
        const cartBtns = document.querySelectorAll("#addTocart, #addTocartNoLogin, .form-add-cart button");
        cartBtns.forEach((btn) => {
            btn.classList.add("bronze-shimmer-btn");
            btn.addEventListener("click", function () {
                this.classList.add("clicked-pulse");
                setTimeout(() => this.classList.remove("clicked-pulse"), 600);
            });
        });
    }

    /**
     * Lắng nghe sự kiện đổi ảnh từ gallery (product-image.js)
     */
    function bindImageChangeListener() {
        window.addEventListener("productImageChanged", function (e) {
            if (state.isLoupeActive) {
                updateLoupeImage();
            }
        });
    }

    // Khởi tạo
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    window.PPFProductShowcase = {
        init: init,
        spin: trigger360Spin,
        toggleLoupe: function () {
            if (zoomToggleBtn) zoomToggleBtn.click();
        },
    };
})();
