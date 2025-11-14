function truncateTitleByLength() {
    const maxLength = 30;
    const cardTitles = document.querySelectorAll(".card-title");

    cardTitles.forEach(cardTitle => {
        if (cardTitle.textContent.trim().length > maxLength) {
            cardTitle.textContent = cardTitle.textContent.trim().substring(maxLength, length) + "...";
        }
    });
}

window.onload = truncateTitleByLength;

function toggleVisibility() {
    const filterProducts = document.getElementById("filter-products");
    const filterCategories = document.getElementById("filter-categories");
    const filterBrands = document.getElementById("filter-brands");
    const filterPosts = document.getElementById("filter-posts");
    const productItems = document.querySelectorAll(".product-item");
    const categoryItems = document.querySelectorAll(".category-item");
    const brandItems = document.querySelectorAll(".brand-item");
    const postItems = document.querySelectorAll(".post-item");

    productItems.forEach(item => {
        item.style.display = filterProducts.checked ? "block" : "none";
    });

    categoryItems.forEach(item => {
        item.style.display = filterCategories.checked ? "block" : "none";
    });

    brandItems.forEach(item => {
        item.style.display = filterBrands.checked ? "block" : "none";
    });

    postItems.forEach(item => {
        item.style.display = filterPosts.checked ? "block" : "none";
    });
    updateFilteredBadges();
}

function updateFilteredBadges() {
    const filterProducts = document.getElementById("filter-products");
    const filterCategories = document.getElementById("filter-categories");
    const filterBrands = document.getElementById("filter-brands");
    const filterPosts = document.getElementById("filter-posts");
    const filteredBadges = document.getElementById("filtered-badges");
    filteredBadges.innerHTML = '';    

    if (filterProducts.checked) {
        addBadge(productTitle, 'filter-products');
    }
    if (filterCategories.checked) {
        addBadge(categoryTitle, 'filter-categories');
    }
    if (filterBrands.checked) {
        addBadge(brandTitle, 'filter-brands');
    }
    if (filterPosts.checked) {
        addBadge(postTitle, 'filter-posts');
    }
}

function addBadge(label, filterId) {
    const filteredBadges = document.getElementById("filtered-badges");
    const badge = document.createElement("span");
    badge.classList.add("filter-badge");
    badge.innerHTML = `${label} <button onclick="removeBadge('${filterId}')">&times;</button>`;
    filteredBadges.appendChild(badge);
}

window.removeBadge = function(filterId) {
    const checkbox = document.getElementById(filterId);
    if (checkbox) {
        checkbox.checked = false;
        updateFilteredBadges();
        toggleVisibility();
    }
};
updateFilteredBadges();