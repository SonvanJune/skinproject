const tempProductDiv = document.getElementById("product-table").innerHTML;

function searchProductApi() {
    const productSearchDiv = document.getElementById("product-search");
    const productDiv = document.getElementById("product-table");
    const notFoundDiv = document.getElementById("not-found");
    const formPage = document.getElementById("form-page");
    const productPagiDiv = document.getElementById("pagination-product");
    const searchBox = document.getElementById("searchBox");
    const routeSearch = searchBox.getAttribute("data-route-search");
    const csrfSearch = searchBox.getAttribute("data-csrf-search");
    const searchInput = document.getElementById("search-input");
    const searchTerm = searchInput.value;
    const url = routeSearch + "?s=" + searchTerm;

    const resultCountBox = document.getElementById("searchResultCount");
    const resultCountNumber = document.getElementById("result-count-number");

    if (searchTerm.trim() === "") {
        productSearchDiv.style.display = "none";
        productDiv.innerHTML = tempProductDiv;
        productPagiDiv.style.display = "block";
        notFoundDiv.innerHTML = "";
        formPage.style.display = "flex";
        resultCountBox.style.display = "none";
        resultCountNumber.textContent = 0;
    } else {
        productDiv.innerHTML = "";
        productSearchDiv.style.display = "flex";
        productPagiDiv.style.display = "none";
        formPage.style.display = "none";

        fetch(url, {
            method: "Get",
            headers: {
                "X-CSRF-TOKEN": csrfSearch,
            },
        })
            .then((r) => r.json())
            .then((data) => {
                resultCountNumber.textContent = data.length;
                if (data.length > 0) {
                    notFoundDiv.innerHTML = "";
                    resultCountBox.style.display = "inline-block";
                    importProducts(data, searchTerm);
                } else {
                    notFoundDiv.innerHTML = `
                        <i class="bi bi-folder-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No matching products found.</p>`;
                    resultCountBox.style.display = "none";
                }
            })
            .catch((e) => console.log(e));
    }
}

function importProducts(products, searchTerm) {
    const searchBox = document.getElementById("searchBox");
    const csrfToken = searchBox.getAttribute("data-csrf-search");
    const routeEditTemplate = searchBox.dataset.routeEdit;
    const routeDeleteTemplate = searchBox.dataset.routeDelete;
    const productTable = document.getElementById("product-table");
    let html = "";

    products.forEach((product, index) => {
        const deleteUrl = routeDeleteTemplate.replace(
            ":slug",
            product.product_slug,
        );
        const urlEdit = routeEditTemplate.replace(
            ":slug",
            product.product_slug,
        );
        const nameHighlighted = highlightMatch(
            product.product_name,
            searchTerm.trim(),
        );
        const link = routeGetFileTemplate.replace(":filename", product.post_image_path);

        html += `
                <tr role="row" class="odd">
                    <td>
                        <button type="button" class="btn btn-primary py-1 px-2" disabled>
                            ${index + 1}
                        </button>
                    </td>
                    <td>
                        <a class="btn btn-white btn-sm" href="${urlEdit}">
                            <i class="bi-pencil-fill me-1"></i>
                        </a>
                        <form action="${deleteUrl}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <a class="btn btn-white btn-sm" href="#" onclick="event.preventDefault(); confirmDelete(this);">
                                <i class="bi-trash dropdown-item-icon"></i>
                            </a>
                        </form>
                        <button class="btn btn-white btn-sm view-product-btn"
                            data-product='${JSON.stringify(product)}'
                            onclick="openModalViewProduct(JSON.parse(this.dataset.product))">
                            <i class="bi-eye-fill me-1"></i>
                        </button>
                    </td>
                    <td class="c0">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0">
                                <img class="avatar avatar-lg" src="${link}" alt="${product.post_image_alt}" onerror="this.onerror=null; this.src='${link}images/avatars/default_avatar.jpg'">
                            </div>
                        </a>
                    </td>
                    <td class="c1">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0 text-start">
                                <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                    ${nameHighlighted || "NO TITLE"}
                                </p>
                            </div>
                        </a>
                    </td>
                    <td class="c2">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0 text-start">
                                <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                    ${product.product_slug || "NO TITLE"}
                                </p>
                            </div>
                        </a>
                    </td>
                    <td class="c3">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0 text-start">
                                <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                    ${product.product_file_path || "NO TITLE"}
                                </p>
                            </div>
                        </a>
                    </td>
                    <td class="c4">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0 text-start">
                                <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                    ${product.post_release || "NO TITLE"}
                                </p>
                            </div>
                        </a>
                    </td>
                    <td class="c5">
                        <a class="d-flex align-items-center justify-content-center">
                            ${
                                product.post_status == 1
                                    ? '<span class="badge badge-success">Active</span>'
                                    : '<span class="badge badge-primary">Disable</span>'
                            }
                        </a>
                    </td>
                    <td class="c6">
                        <a class="d-flex align-items-center justify-content-center">
                            <div class="flex-shrink-0 text-start">
                                <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                                    ${product.updated_at || "NO TITLE"}
                                </p>
                            </div>
                        </a>
                    </td>
                </tr>
            `;
    });

    productTable.innerHTML = html;
}
