document.getElementById("openImageModal").addEventListener("click", () => {
    renderModal("selectImageModal");
});

const renderFile = (selectedFile) => {
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

        item.innerHTML = fileItem;

        attachFileContainer.appendChild(item);

        const closeIcon = item.querySelector(".delete-selected-file");
        closeIcon.addEventListener("click", function () {
            selectedFile = null;
            document.getElementById("imagePathInput").value = "";
            document.getElementById("imageAltInput").value = "";
            renderFile();
        });
    }
};

window.addEventListener("selectedFileUpdated", function () {
    const selectedFile = event.detail;
    renderFile(selectedFile);
});
