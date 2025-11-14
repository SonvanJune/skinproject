let activeColumns = [true, true, true, true, true];
const columns = [
    "Post Image",
    "Post Slug",
    "Post Title",
    "Post Release Time",
    "Author",
    "Post Status",
    "Last Updated",
];

const columnInTableFilterContainer = document.querySelector(
    "#column-in-tabel-filter-container"
);
const columnFilterContainer = document.querySelector(
    "#column-filter-container"
);
const tableContent = document.querySelector("tbody");

const LOCAL_KEY = "SKIN_PROJECT_SUBADMIN_TABLE_COLUMN_FILTER";

getFromLocalColumnFilter();
getColumnsFilter();
getColumnsInTableFilter();
getTableContent();
document.getElementById("count-column").textContent = columns.length;

function getFromLocalColumnFilter() {
    const columnsFilter =
        JSON.parse(localStorage.getItem(LOCAL_KEY)) || activeColumns;
    activeColumns = columnsFilter;
}

function setToLocalColumnFilter() {
    localStorage.setItem(LOCAL_KEY, JSON.stringify(activeColumns));
}

function getColumnsFilter() {
    columnFilterContainer.innerHTML = `
                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="No."
                    style="width: 115px;">No.</th> <br></br>

                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                style="width: 115px;">Actions</th>
                `;

    for (let i = 0; i < columns.length; i++) {
        const columnFilter = `
                    <label class="row form-check form-switch" for="toggleColumn_user">
                        <span class="col-8 col-sm-9 ms-0">
                            <span class="me-2">${columns[i]}</span>
                        </span>
                        <span class="col-4 col-sm-3 text-end">
                            <input type="checkbox" class="form-check-input" 
                                id="toggleColumn_user"
                                ${activeColumns[i] ? "checked=''" : ""} 
                                onchange="checkColumn(this.checked, ${i})">
                        </span>
                    </label>
                    `;
        columnFilterContainer.innerHTML += columnFilter;
    }
}

function getColumnsInTableFilter() {
    columnInTableFilterContainer.innerHTML = `
                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="No."
                    style="width: 115px;">No.</th>

                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                style="width: 115px;">Actions</th>
                `;

    for (let i = 0; i < columns.length; i++) {
        const columnFilter = `
                    <th class="sorting" rowspan="1" colspan="1"
                        aria-label='${columns[i]}: activate to sort column ascending'
                        style="width: 85px;">${columns[i]}</th>
                    `;

        if (activeColumns[i]) {
            columnInTableFilterContainer.innerHTML += columnFilter;
        }
    }
}

function getTableContent() {
    for (let i = 0; i < columns.length; i++) {
        document.querySelectorAll("td.c" + i)?.forEach((item) => {
            item.style.display = activeColumns[i] ? "" : "none";
        });
    }
}

function checkColumn(isChecked, index) {
    activeColumns[index] = isChecked;
    getColumnsInTableFilter();
    getTableContent();
    setToLocalColumnFilter();
}

const modals = document.querySelectorAll(".modal");
const postImage = document.querySelector("#view-post-modal .card-img-top");
const postTitle = document.querySelector("#view-post-modal .card-title");
const postContent = document.querySelector("#view-post-modal .card-text");
const postReleaseDate = document.querySelector(
    "#view-post-modal #publish-date"
);
const postAuthor = document.querySelector("#view-post-modal #author-name");

modals.forEach((modal) => {
    modal.addEventListener("hidden.bs.modal", function (event) {
        document.title = "Admin-Roles";
    });
});

function openView(title, imagePath, imageAlt, content, releaseDate, author) {
    postImage.src = imagePath;
    postImage.alt = imageAlt;
    postTitle.textContent = title;
    postContent.innerHTML = content;
    postReleaseDate.textContent = releaseDate;
    postAuthor.textContent = author;
}

function confirmDelete(element) {
    const form = element.closest("form");

    const modal = new bootstrap.Modal(
        document.getElementById("deleteConfirmationModal")
    );
    modal.show();

    document.getElementById("confirmDeleteBtn").onclick = function () {
        form.submit();
        modal.hide();
    };
}

