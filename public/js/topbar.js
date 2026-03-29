// document.addEventListener("DOMContentLoaded", function () {
//     var dropdownToggle = document.querySelector(".dropdown-toggle-bot");
//     var dropdownMenu = document.querySelector(".dropdown-menu-bot");

//     dropdownToggle.addEventListener("click", function () {
//         dropdownMenu.classList.toggle("show");
//     });

//     // Close the dropdown when clicking outside of it
//     document.addEventListener("click", function (event) {
//         if (
//             !dropdownToggle.contains(event.target) &&
//             !dropdownMenu.contains(event.target)
//         ) {
//             dropdownMenu.classList.remove("show");
//         }
//     });
// });


let lastScrollTop = 0;
const navbar = document.querySelector(".kb-navbar");
navbar.classList.add("show");


window.addEventListener("scroll", () => {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        // scroll xuống → ẩn
        navbar.classList.remove("show");
        navbar.classList.add("hide");
    } else {
        // scroll lên → hiện
        navbar.classList.remove("hide");
        navbar.classList.add("show");
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
});

const items = document.querySelectorAll(".kb-menu-list li");
const preview = document.getElementById("kb-preview-img");

items.forEach((item) => {
    item.addEventListener("mouseenter", () => {
        const img = item.getAttribute("data-img");
        preview.style.opacity = 0;
        preview.style.transform = "scale(0.95)";

        setTimeout(() => {
            preview.src = img;
            preview.style.opacity = 1;
            preview.style.transform = "scale(1)";
        }, 150);
    });
});

const menu = document.querySelector(".kb-mega-left");
