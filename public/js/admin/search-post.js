const tempProductDiv = document.getElementById("post-table").innerHTML;

function searchPostApi() {
    const postSearchDiv = document.getElementById("post-search");
    const postDiv = document.getElementById("post-table");
    const notFoundDiv = document.getElementById("not-found");
    const formPage = document.getElementById("form-page");
    const postPagiDiv = document.getElementById("pagination-post");
    const searchBox = document.getElementById("searchBox");
    const routeSearch = searchBox.getAttribute("data-route-search");
    const csrfSearch = searchBox.getAttribute("data-csrf-search");
    const searchInput = document.getElementById("search-input");
    const searchTerm = searchInput.value;
    const url = routeSearch + "?s=" + searchTerm;

    const resultCountBox = document.getElementById("searchResultCount");
    const resultCountNumber = document.getElementById("result-count-number");

    if (searchTerm.trim() === "") {
        postSearchDiv.style.display = "none";
        postDiv.innerHTML = tempProductDiv;
        postPagiDiv.style.display = "block";
        notFoundDiv.innerHTML = "";
        formPage.style.display = "flex";
        resultCountBox.style.display = "none";
        resultCountNumber.textContent = 0;
    } else {
        postDiv.innerHTML = "";
        postSearchDiv.style.display = "flex";
        postPagiDiv.style.display = "none";
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
                    importPosts(data, searchTerm);
                } else {
                    notFoundDiv.innerHTML = `
                        <i class="bi bi-folder-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No matching posts found.</p>`;
                    resultCountBox.style.display = "none";
                }
            })
            .catch((e) => console.log(e));
    }
}

function importPosts(posts, searchTerm) {
    const searchBox = document.getElementById("searchBox");
    const csrfToken = searchBox.getAttribute("data-csrf-search");
    const routeEditTemplate = searchBox.dataset.routeEdit;
    const routeDeleteTemplate = searchBox.dataset.routeDelete;
    const postTable = document.getElementById("post-table");
    let html = "";

    posts.forEach((post, index) => {
        const deleteUrl = routeDeleteTemplate.replace(":slug", post.slug);
        const urlEdit = routeEditTemplate.replace(":slug", post.slug);
        const nameHighlighted = highlightMatch(post.name, searchTerm.trim());
        const link = routeGetFileTemplate.replace(":filename", post.image_path);
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
        
                <a class="btn btn-white btn-sm"
                    onclick="openView(this.dataset.name, this.dataset.image_path, this.dataset.image_alt, this.dataset.content, this.dataset.release, this.dataset.author)"
                    href="#" data-bs-toggle="modal" data-bs-target="#view-post-modal"
                    data-name="${post.name}"
                    data-image_path="${link}"
                    data-image_alt="${post.image_alt}"
                    data-content="${post.content}"
                    data-release="${post.release}"
                    data-author="${post.author}">
                    <i class="bi bi-eye-fill me-1"></i>
                </a>
            </td>
        
            <td class="c0">
                <a class="d-flex align-items-center justify-content-center" href="${link}">
                    <div class="flex-shrink-0">
                        <img class="avatar avatar-lg" width="100%" src="${link}" alt="${post.image_alt}" onerror="this.onerror=null; this.src='${link}images/avatars/default_avatar.jpg'">
                    </div>
                </a>
            </td>
        
            <td class="c1">
                <a class="d-flex align-items-center justify-content-center">
                    <div class="flex-shrink-0 text-start">
                        <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                            ${post.slug || "NO TITLE"}
                        </p>
                    </div>
                </a>
            </td>
        
            <td class="c2">
                <a class="d-flex align-items-center justify-content-center">
                    <div class="flex-shrink-0 text-start">
                        <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                            ${nameHighlighted || "NO TITLE"}
                        </p>
                    </div>
                </a>
            </td>
        
            <td class="c3">
                <a class="d-flex align-items-center justify-content-center">
                    <div class="flex-shrink-0 text-start">
                        <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                            ${post.release}
                        </p>
                    </div>
                </a>
            </td>
        
            <td class="c4">
                ${
                    post.author
                        ? `<p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                        ${post.author}
                       </p>`
                        : `<button type="button" class="btn btn-outline-primary py-1 px-2" disabled>SYSTEM POST</button>`
                }
            </td>
        
            <td class="c5">
                <a class="d-flex align-items-center justify-content-center">
                    ${
                        post.status == 1
                            ? '<span class="badge badge-success">Active</span>'
                            : '<span class="badge badge-primary">Disable</span>'
                    }
                </a>
            </td>
        
            <td class="c6">
                <a class="d-flex align-items-center justify-content-center">
                    <div class="flex-shrink-0 text-start">
                        <p style="word-wrap: break-word; overflow-wrap:break-word; white-space: normal;">
                            ${post.updated_at || "NO TITLE"}
                        </p>
                    </div>
                </a>
            </td>
        </tr>
        `;
    });

    postTable.innerHTML = html;
}
