/**
 * ============================================================================
 * LUXURY PRODUCT IMAGE GALLERY CONTROLLER
 * High-performance image switcher, smooth cross-fades, active state tracking,
 * and seamless coordination with the 3D showcase perspective stage.
 * ============================================================================
 */

(function () {
    "use strict";

    let mainImageEl = null;
    let thumbnails = [];
    let currentIndex = 0;
    let autoSlideTimer = null;
    let isUserInteracting = false;

    function init() {
        mainImageEl = document.getElementById("mainImage");
        thumbnails = Array.from(document.querySelectorAll(".thumbnail"));

        if (!mainImageEl) return;

        // Ensure the first thumbnail is active if none is selected
        if (thumbnails.length > 0) {
            const hasActive = thumbnails.some((t) => t.classList.contains("active"));
            if (!hasActive) {
                thumbnails[0].classList.add("active");
            }
        }

        bindThumbnailClicks();
        bindHoverPause();
        bindKeyboardNav();
        initSmartAutoSlide();
    }

    /**
     * Switch the displayed image with a smooth cross-fade effect
     */
    function switchImage(src, alt, index) {
        if (!mainImageEl || !src) return;

        // Subtle luxury cross-fade
        mainImageEl.style.transition = "opacity 0.22s ease-in-out, transform 0.22s ease-in-out";
        mainImageEl.style.opacity = "0.35";
        mainImageEl.style.transform = "scale(0.985)";

        const preloader = new Image();
        preloader.onload = function () {
            mainImageEl.src = src;
            if (alt) mainImageEl.alt = alt;

            mainImageEl.style.opacity = "1";
            mainImageEl.style.transform = "scale(1)";

            // Notify other interactive listeners (e.g., zoom loupe)
            window.dispatchEvent(
                new CustomEvent("productImageChanged", {
                    detail: { src: src, alt: alt, index: index },
                })
            );
        };
        preloader.onerror = function () {
            // Fallback in case of image load delay
            mainImageEl.src = src;
            mainImageEl.style.opacity = "1";
            mainImageEl.style.transform = "scale(1)";
        };
        preloader.src = src;

        // Update active class on thumbnails
        if (thumbnails.length > 0 && typeof index === "number") {
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle("active", i === index);
            });
            currentIndex = index;
        }
    }

    /**
     * Thumbnail Click Handlers
     */
    function bindThumbnailClicks() {
        thumbnails.forEach((thumb, idx) => {
            thumb.addEventListener("click", function (e) {
                e.preventDefault();
                isUserInteracting = true; // User took manual control
                stopAutoSlide();

                const targetSrc = this.getAttribute("data-src") || this.src;
                const targetAlt = this.alt || mainImageEl.alt;
                switchImage(targetSrc, targetAlt, idx);
            });
        });
    }

    /**
     * Pause slide on mouse enter
     */
    function bindHoverPause() {
        const stage = document.getElementById("productShowcaseStage") || document.getElementById("showcaseViewport");
        if (!stage) return;

        stage.addEventListener("mouseenter", function () {
            stopAutoSlide();
        });

        stage.addEventListener("mouseleave", function () {
            if (!isUserInteracting) {
                initSmartAutoSlide();
            }
        });
    }

    /**
     * Keyboard Arrow Navigation
     */
    function bindKeyboardNav() {
        document.addEventListener("keydown", function (e) {
            // Only trigger if gallery has multiple images and not typing in an input
            if (thumbnails.length <= 1) return;
            if (["INPUT", "TEXTAREA", "SELECT"].includes(document.activeElement?.tagName)) return;

            if (e.key === "ArrowRight") {
                const nextIdx = (currentIndex + 1) % thumbnails.length;
                const nextThumb = thumbnails[nextIdx];
                if (nextThumb) {
                    const src = nextThumb.getAttribute("data-src") || nextThumb.src;
                    switchImage(src, nextThumb.alt, nextIdx);
                }
            } else if (e.key === "ArrowLeft") {
                const prevIdx = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
                const prevThumb = thumbnails[prevIdx];
                if (prevThumb) {
                    const src = prevThumb.getAttribute("data-src") || prevThumb.src;
                    switchImage(src, prevThumb.alt, prevIdx);
                }
            }
        });
    }

    /**
     * Smart Auto Slide (Disabled if user manually interacts or only 1 image)
     */
    function initSmartAutoSlide() {
        stopAutoSlide();
        if (thumbnails.length <= 1 || isUserInteracting) return;

        autoSlideTimer = setInterval(function () {
            if (document.hidden || isUserInteracting) return;
            const nextIdx = (currentIndex + 1) % thumbnails.length;
            const nextThumb = thumbnails[nextIdx];
            if (nextThumb) {
                const src = nextThumb.getAttribute("data-src") || nextThumb.src;
                switchImage(src, nextThumb.alt, nextIdx);
            }
        }, 8000); // 8s polite gentle interval
    }

    function stopAutoSlide() {
        if (autoSlideTimer) {
            clearInterval(autoSlideTimer);
            autoSlideTimer = null;
        }
    }

    // Expose for external control if needed
    window.ProductGallery = {
        switchImage: switchImage,
        next: function () {
            if (!thumbnails.length) return;
            const nextIdx = (currentIndex + 1) % thumbnails.length;
            const thumb = thumbnails[nextIdx];
            switchImage(thumb.getAttribute("data-src") || thumb.src, thumb.alt, nextIdx);
        },
        prev: function () {
            if (!thumbnails.length) return;
            const prevIdx = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
            const thumb = thumbnails[prevIdx];
            switchImage(thumb.getAttribute("data-src") || thumb.src, thumb.alt, prevIdx);
        },
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();