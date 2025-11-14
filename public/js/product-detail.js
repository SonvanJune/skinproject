function increaseValue() {
    const input = document.getElementById("count");
    let currentValue = parseInt(input.value, 10);
    input.value = currentValue + 1;
}

function decreaseValue() {
    const input = document.getElementById("count");
    let currentValue = parseInt(input.value, 10);

    if (currentValue > 0) {
        input.value = currentValue - 1;
    }
}

if(document
    .getElementById("addTocart")){
    document
    .getElementById("addTocart")
    .addEventListener("click", function (event) {
        const form = document.getElementById("formAddToCart");
        const notiSuc = document.getElementById("notiSuc");
        btn.disabled = true;
        notiSuc.classList.remove("d-none");
        form.submit();
    });
    }

