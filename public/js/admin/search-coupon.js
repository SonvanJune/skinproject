const tempProductDiv = document.getElementById("coupon-table").innerHTML;

function searchCouponApi() {
    const couponSearchDiv = document.getElementById("coupon-search");
    const couponDiv = document.getElementById("coupon-table");
    const notFoundDiv = document.getElementById("not-found");
    const formPage = document.getElementById("form-page");
    const couponPagiDiv = document.getElementById("pagination-coupon");
    const searchBox = document.getElementById("searchBox");
    const routeSearch = searchBox.getAttribute("data-route-search");
    const csrfSearch = searchBox.getAttribute("data-csrf-search");
    const searchInput = document.getElementById("search-input");
    const searchTerm = searchInput.value;
    const url = routeSearch + "?s=" + searchTerm;

    const resultCountBox = document.getElementById("searchResultCount");
    const resultCountNumber = document.getElementById("result-count-number");

    if (searchTerm.trim() === "") {
        couponSearchDiv.style.display = "none";
        couponDiv.innerHTML = tempProductDiv;
        couponPagiDiv.style.display = "block";
        notFoundDiv.innerHTML = "";
        formPage.style.display = "flex";
        resultCountBox.style.display = "none";
        resultCountNumber.textContent = 0;
    } else {
        couponDiv.innerHTML = "";
        couponSearchDiv.style.display = "flex";
        couponPagiDiv.style.display = "none";
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
                    importcoupons(data, searchTerm);
                } else {
                    notFoundDiv.innerHTML = `
                        <i class="bi bi-folder-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No matching coupons found.</p>`;
                    resultCountBox.style.display = "none";
                }
            })
            .catch((e) => console.log(e));
    }
}

function importcoupons(coupons, searchTerm) {
    const searchBox = document.getElementById("searchBox");
    const csrfToken = searchBox.getAttribute("data-csrf-search");
    const routeEditTemplate = searchBox.dataset.routeEdit;
    const routeDeleteTemplate = searchBox.dataset.routeDelete;
    const couponTable = document.getElementById("coupon-table");
    let html = "";

    coupons.forEach((coupon, index) => {
        const deleteUrl = routeDeleteTemplate;
        const urlEdit = routeEditTemplate.replace(":id", coupon.coupon_id);
        const nameHighlighted = coupon.coupon_name
            ? highlightMatch(coupon.coupon_name, searchTerm.trim())
            : "##";
        const codeHighlighted = coupon.coupon_code
            ? highlightMatch(coupon.coupon_code, searchTerm.trim())
            : "##";
        html += `
        <tr role="row" class="odd">
         <td>
           <button type="button" class="btn btn-primary py-1 px-2" disabled>
            ${index + 1}
           </button>
         </td>

         <td>
         ${
             new Date(coupon.coupon_expired) < new Date()
                 ? '<button type="button" class="btn btn-outline-primary py-1 px-2" disabled> NO ACTIONS </button>'
                 : `
                <a class="btn btn-white btn-sm"
                    href="${urlEdit}">
                    <i class="bi-pencil-fill me-1"></i>
                </a>

                <form action="${deleteUrl}" method="POST" style="display:inline;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                    <a class="btn btn-white btn-sm" href="#" onclick="event.preventDefault(); confirmDelete(this);">
                        <i class="bi-trash dropdown-item-icon"></i>
                    </a>
                    <input type="hidden" name="coupon_id" value="${coupon.coupon_id}">
                </form>
            `
         }
        </td>
        <td class="c0">${nameHighlighted}</td>
        <td class="c1">${codeHighlighted}</td>
        <td class="c2">${coupon.coupon_price ?? "##"}</td>
        <td class="c3">${coupon.coupon_per_hundred ?? "##"}</td>
        <td class="c4">${coupon.product ? coupon.product.product_name : "##"}</td>
        <td class="c5">${coupon.coupon_release}</td>
        <td class="c6 ${new Date(coupon.coupon_expired) < new Date() ? "expired" : ""}">
         ${coupon.coupon_expired}
        </td>
       </tr>
    `;
    });

    couponTable.innerHTML = html;
}
