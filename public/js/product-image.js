const thumbnails = document.querySelectorAll('.thumbnail');

const mainImage = document.getElementById('mainImage');

thumbnails.forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
        mainImage.src = thumbnail.getAttribute('data-src');
    });
});

document.addEventListener("DOMContentLoaded", function() {
    let currentIndex = 0;
    const images = window.productImages;
    const mainImage = document.getElementById('mainImage');

    function changeImage() {
        if (currentIndex >= images.length) {
            currentIndex = 0;
        }

        const newImage = images[currentIndex];
        mainImage.src = newImage.product_image_path;
        mainImage.alt = newImage.product_image_alt;
        currentIndex++;
    }

    setInterval(changeImage, 6000);

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            mainImage.src = thumbnail.dataset.src;
            mainImage.alt = thumbnail.alt;
            currentIndex = index +
            1;
        });
    });
});