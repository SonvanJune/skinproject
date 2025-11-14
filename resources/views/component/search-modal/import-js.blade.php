<script>
    function searchApi() {
        const routeSearch = document.body.getAttribute("data-route-search");
        const searchModal = document.getElementById("search-modal");
        const csrfSearch = document.body.getAttribute("data-csrf-translate");
        const searchInput = document.getElementById("searchInput");
        const searchTerm = searchInput.value;
        const url = routeSearch + "?s=" + searchTerm;
        const contentDiv = document.getElementById("search-modal-content");
        const searchSpinner = document.getElementById("searchSpinner");

        if (searchTerm.trim() === "") {
            contentDiv.innerHTML = '';
            searchModal.style.display = "none";
            searchSpinner.style.display = "none";
        } else {
            searchModal.style.display = "flex";
            searchSpinner.style.display = "block";
            contentDiv.innerHTML = '';
            fetch(url, {
                    method: "Get",
                    headers: {
                        "X-CSRF-TOKEN": csrfSearch,
                    },
                })
                .then((r) => r.json())
                .then((data) => {
                    searchSpinner.style.display = "none";
                    if (data.products.length === 0 && data.categories.length === 0 && data.brands.length === 0 &&
                        data.posts.length === 0) {
                        contentDiv.innerHTML = `
                    <div class="empty-list">
                    <img src="{{ asset('images/empty-box-2.jpg') }}" alt="" srcset="">
                    <div class="text-center empty-text">{{ __('message.listIsEmpty') }}</div>
                    </div>
                    `;
                    } else {
                        contentDiv.innerHTML += generateListProductHTML(
                            data.products,
                            '{{ __('message.listProduct') }}'
                        );
                        contentDiv.innerHTML += generateListCategoryHTML(
                            data.categories,
                            '{{ __('message.categories') }}'
                        );
                        contentDiv.innerHTML += generateListCategoryHTML(
                            data.brands,
                            '{{ __('message.brand') }}'
                        );
                        contentDiv.innerHTML += generateListPostHTML(data.posts, '{{ __('message.blog') }}');
                    }

                })
                .catch((e) => console.log);
        }
    }

    function generateListProductHTML(items, title) {
        const baseUrl = "{{ url(App::getLocale()) }}"
        if (!items || items.length === 0)
            return ``;
        return `
    <div>
    <h6>${title} (${items.length})</h6>
    <div class="item-list">
        ${items.map((item) => `
            <a class="item" href="${baseUrl}/product/${item.product_slug}">
                <img src="${routeGetFileTemplate.replace(":filename", item.post_image_path)}" alt="${item.post_image_alt}" class="item-image">
                <p class="item-name"><span class="highlight">${item.product_name.charAt(0)}</span>${item.product_name.slice(1)}</p>
            </a>
        `).join("")}
    </div>
    </div>
    `;
    }

    function generateListCategoryHTML(items, title) {
        const baseUrl = "{{ url(App::getLocale()) }}"

        if (!items || items.length === 0)
            return ``;

        return `
    <div>
    <h6>${title} (${items.length})</h6>
    <div class="item-list">
        ${items.map((item) => {
    if (item.type === 1) {
        return `
            <a class="item" href="${baseUrl}/product-category/${item.slug}">
                <img src="${routeGetFileTemplate.replace(":filename", item.image_path)}" alt="${item.image_alt}" class="item-image">
                <p class="item-name"><span class="highlight">${item.name.charAt(0)}</span>${item.name.slice(1)}</p>
            </a>
        `;
    } else {
        return `
            <a class="item" href="${baseUrl}/brand/${item.slug}">
                <img src="${routeGetFileTemplate.replace(":filename", item.image_path)}" alt="${item.image_alt}" class="item-image">
                <p class="item-name"><span class="highlight">${item.name.charAt(0)}</span>${item.name.slice(1)}</p>
            </a>
        `;
    }
    }).join("")}
    </div>
    </div>
    `;
    }

    function generateListPostHTML(items, title) {
        const baseUrl = "{{ url(App::getLocale()) }}"

        if (!items || items.length === 0)
            return ``;
        return `
    <div>
    <h6>${title} (${items.length})</h6>
    <div class="item-list">
        ${items.map((item) => `
            <a class="item" href="${baseUrl}/blog/${item.slug}">
                <img src="${routeGetFileTemplate.replace(":filename", item.image_path)}" alt="${item.image_alt}" class="item-image">
                <p class="item-name">${item.name}</p>
            </a>
        `).join("")}
    </div>
    </div>
    `;
    }

    function closeSearch() {
        const searchModal = document.getElementById("search-modal");
        searchModal.style.display = "none";
        const searchInput = document.getElementById("searchInput").value = '';
    }

    function goToSearchPage() {
        const searchInput = document.getElementById("searchInput");
        const searchTerm = searchInput.value;
        const url = "{{ route('search.page') }}?s=" + searchTerm;
        window.location.href = url;
    }
</script>
