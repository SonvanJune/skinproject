const wishlistItems = document.querySelectorAll(".wishlist-item");
const btn = document.getElementById("addTocart");
const form = document.getElementById("formAddToCart");

wishlistItems.forEach((item, index) => {
    item.style.animationDelay = `${(index + 1) * 0.2}s`;
});

if(btn){
btn.addEventListener("click", function(event) {
    btn.disabled = true;
    notiSuc.classList.remove("d-none");
    form.submit();
});
}


function like() {
    notiSuc.classList.remove('d-none');
}
