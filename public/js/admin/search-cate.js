const toggleBtn = document.getElementById("toggleSearch");
const searchBox = document.getElementById("searchBox");
const searchTitle = document.getElementById("titleSearch");
const icon = toggleBtn.querySelector("i");

toggleBtn.addEventListener("click", () => {
    searchBox.classList.toggle("show");
    toggleBtn.classList.toggle("active");

    if (searchBox.classList.contains("show")) {
        icon.classList.remove("bi-search");
        searchTitle.style.display = "none";
        icon.classList.add("bi-x");
        icon.classList.remove("me-2");
    } else {
        icon.classList.remove("bi-x");
        icon.classList.add("bi-search");
        searchTitle.style.display = "inline-block";
        icon.classList.add("me-2");
    }
});

function searchCategoryApi() {
    const searchBox = document.getElementById("searchBox");
    const routeSearch = searchBox.getAttribute("data-route-search");
    const categorySearchDiv = document.getElementById("category-search");
    const categoryTreeDiv = document.getElementById("category-tree");
    const csrfSearch = searchBox.getAttribute("data-csrf-search");
    const searchInput = document.getElementById("search-input");
    const searchTerm = searchInput.value;
    const url = routeSearch + "?s=" + searchTerm;

    const resultCountBox = document.getElementById("searchResultCount");
    const resultCountNumber = document.getElementById("result-count-number");

    if (searchTerm.trim() === "") {
        categorySearchDiv.style.display = "none";
        categoryTreeDiv.style.display = "block";
        resultCountBox.style.display = "none";
        resultCountNumber.textContent = 0;
    } else {
        categoryTreeDiv.style.display = "none";
        categorySearchDiv.style.display = "block";

        fetch(url, {
            method: "Get",
            headers: {
                "X-CSRF-TOKEN": csrfSearch,
            },
        })
            .then((r) => r.json())
            .then((data) => {
                const ul = document.createElement("ul");
                resultCountNumber.textContent = data.length;
                if (data.length > 0) {
                    categorySearchDiv.innerHTML = "";
                    resultCountBox.style.display = "inline-block";
                    data.forEach((category) => {
                        const li = document.createElement("li");
                        li.classList.add("category-item");

                        const div = document.createElement("div");
                        const nameHighlighted = highlightMatch(
                            category.name,
                            searchTerm.trim(),
                        );

                        if (category.children && category.children.length > 0) {
                            div.innerHTML = `
                                       <i class="bi bi-bookmark-fill toggle-icon"></i>
                                       <span>${nameHighlighted}</span>
                                       <span class="ms-2 text-muted">
                                       <i class="fa-solid fa-box toggle-icon"></i> ${category.product_count}
                                       </span>
                                       <span class="ms-2 text-muted">
                                        <i class="bi bi-list-ul toggle-icon"></i> ${category.children_category_count}
                                       </span>
                                    `;
                        } else {
                            div.innerHTML = `
                                        <i class="bi-bookmark-fill toggle-icon"></i>
                                        <span>${nameHighlighted}</span>
                                        <span class="ms-2 text-muted">
                                        <i class="fa-solid fa-box toggle-icon"></i> ${category.product_count}
                                        </span>
                                    `;
                        }
                        li.appendChild(div);
                        ul.appendChild(li);

                        const routeWrapper =
                            document.getElementById("routeWrapper");
                        const categorySlug = category.slug;
                        const routeEditTemplate =
                            routeWrapper.dataset.routeEdit;
                        const routeCreateTemplate =
                            routeWrapper.dataset.routeCreate;
                        const routeDeleteTemplate =
                            routeWrapper.dataset.routeDelete;

                        li.addEventListener("contextmenu", function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            const deleteUrl = routeDeleteTemplate.replace(
                                ":slug",
                                categorySlug,
                            );

                            const existingMenu =
                                document.querySelector("#menu-files-box");
                            if (existingMenu) {
                                existingMenu.remove();
                            }

                            const menuBox = document.createElement("div");
                            menuBox.className = "menu-box";
                            menuBox.id = "menu-files-box";
                            menuBox.innerHTML = `
                            <ul>
                                <li id="create-category" class="menu-item">
                                    
                                    <div class="item-icon">
                                    <i class="bi bi-plus-square"></i>
                                    </div>
                                    <div class="item-text">
                                        Add
                                    </div>
                                </li>
                                <li id="edit-category" class="menu-item">
                                    
                                    <div class="item-icon">
                                    <i class="bi-pencil-fill"></i>
                                    </div>
                                    <div class="item-text">
                                        Edit
                                    </div>
                                </li>
                                <li id="delete-category" class="menu-item">
                                    <div class="item-icon">
                                    <i class="bi-trash"></i>
                                    </div>
                                    <div class="item-text">
                                    Delete
                                    </div>
                                    <form id="delete-category-form" action="${deleteUrl}" method="POST" style="display: none;">
                                        <input type="hidden" name="_token" value="${csrfSearch}">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </li>
                            </ul>
                        `;

                            const x = event.clientX;
                            const y = event.clientY;

                            menuBox.style.position = "absolute";
                            menuBox.style.top = `${y}px`;
                            menuBox.style.left = `${x}px`;

                            document.body.appendChild(menuBox);

                            menuBox.addEventListener("click", function (event) {
                                event.stopPropagation();
                            });

                            menuBox
                                .querySelectorAll(".menu-item")
                                .forEach((item) => {
                                    item.addEventListener("click", function () {
                                        switch (item.id) {
                                            case "create-category":
                                                const urlCreate =
                                                    routeCreateTemplate.replace(
                                                        ":slug",
                                                        categorySlug,
                                                    );
                                                window.location.href =
                                                    urlCreate;
                                                break;
                                            case "edit-category":
                                                const urlEdit =
                                                    routeEditTemplate.replace(
                                                        ":slug",
                                                        categorySlug,
                                                    );
                                                window.location.href = urlEdit;
                                                break;
                                            case "delete-category":
                                                const form =
                                                    document.getElementById(
                                                        "delete-category-form",
                                                    );

                                                const modal =
                                                    new bootstrap.Modal(
                                                        document.getElementById(
                                                            "deleteConfirmationModal",
                                                        ),
                                                    );
                                                modal.show();

                                                document.getElementById(
                                                    "confirmDeleteBtn",
                                                ).onclick = function () {
                                                    form.submit();
                                                    modal.hide();
                                                };
                                                break;
                                            default:
                                                break;
                                        }
                                    });
                                });

                            document.addEventListener("click", () => {
                                menuBox.remove();
                            });
                        });
                    });
                    categorySearchDiv.appendChild(ul);
                } else {
                    categorySearchDiv.innerHTML = `
                     <div class="text-center text-muted py-3 fade-in">
                      <i class="bi bi-folder-x" style="font-size: 2rem;"></i>
                      <p class="mt-2 mb-0">No matching categories found.</p>
                     </div>
                    `;
                    resultCountBox.style.display = "none";
                }
            })
            .catch((e) => console.log);
    }
}
