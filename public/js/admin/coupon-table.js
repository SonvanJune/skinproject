//declare variables for columns 
let activeColumns = [true, true, true, true, true, true, true, true];
const columns = ['Name', 'Code', 'Price', 'Per Hundred', 'Product', 'Release date', 'Expired date'];

//elements
const columnInTableFilterContainer = document.querySelector("#column-in-tabel-filter-container");
const columnFilterContainer = document.querySelector("#column-filter-container");
const tableContent = document.querySelector("tbody");

//key to save into localStorage
const LOCAL_KEY = "SKIN_PROJECT_SUBADMIN_TABLE_COLUMN_FILTER";

//call first set-up
getFromLocalColumnFilter();
getColumnsFilter();
getColumnsInTableFilter();
getTableContent();

// Function to retrieve the column filter settings from localStorage
// If no settings are found, the default activeColumns array is used
function getFromLocalColumnFilter() {
    const columnsFilter = JSON.parse(localStorage.getItem(LOCAL_KEY)) || activeColumns;
    activeColumns = columnsFilter;
}

// Function to save the current column filter settings to localStorage
function setToLocalColumnFilter() {
    localStorage.setItem(LOCAL_KEY, JSON.stringify(activeColumns));
}

// Function to generate column filter checkboxes dynamically in the UI
function getColumnsFilter() {
    // Add an "Actions" column and "Index" column to the filter UI
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
        // Add the generated checkbox HTML to the container
        columnFilterContainer.innerHTML += columnFilter;
    }
}

// Function to generate table header columns based on the active filter settings
function getColumnsInTableFilter() {
    // Add an "Actions" column and "Index" column to the table header
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
            // Only add columns that are marked as active
            columnInTableFilterContainer.innerHTML += columnFilter;
        }
    }
}

// Function to control the visibility of table content columns based on active filters
function getTableContent() {
    for (let i = 0; i < columns.length; i++) {
        document.querySelectorAll('td.c' + i)?.forEach(item => {
            // Show or hide table cells based on the activeColumns setting
            item.style.display = activeColumns[i] ? '' : 'none';
        });
    }
}

// Function called when a column visibility toggle is changed
// Updates the activeColumns array, UI, and saves to localStorage
function checkColumn(isChecked, index) {
    activeColumns[index] = isChecked; // Update the active state of the column
    getColumnsInTableFilter(); // Refresh the table header
    getTableContent(); // Refresh the table content
    setToLocalColumnFilter(); // Save the updated state to localStorage
}