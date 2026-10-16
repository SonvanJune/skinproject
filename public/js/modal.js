const modal = document.getElementById("modal");

function refreshCartModal() {
    const container = document.getElementById("cartModalContainer");
    const fetchUrl = (container && container.getAttribute("data-url"))
        || window.cartModalItemsUrl
        || (window.location.pathname.startsWith('/vi') ? '/vi/cart/modal-items' : (window.location.pathname.startsWith('/en') ? '/en/cart/modal-items' : '/cart/modal-items'));

    fetch(fetchUrl, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) return null;
        return res.json();
    })
    .then(data => {
        if (data && data.status === "success") {
            if (data.cart_html && container) {
                container.innerHTML = data.cart_html;
            }
            if (typeof data.cart_count !== "undefined" && typeof window.updateCartBadges === "function") {
                window.updateCartBadges(data.cart_count);
            }
        }
    })
    .catch(err => {
        console.warn("Could not sync cart modal:", err);
    });
}

function openCartModal() {
    if (!modal) return;
    if (modal.classList.contains("show")) {
        modal.classList.remove("show");
        setTimeout(() => (modal.style.display = "none"), 300);
    } else {
        modal.style.display = "block"; // Hiện modal
        setTimeout(() => modal.classList.add("show"), 10);
        // Refresh cart modal items in background to ensure sync
        refreshCartModal();
    }
};

function closeCartModal() {
    if (!modal) return;
    modal.classList.remove("show");
    setTimeout(() => (modal.style.display = "none"), 300); // Ẩn modal sau khi hiệu ứng hoàn tất
}

window.openCartModal = openCartModal;
window.closeCartModal = closeCartModal;
window.refreshCartModal = refreshCartModal;

document.addEventListener("click", function (event) {
    if (modal && modal.classList.contains("show")) {
        if (!modal.contains(event.target) && !event.target.closest("#openModal") && !event.target.closest(".cart")) {
            closeCartModal();
        }
    }
});

