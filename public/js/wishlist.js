const wishlistItems = document.querySelectorAll(".wishlist-item");
wishlistItems.forEach((item, index) => {
    item.style.animationDelay = `${(index + 1) * 0.2}s`;
});

function like() {
    const notiSuc = document.getElementById('notiSuc');
    if (notiSuc) {
        notiSuc.classList.remove('d-none');
    }
}
