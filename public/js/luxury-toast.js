/**
 * ============================================================================
 * LUXURY TOAST CONTROLLER - CLIENT NOTIFICATION SYSTEM
 * Unified modern toast notifications for all customer-facing pages.
 * Powered by Toastify library with automated fallback and responsive styling.
 * ============================================================================
 */

(function () {
    "use strict";

    const ICONS = {
        success: "bi bi-check-circle-fill",
        error: "bi bi-x-circle-fill",
        warning: "bi bi-exclamation-triangle-fill",
        info: "bi bi-info-circle-fill"
    };

    const TITLES = {
        success: "Success",
        error: "Error",
        warning: "Notice",
        info: "Information"
    };

    function createToastElement(message, type, title, duration, closeToastCallback) {
        const wrapper = document.createElement("div");
        wrapper.className = "luxury-toast-body";

        const iconClass = ICONS[type] || ICONS.info;
        const displayTitle = title || TITLES[type] || "Notification";

        wrapper.innerHTML = `
            <div class="luxury-toast-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="luxury-toast-content">
                <div class="luxury-toast-title">${displayTitle}</div>
                <div class="luxury-toast-message">${message}</div>
            </div>
            <button type="button" class="luxury-toast-close" aria-label="Close">&times;</button>
            <div class="luxury-toast-progress" style="animation-duration: ${duration}ms;"></div>
        `;

        const closeBtn = wrapper.querySelector(".luxury-toast-close");
        if (closeBtn && typeof closeToastCallback === "function") {
            closeBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                closeToastCallback();
            });
        }

        return wrapper;
    }

    // Fallback renderer when Toastify library is not available
    function showFallbackToast(message, type, title, duration) {
        let container = document.getElementById("luxuryFallbackToastContainer");
        if (!container) {
            container = document.createElement("div");
            container.id = "luxuryFallbackToastContainer";
            container.style.cssText = `
                position: fixed;
                top: 85px;
                right: 24px;
                z-index: 100080;
                display: flex;
                flex-direction: column;
                gap: 12px;
                pointer-events: none;
                max-width: 90vw;
            `;
            document.body.appendChild(container);
        }

        const toast = document.createElement("div");
        toast.className = `toastify toast-${type}`;
        toast.style.pointerEvents = "auto";

        const contentNode = createToastElement(message, type, title, duration, () => {
            dismissFallback(toast);
        });
        toast.appendChild(contentNode);
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add("on");
        });

        const timer = setTimeout(() => {
            dismissFallback(toast);
        }, duration);

        function dismissFallback(target) {
            clearTimeout(timer);
            target.classList.remove("on");
            target.style.transform = "translateY(-15px) scale(0.95)";
            target.style.opacity = "0";
            setTimeout(() => {
                if (target.parentNode) {
                    target.parentNode.removeChild(target);
                }
            }, 350);
        }
    }

    const recentToasts = new Set();

    const LuxuryToast = {
        show: function (message, type = "info", title = null, options = {}) {
            if (!message) return;

            // Normalize type
            type = (type || "info").toLowerCase();
            if (type === "danger" || type === "failed") type = "error";
            if (type === "warn") type = "warning";
            if (!ICONS[type]) type = "info";

            // Prevent duplicate toasts within 2 seconds
            const dedupeKey = `${type}:${message.trim()}`;
            if (recentToasts.has(dedupeKey)) {
                return;
            }
            recentToasts.add(dedupeKey);
            setTimeout(() => {
                recentToasts.delete(dedupeKey);
            }, 2000);

            const duration = options.duration || 4500;
            const gravity = options.gravity || "top";
            const position = options.position || "right";
            const offset = options.offset || { x: 24, y: 85 };

            // Mobile vibration if supported
            if (window.navigator && window.navigator.vibrate) {
                try {
                    window.navigator.vibrate(type === "error" ? [30, 40, 30] : 20);
                } catch (_) {}
            }

            // Use Toastify if loaded
            if (typeof Toastify === "function") {
                let toastInstance = null;
                const toastNode = createToastElement(message, type, title, duration, () => {
                    if (toastInstance && typeof toastInstance.hideToast === "function") {
                        toastInstance.hideToast();
                    }
                });

                toastInstance = Toastify({
                    node: toastNode,
                    duration: duration,
                    gravity: gravity,
                    position: position,
                    offset: offset,
                    stopOnFocus: true,
                    className: `toast-${type}`,
                    style: {
                        background: "transparent",
                        boxShadow: "none"
                    }
                });

                toastInstance.showToast();
                return toastInstance;
            } else {
                // Fallback to native DOM toast
                return showFallbackToast(message, type, title, duration);
            }
        },

        success: function (message, title = null, options = {}) {
            return this.show(message, "success", title, options);
        },

        error: function (message, title = null, options = {}) {
            return this.show(message, "error", title, options);
        },

        warning: function (message, title = null, options = {}) {
            return this.show(message, "warning", title, options);
        },

        info: function (message, title = null, options = {}) {
            return this.show(message, "info", title, options);
        }
    };

    // Expose globals
    window.LuxuryToast = LuxuryToast;
    window.showLuxuryToast = function (message, type, title, options) {
        return LuxuryToast.show(message, type, title, options);
    };
    window.showLuxuryCartToast = function (message, type = "success") {
        return LuxuryToast.show(message, type);
    };
})();
