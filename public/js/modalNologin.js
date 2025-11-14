function openModalLoginRegis(){
    const modal = document.getElementById("loginRegisterModal");
    const openBtn = document.getElementById("addTocartNoLogin");
    const closeBtn = document.querySelector(".custom-close");

    modal.style.display = "block";

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
}
