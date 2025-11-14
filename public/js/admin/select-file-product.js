if (document.getElementById("openFolderPathModal")) {
    document
        .getElementById("openFolderPathModal")
        .addEventListener("click", () => {
            renderFolderModal("folderPathModal");
        });
}

document.getElementById("openImagesPathModal").addEventListener("click", () => {
    sessionStorage.removeItem("filesSection");
    renderModal("selectImagesModal");
});

document.getElementById("openImagePathModal").addEventListener("click", () => {
    sessionStorage.removeItem("filesSection");
    renderModal("selectImageModal");
});

let selectedFiles = [];
let selectedFile = null;

// Render files

const renderFiles = () => {
    const attachFilesContainer = document.getElementById("attachFiles");
    attachFilesContainer.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const item = document.createElement("div");
        item.classList.add("col-12", "m-1", "dz-preview", "dz-file-preview");
        const url = routeGetFileTemplate.replace(":filename", file.filePath);
        const fileItem = `
    <div class="d-flex justify-content-end dz-close-icon">
       <small class="bi-x" data-index="${index}"></small>
    </div>
    <div class="dz-details d-flex flex-column align-items-center">
        <div class="dz-img flex-shrink-0">
            <img class="img-fluid dz-img-inner" alt="${file.name}" src="${url}">
        </div>
        <div class="dz-file-wrapper flex-grow-1 mt-2">
            <h6 class="dz-filename">
                <span class="dz-title" data-dz-name="">${file.name}</span>
            </h6>
            <div class="dz-size" data-dz-size=""><strong>${file.size}</strong></div>
        </div>
    </div>
    <div>

        <input type="text" class="form-control text-center" id="imageAltLabel_${index}"
            placeholder="Enter image alt..." value="${file.name}"
            required>
        <div class="invalid-feedback"> Please enter image alt.</div>
    </div>`;

        item.innerHTML = fileItem;

        attachFilesContainer.appendChild(item);

        const closeIcon = item.querySelector(".bi-x");
        closeIcon.addEventListener("click", function () {
            // Get the index from the data attribute
            const index = parseInt(this.getAttribute("data-index"));

            // Remove the item from the selectedFiles array
            selectedFiles.splice(index, 1);

            // Update the sessionStorage with the new array
            sessionStorage.setItem(
                "filesSection",
                JSON.stringify(selectedFiles),
            );

            // Re-render the files after deletion
            renderFiles();
        });
    });
};

const renderFile = () => {
    const attachFileContainer = document.getElementById("attachFile");
    attachFileContainer.innerHTML = "";

    if (selectedFile) {
        const url = routeGetFileTemplate.replace(":filename", selectedFile.filePath);
        const path = selectedFile.filePath;

        const item = document.createElement("div");
        item.classList.add("col-12", "m-1", "dz-preview", "dz-file-preview");

        const fileItem = `
    <div class="d-flex justify-content-end dz-close-icon">
       <small class="bi-x delete-selected-file"></small>
    </div>
    <div class="dz-details d-flex flex-column align-items-center">
        <div class="dz-img flex-shrink-0">
            <img class="img-fluid dz-img-inner" alt="${selectedFile.name}" src="${url}">
        </div>
        <div class="dz-file-wrapper flex-grow-1 mt-2">
            <h6 class="dz-filename">
                <span class="dz-title" data-dz-name="">${selectedFile.name}</span>
            </h6>
            <div class="dz-size" data-dz-size=""><strong>${selectedFile.size}</strong></div>
        </div>
    </div>`;
        document.getElementById("imagePathInput").value = `${path}`;
        document.getElementById("imageAltInput").value = `${selectedFile.name}`;

        // Set innerHTML for the item
        item.innerHTML = fileItem;

        // Append the item to the container
        attachFileContainer.appendChild(item);

        // Add event listener to the close icon
        const closeIcon = item.querySelector(".delete-selected-file");
        closeIcon.addEventListener("click", function () {
            selectedFile = null;
            // Re-render the files after deletion
            renderFile();
        });
    }
};

// Listen for the selectedFilesUpdated event to update when files change
window.addEventListener("selectedFilesUpdated", function () {
    selectedFiles = event.detail;
    renderFiles();
});

// Listen for the selectedFileUpdated event to update when file change
window.addEventListener("selectedFileUpdated", function () {
    selectedFile = event.detail;
    renderFile();
});
