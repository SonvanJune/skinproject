const modal = document.getElementById("modal");

function openCartModal() {
    if (modal.classList.contains("show")) {
        modal.classList.remove("show");
        setTimeout(() => (modal.style.display = "none"), 300);
    } else {
        modal.style.display = "block"; // Hiện modal
        setTimeout(() => modal.classList.add("show"), 10);
    }
};

function closeCartModal() {
    modal.classList.remove("show");
    setTimeout(() => (modal.style.display = "none"), 300); // Ẩn modal sau khi hiệu ứng hoàn tất
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.classList.remove("show");
        setTimeout(() => (modal.style.display = "none"), 300); // Ẩn modal sau khi hiệu ứng hoàn tất
    }
};
