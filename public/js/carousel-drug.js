// script.js
const prevButton = document.querySelector(".carousel-custom-button.prev");
const nextButton = document.querySelector(".carousel-custom-button.next");
const carouselInner = document.querySelector(".carousel-custom-inner");
const carouselItems = document.querySelectorAll(".carousel-custom-item");
const paginationContainer = document.querySelector(".pagination");
const totalItems = document.getElementById("countCate").value;
const itemWidth = carouselItems[0].offsetWidth;
const visibleItems = 3;
let currentIndex = 0;

// Create pagination dots
function createPaginationDots() {
    paginationContainer.innerHTML = "";
    for (let i = 0; i < totalItems; i++) {
        const dot = document.createElement("span");
        dot.className = "pagination-dot";
        dot.addEventListener("click", () => {
            currentIndex = i;
            updateCarousel();
            updatePagination();
        });
        paginationContainer.appendChild(dot);
    }
}

// Function to update carousel position
function updateCarousel() {
    const offset = -currentIndex * itemWidth;
    carouselInner.style.transform = `translateX(${offset}px)`;
}

// Function to update pagination dots
function updatePagination() {
    const dots = document.querySelectorAll(".pagination-dot");
    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentIndex);
    });
}

// Function to go to the next item
function goToNext() {
    currentIndex++;
    if (currentIndex == totalItems) {
        carouselInner.style.transition = "none";
        currentIndex = 0;
        const offset = currentIndex * itemWidth;
        carouselInner.style.transform = `translateX(${offset}px)`;
        carouselInner.style.transition = "transform 0.5s ease";
    } else {
        updateCarousel();
    }
    updatePagination();
}

function goToPrev() {
    if (currentIndex === 0) {
        carouselInner.style.transition = "none";
        currentIndex = totalItems - 1;
        updateCarousel();
        setTimeout(() => {
            carouselInner.style.transition = "transform 0.5s ease";
        }, 0);
    } else {
        currentIndex--;
        updateCarousel();
    }
    updatePagination();
}

prevButton.addEventListener("click", goToPrev);
nextButton.addEventListener("click", goToNext);

// Initialize pagination
createPaginationDots();
updatePagination();

// Optional: Auto-slide functionality
setInterval(goToNext, 3000);
