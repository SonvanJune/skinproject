function renderModal(modalId, folderPath = "") {
    const modalContainer = document.getElementById("modalContainer");
    const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 12px;">
                <div class="header-item">
                    <h1 class="modal-title fs-5" id="fileModalLabel">File</h1>
                    <ul>
                        <li id="upload-button"><i class="fa-solid fa-arrow-up-from-bracket"></i>Upload
                            <input type="file" name="file[]" id="file" hidden multiple>
                        </li>
                        <li id="create-folder"><i class="fa-solid fa-folder-plus"></i>Create folder</li>
                    </ul>
                </div>
                <button type="button" class="btn-accept-file" data-bs-dismiss="modal">Choose</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0">
                <div class="row">
                    <!-- Show the folders -->
                    <div class="col-3 border-right" style="padding-right: 0px">
                        <div class="file-tree scrollable">
                            <ul id="folder-list">
                            </ul>
                        </div>
                    </div>
                    <!-- Show all images in the folder -->
                    <div class="col-9 files-box" style="padding-right: 0px">
                        <div class="row image-container scrollable">
                        </div>
                        <!-- popup confirm  -->
                        <div id="confirm-modal"></div> <!-- Container for the confirmation modal -->
                        <div id="input-modal"></div> <!-- Container for the input modal -->
                    </div>
                    <!-- End show all images in the folder -->
                </div>
            </div>
        </div>
    </div>
</div>
    `;

    // Xóa modal cũ (nếu có) và thêm modal mới
    modalContainer.innerHTML = modalHTML;
    new FileManager(`#${modalId}`, 1);

    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

function renderFolderModal(modalId, folderPath = "") {
    const modalContainer = document.getElementById("modalContainer");
    const modalHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 12px;">
                <div class="header-item">
                    <h1 class="modal-title fs-5" id="fileModalLabel">File</h1>
                    <ul>
                        <li id="upload-button"><i class="fa-solid fa-arrow-up-from-bracket"></i>Upload
                            <input type="file" name="file" id="file" hidden>
                        </li>
                        <li id="create-folder"><i class="fa-solid fa-folder-plus"></i>Create folder</li>
                        <li id="selected-folder" class="text-muted" style="font-size: 0.875rem; padding-left: 10px;">No folder selected</li>
                    </ul>
                </div>
                <button type="button" class="btn-accept-file" data-bs-dismiss="modal">Choose</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0">
                <div class="row">
                    <!-- Show the folders -->
                    <div class="col-3 border-right" style="padding-right: 0px">
                        <div class="file-tree scrollable">
                            <ul id="folder-list">
                            </ul>
                        </div>
                    </div>
                    <!-- Show all images in the folder -->
                    <div class="col-9 files-box" style="padding-right: 0px">
                        <div class="row image-container scrollable">
                        </div>
                        <!-- popup confirm  -->
                        <div id="confirm-modal"></div> <!-- Container for the confirmation modal -->
                        <div id="input-modal"></div> <!-- Container for the input modal -->
                    </div>
                    <!-- End show all images in the folder -->
                </div>
            </div>
        </div>
    </div>
</div>
    `;

    // Xóa modal cũ (nếu có) và thêm modal mới
    modalContainer.innerHTML = modalHTML;
    new FileManager(`#${modalId}`, 2);

    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}
class FileManager {
    constructor(modalSelector, getOnlyFoler) {
        this.modalElement = document.querySelector(modalSelector);
        this.initEvents(getOnlyFoler);
    }

    initEvents(getOnlyFoler) {
        const BASE_DIRECTORY = "file-manager";
        const FOLDER_LEVEL = 10;

        // Ngữ cảnh hiện tại
        let fileManagerContext = null;
        // Hàm callback khi chọn file
        let fileManagerCallback = null;

        const createFolderBtn = document.getElementById("create-folder");
        const imageContainer = document.querySelector(".image-container");
        const fileTree = document.querySelector(".file-tree");
        const filesBox = document.querySelector(".files-box");
        const sectionId = "filesSection";

        let openFolders = JSON.parse(localStorage.getItem("openFolders")) || [];
        const folderList = document.getElementById("folder-list");

        const skeletomImageLoading = `<div id="image-loading" class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card is-loading">
                                        <div class="image"></div>
                                        <div class="content">
                                            <h2></h2>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="image-loading" class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card is-loading">
                                        <div class="image"></div>
                                        <div class="content">
                                            <h2></h2>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="image-loading" class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card is-loading">
                                        <div class="image"></div>
                                        <div class="content">
                                            <h2></h2>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="image-loading" class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card is-loading">
                                        <div class="image"></div>
                                        <div class="content">
                                            <h2></h2>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>`;

        function getFolders(directory) {
            const url = `/api/folders/${directory.replaceAll("/", ".")}`;
            imageContainer.innerHTML = skeletomImageLoading;

            return axios
                .get(url, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    renderImages(response.data.files);
                    return response.data;
                })
                .catch((error) => {
                    console.error(
                        "There was a problem with the fetch operation:",
                        error,
                    );
                });
        }

        // Create folder function
        function createFolder(folderName, baseDirectory) {
            const formData = new FormData();

            // Split the baseDirectory into an array of levels
            const directoryParts = baseDirectory.split("/");

            // Remove the last level if there are more than 2 levels (to keep max 3)
            if (directoryParts.length > FOLDER_LEVEL - 1) {
                directoryParts.pop(); // Remove the last level
                baseDirectory = directoryParts.join("/"); // Reconstruct the path
            }


            formData.append("folder_name", folderName);
            formData.append("base_directory", baseDirectory);

            return axios
                .post("/folders/create", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    showDialog(data?.message);
                })
                .catch((error) => {
                    console.error("Error renaming folder:", error);
                    showDialog(
                        "Cannot conduct the request. Please try again later!",
                    );
                });
        }

        // Reaname folder
        function fetchRenameFolder(baseDirectory, currentName, newName) {
            const formData = new FormData();

            formData.append("base_directory", baseDirectory);
            formData.append("current_name", currentName);
            formData.append("new_name", newName);

            axios
                .post("/folders/rename", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    showDialog(data?.message);
                })
                .catch((error) => {
                    console.error("Error renaming folder:", error);
                    showDialog(
                        "Cannot conduct the request. Please try again later!",
                    );
                });
        }

        // Delete folder
        function fetchDeleteFolder(baseDirectory) {
            const formData = new FormData();

            formData.append("base_directory", baseDirectory);

            axios
                .post("/folders/delete", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    showDialog(data?.message);
                })
                .catch((error) => {
                    console.error("Error fetchDeleteFolder, ", error);
                    showDialog(
                        "Cannot conduct the request. Please try again later!",
                    );
                });
        }

        // Render folder tree recursively up to 5 levels deep
        function renderFolders(folders, parentElement) {
            if(document.getElementById("productFilePathLabel")){
                if(document.getElementById("selected-folder")){
                    document.getElementById("selected-folder").textContent =
                    document.getElementById("productFilePathLabel").value;
                }
            }
            
            folders.directories.forEach((folderName) => {
                const folderItem = document.createElement("li");
                const level = getFolderLevel(folders.base_directory);
                const baseDirectory = folders.base_directory + "/" + folderName;

                folderItem.className = "folder-item";

                folderItem.innerHTML = `
            <div class="folder-name" 
                data-base-directory="${baseDirectory}" 
                aria-level="${level}">
                <div class="folder-content">
                    <i id="ic-folder" class="fa-solid fa-folder"></i>
                    <span class="folder">${folderName}</span>
                </div>
                <i class="fa-solid fa-caret-right"></i>
            </div>
            <ul class="nested" style="display:none;"></ul>
        `;
                parentElement.appendChild(folderItem);

                folderItem.addEventListener("click", function (event) {
                    if (getOnlyFoler == 2) {
                        const chooseFileBtn =
                            document.querySelector(".btn-accept-file");
                        chooseFileBtn.style.display = "inline-block";
                        if (document.getElementById("productFilePathLabel")) {
                            const inputFilePath = document.getElementById(
                                "productFilePathLabel",
                            );

                            inputFilePath.value = baseDirectory;
                            document.getElementById(
                                "selected-folder",
                            ).textContent = baseDirectory;
                        }
                        event.stopPropagation();
                    }
                });

                folderItem.addEventListener("contextmenu", function (event) {
                    event.preventDefault(); // Prevent the default context menu from appearing

                    // Remove any existing menu
                    const existingMenu = document.querySelector(".menu-box");
                    if (existingMenu) {
                        existingMenu.remove();
                    }

                    // Create a new context menu
                    const menuBox = document.createElement("div");
                    menuBox.className = "menu-box"; // Assign class for styling
                    menuBox.innerHTML = `
                <ul>
                    <li id="create-folder" class="menu-item">
                        <div class="item-icon">
                            <i class="fa-solid fa-folder-plus"></i>
                        </div>
                        <div class="item-text">
                            Create
                        </div>
                    </li>
                    <li id="rename-folder" class="menu-item">
                        <div class="item-icon">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div class="item-text">
                            Rename
                        </div>
                    </li>
                    <li id="delete-folder" class="menu-item">
                        <div class="item-icon">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <div class="item-text">
                            Delete
                        </div>                                                   
                    </li>
                </ul>
                `;

                    // Position the menu based on mouse coordinates relative to the folder item
                    const parentRect = folderItem.getBoundingClientRect(); // Get the folder item's position
                    const x = event.clientX - parentRect.left; // Calculate X position
                    const y = event.clientY - parentRect.top; // Calculate Y position

                    menuBox.style.position = "absolute"; // Set position to absolute for precise placement
                    menuBox.style.top = `${y}px`; // Position menu vertically
                    menuBox.style.left = `${x}px`; // Position menu horizontally

                    folderItem.appendChild(menuBox); // Append the menu to the folder item

                    // Prevent clicks on the menu from closing it
                    menuBox.addEventListener("click", function (event) {
                        event.stopPropagation();
                    });

                    // Add click event listeners to menu items
                    menuBox.querySelectorAll(".menu-item").forEach((item) => {
                        item.addEventListener("click", function () {
                            switch (item.id) {
                                case "create-folder":
                                    showInputModal({
                                        title: "Create New Folder",
                                        inputTitle: "Enter new folder name",
                                        onOk: (folderName) => {
                                            showConfirmModal({
                                                title: `Are you sure you want to create a folder named "${folderName}"?`,
                                                onYes: () => {
                                                    createFolder(
                                                        folderName,
                                                        baseDirectory,
                                                    );
                                                    reloadFolders();
                                                },
                                                onNo: () => {
                                                    console.log("Canceled!");
                                                },
                                            });
                                        },
                                        onCancel: () => {
                                            console.log(
                                                "Folder creation canceled.",
                                            );
                                        },
                                    });
                                    break;
                                case "rename-folder":
                                    showInputModal({
                                        title: "Rename Folder",
                                        inputTitle: "Enter new folder name",
                                        onOk: (newFolderName) => {
                                            showConfirmModal({
                                                title: `Do you want to rename this folder with: "${newFolderName}"?`,
                                                onYes: () => {
                                                    fetchRenameFolder(
                                                        folders.base_directory,
                                                        folderName,
                                                        newFolderName,
                                                    );
                                                    reloadFolders();
                                                },
                                                onNo: () => {
                                                    console.log("Canceled!");
                                                },
                                            });
                                        },
                                        onCancel: () => {
                                            console.log(
                                                "Folder creation canceled.",
                                            );
                                        },
                                    });
                                    break;
                                case "delete-folder":
                                    showConfirmModal({
                                        title: `Do you want to delete this folder: "${folderName}"?`,
                                        onYes: () => {
                                            // closeFolder(baseDirectory);
                                            // fetchDeleteFolder(baseDirectory);
                                            // reloadFolders();
                                            checkFolderBeforeDelete(
                                                baseDirectory,
                                            );
                                        },
                                        onNo: () => {
                                            console.log("Canceled!");
                                        },
                                    });
                                    break;
                                default:
                                    break;
                            }
                        });
                    });
                    event.stopPropagation();
                });

                const folderNameDiv = folderItem.querySelector(".folder-name");
                folderNameDiv.addEventListener("click", function () {
                    loadFolder(folderNameDiv);
                });

                const folderItems = document.querySelectorAll(".folder-name");
                dropItem(folderItems);
            });
        }

        // Count '/' in string path
        function getFolderLevel(path) {
            const level = path.split("/").length;
            return level > FOLDER_LEVEL ? FOLDER_LEVEL : level;
        }

        // Load and display folders and files
        async function loadFolder(element) {
            const baseDirectory = element.dataset.baseDirectory;
            const folderData = await getFolders(
                baseDirectory.replaceAll("/", "."),
            );
            const currentLevel = parseInt(
                element.getAttribute("aria-level"),
                10,
            );

            if (folderData) {
                const nestedList = element.nextElementSibling;
                const rotateCaret = element.querySelector(".fa-caret-right");
                const iconOpenFolder = element.querySelector("#ic-folder");
                renderImages(folderData.files);

                if (nestedList.style.display === "block") {
                    // Close folder
                    // Close folder if already open
                    nestedList.style.display = "none";
                    rotateCaret.style.transform = "rotate(0deg)";
                    iconOpenFolder.className = "fa-solid fa-folder";
                    element.classList.remove("active");

                    // Get parent directory
                    const parentDirectory = baseDirectory.slice(
                        0,
                        baseDirectory.lastIndexOf("/"),
                    );

                    const data = await getFolders(
                        parentDirectory.replaceAll("/", "."),
                    );
                    renderImages(data.files);

                    // Activate parent folder
                    const parentElement = document.querySelector(
                        `.folder-name[data-base-directory="${parentDirectory}"]`,
                    );
                    if (parentElement) {
                        parentElement.classList.add("active");
                    }

                    closeFolder(baseDirectory);
                } else {
                    // Open folder
                    // Close other folders at the same level
                    const siblingFolders = document.querySelectorAll(
                        `.folder-name[aria-level="${currentLevel}"]`,
                    );
                    siblingFolders.forEach((folder) => {
                        if (folder !== element) {
                            const siblingNestedList = folder.nextElementSibling;
                            if (
                                siblingNestedList &&
                                siblingNestedList.style.display === "block"
                            ) {
                                siblingNestedList.style.display = "none";
                                const siblingRotateCaret =
                                    folder.querySelector(".fa-caret-right");
                                const siblingIconFolder =
                                    folder.querySelector("#ic-folder");
                                siblingRotateCaret.style.transform =
                                    "rotate(0deg)";
                                siblingIconFolder.className =
                                    "fa-solid fa-folder";
                                folder.classList.remove("active");
                            }
                        }
                    });

                    const allFolders = document.querySelectorAll(
                        ".folder-name.active",
                    );
                    allFolders.forEach((folder) => {
                        folder.classList.remove("active");
                    });

                    // Open current folder
                    nestedList.style.display = "block";
                    rotateCaret.style.transform = "rotate(90deg)";
                    iconOpenFolder.className = "fa-solid fa-folder-open";
                    element.classList.add("active");

                    openFolder(baseDirectory, currentLevel);

                    // Clear and render subfolders
                    nestedList.innerHTML = "";
                    renderFolders(folderData, nestedList);
                }
            }
        }

        // ====================================================
        // In LoacalStorage
        // ====================================================

        // Handle adding the open folder to localStorage
        function openFolder(folderPath, level) {
            // Remove all folders from the same level before adding the new one
            openFolders = openFolders.filter(
                (folder) => parseInt(folder.level, 10) < level,
            );

            // Add the new folder to the list of open folders
            openFolders.push({ path: folderPath, level });
            localStorage.setItem("openFolders", JSON.stringify(openFolders));
        }

        // Handle removing the folder from localStorage
        function closeFolder(folderPath) {
            // Remove the folder and all its subfolders
            openFolders = openFolders.filter(
                (folder) => !folder.path.startsWith(folderPath),
            );
            localStorage.setItem("openFolders", JSON.stringify(openFolders));
        }

        //Render images in the current folder
        function renderImages(files) {
            imageContainer.innerHTML = "";
            if (files.length > 0) {
                files.forEach((file) => {
                    const imageItemBlock = document.createElement("div");
                    imageItemBlock.className =
                        "col-12 col-sm-6 col-md-4 col-lg-3";
                    // Check MINE type is image
                    const isImage = file.mine.startsWith("image/");
                    const getFileUrl = routeGetFileTemplate.replace(":filename", file.filePath);
                    imageItemBlock.innerHTML = `
                <div class="image-item" draggable="true"
                    data-image-src="${getFileUrl}">
                    <div class="image">
                        <img src="${
                            isImage
                                ? getFileUrl
                                : "/images/image-default.png"
                        }" 
                         loading="lazy" 
                         alt="${file.name}">
                    </div>
                    <div class="image-content">
                        <div class="image-name">
                            ${file.name}
                        </div>
                        <div class="image-date">
                           ${file.type}
                        </div>
                        <div class="image-amount">
                            ${file.size}
                        </div>
                    </div>
                </div>
        `;
                    imageContainer.appendChild(imageItemBlock);

                    const imageItem =
                        imageItemBlock.querySelector(".image-item");
                    // imageItem.addEventListener("dragstart", handleDragStart);

                    // Check if file is selected and activate the item
                    let selectedFiles =
                        JSON.parse(sessionStorage.getItem(sectionId)) || [];
                    const selectedImage =
                        document.getElementById("selectImageModal");
                    const selectImages =
                        document.getElementById("selectImagesModal");

                    if (selectImages)
                        if (
                            selectedFiles.some(
                                (selectedFile) =>
                                    selectedFile.filePath === file.filePath,
                            )
                        ) {
                            imageItem.classList.add("image-item--active");
                        }

                    // Retrieve the list of selected files from sessionStorage when the page loads
                    imageItem.addEventListener("click", function () {
                        const chooseFileBtn =
                            document.querySelector(".btn-accept-file");
                        if (getOnlyFoler != 2) {
                            chooseFileBtn.style.display = "inline-block";
                        }

                        // const selectedImage = document.getElementById("selectImageModal");
                        if (selectedImage) {
                            // let selectedFiles = JSON.parse(sessionStorage.getItem(sectionId)) || [];
                            // if (selectedFiles.length > 0) {
                            document
                                .querySelectorAll(".image-item--active")
                                .forEach((item) =>
                                    item.classList.remove("image-item--active"),
                                );
                            // }
                            imageItem.classList.add("image-item--active");

                            chooseFileBtn.addEventListener(
                                "click",
                                function () {
                                    // sessionStorage.setItem(sectionId,JSON.stringify(selectedFiles));
                                    // Dispatch một sự kiện thông báo file đã được cập nhật
                                    const event = new CustomEvent(
                                        "selectedFileUpdated",
                                        {
                                            detail: file,
                                        },
                                    );
                                    window.dispatchEvent(event);
                                },
                            );
                        }
                        const imagePickerModalInstance =
                            document.getElementById("imagePickerModal");
                        if (imagePickerModalInstance) {
                            selectedFiles = [];
                            // Lấy tất cả các phần tử đang active
                            const allActiveItems = document.querySelectorAll(
                                ".image-item--active",
                            );

                            // Kiểm tra nếu phần tử được click đã active
                            const isAlreadyActive =
                                imageItem.classList.contains(
                                    "image-item--active",
                                );

                            // Loại bỏ class "image-item--active" khỏi tất cả
                            allActiveItems.forEach((item) => {
                                item.classList.remove("image-item--active");
                            });

                            // Nếu không active, bật active và chèn ảnh
                            if (!isAlreadyActive) {
                                imageItem.classList.add("image-item--active");
                                chooseFileBtn.addEventListener(
                                    "click",
                                    function () {
                                        // Lấy vị trí hiện tại của con trỏ trong Quill
                                        const range =
                                            window.quill.getSelection();
                                        if (range) {
                                            // Chèn ảnh vào Quill
                                            window.quill.insertEmbed(
                                                range.index,
                                                "image",
                                                file.filePath,
                                            );

                                            // Lưu vị trí chèn ảnh để có thể xóa sau này
                                            imageItem.dataset.quillIndex =
                                                range.index;
                                        }
                                    },
                                );
                            } else {
                                // Nếu ảnh đang active và được click lại, bỏ active và xóa ảnh
                                const quillIndex = parseInt(
                                    imageItem.dataset.quillIndex,
                                    10,
                                ); // Lấy vị trí từ dataset
                                if (!isNaN(quillIndex)) {
                                    // Xóa ảnh khỏi Quill tại vị trí đã lưu
                                    window.quill.deleteText(quillIndex, 1); // Xóa 1 ký tự (embed là 1 ký tự)
                                }
                            }
                        }

                        if (selectImages) {
                            let selectedFiles =
                                JSON.parse(sessionStorage.getItem(sectionId)) ||
                                [];

                            // Check the current state of the imageItem and modify the list accordingly
                            const index = selectedFiles.findIndex(
                                (item) => item.filePath === file.filePath,
                            );

                            if (index !== -1) {
                                // Remove the file from the list
                                selectedFiles.splice(index, 1);
                                imageItem.classList.remove(
                                    "image-item--active",
                                );
                            } else {
                                // Add the file to the list
                                selectedFiles.push(file);
                                imageItem.classList.add("image-item--active");
                            }

                            // Update the selected files list in sessionStorage
                            sessionStorage.setItem(
                                sectionId,
                                JSON.stringify(selectedFiles),
                            );
                            chooseFileBtn.addEventListener(
                                "click",
                                function () {

                                    // Dispatch a custom event to notify other scripts about the updated selected files
                                    const event = new CustomEvent(
                                        "selectedFilesUpdated",
                                        {
                                            detail: selectedFiles,
                                        },
                                    );
                                    window.dispatchEvent(event);
                                },
                            );
                        }
                    });

                    imageItem.addEventListener("contextmenu", function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Xóa menu cũ nếu có
                        const existingMenu =
                            document.querySelector("#menu-box");
                        if (existingMenu) {
                            existingMenu.remove();
                        }

                        const menuBox = document.createElement("div");
                        menuBox.className = "menu-box";
                        menuBox.id = "menu-box";
                        menuBox.innerHTML = `
            <ul>
                <li id="rename-file" class="menu-item">
                    <div class="item-icon">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>
                    <div class="item-text">
                        Rename
                    </div>
                </li>
                <li id="delete-file" class="menu-item">
                    <div class="item-icon">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <div class="item-text">
                        Delete
                    </div>                                                   
                </li>
            </ul>
            `;
                        imageItem.appendChild(menuBox);
                        menuBox.addEventListener("click", function (event) {
                            event.stopPropagation();
                        });

                        const activeFolder = document.querySelector(
                            ".folder-name.active",
                        );
                        let baseDirectory = "";
                        const fileInfo = {
                            name: file.name,
                            size: file.size,
                            type: file.type,
                            mine: file.mine,
                            src: file.filePath,
                        };

                        if (activeFolder) {
                            baseDirectory = activeFolder.getAttribute(
                                "data-base-directory",
                            );
                        } else {
                            baseDirectory = BASE_DIRECTORY;
                        }

                        menuBox.querySelectorAll(".menu-item").forEach((e) => {
                            e.addEventListener("click", function () {
                                switch (e.id) {
                                    case "rename-file":
                                        showInputModal({
                                            title: "Rename File Folder",
                                            inputTitle: "Enter new file name",
                                            inputValue: file.name.substring(
                                                0,
                                                file.name.lastIndexOf("."),
                                            ),
                                            onOk: (folderName) => {
                                                showConfirmModal({
                                                    title: `Do you want to rename "${folderName}"?`,
                                                    onYes: () => {
                                                        fetchRenameFile(
                                                            baseDirectory,
                                                            file.name,
                                                            folderName,
                                                        );
                                                    },
                                                    onNo: () => {
                                                        console.log(
                                                            "Canceled!",
                                                        );
                                                    },
                                                });
                                            },
                                            onCancel: () => {
                                                console.log(
                                                    "Folder creation canceled.",
                                                );
                                            },
                                        });

                                        break;
                                    case "move-file":
                                        break;
                                    case "delete-file":
                                        showConfirmModal({
                                            title: "Do yout want to delete this file",
                                            fileInfo: fileInfo,
                                            onYes: () => {
                                                fetchRemoveFile(
                                                    file.name,
                                                    baseDirectory,
                                                );
                                            },
                                            onNo: () => {
                                                console.log("Canceled!");
                                            },
                                        });

                                        break;
                                    default:
                                        break;
                                }
                            });
                        });
                    });
                });
            } else {
                const folderEmpty = document.createElement("div");
                folderEmpty.className = "folder-empty";
                folderEmpty.innerHTML = `
         <img class="folder-empty-image" src="/images/folder_empty.png">
        <h3>No files found in this folder!</h3>`;
                imageContainer.appendChild(folderEmpty);
            }
        }

        document.addEventListener("click", function (event) {
            // Kiểm tra và xóa menu-box nếu tồn tại
            const existingMenu = document.querySelector(".menu-box");
            if (existingMenu) {
                existingMenu.remove();
            }
        });

        // Initialize folder rendering
        async function initializeFolders() {
            const data = await getFolders(BASE_DIRECTORY);
            folderList.innerHTML = "";
            renderFolders(data, folderList);
            renderImages(data.files);

            openFolders.forEach(async (folder) => {
                const folderElement = await waitForElementToRender(
                    `[data-base-directory="${folder.path}"]`,
                );
                if (folderElement) {
                    loadFolder(folderElement); // Mở lại thư mục
                }
            });
        }

        function waitForElementToRender(selector) {
            return new Promise((resolve) => {
                const element = document.querySelector(selector);
                if (element) {
                    resolve(element); // Nếu phần tử đã có sẵn thì trả về ngay
                } else {
                    // Sử dụng MutationObserver để đợi phần tử xuất hiện trong DOM
                    const observer = new MutationObserver(
                        (mutationsList, observer) => {
                            const foundElement =
                                document.querySelector(selector);
                            if (foundElement) {
                                resolve(foundElement); // Phần tử xuất hiện, trả về
                                observer.disconnect(); // Ngừng quan sát sau khi tìm thấy phần tử
                            }
                        },
                    );

                    observer.observe(document.body, {
                        childList: true, // Quan sát sự thay đổi của phần tử con
                        subtree: true, // Quan sát tất cả các phần tử con trong cây DOM
                    });
                }
            });
        }

        // Gọi hàm khởi tạo
        initializeFolders();

        // ====================================================
        // File Upload Handling
        // ====================================================

        // Trigger hidden file input
        const uploadButton = document.getElementById("upload-button");
        const fileInput = document.getElementById("file");
        uploadButton.addEventListener("click", () => fileInput.click());

        // Handle file upload
        fileInput.addEventListener("change", async (event) => {
            const files = event.target.files ?? [];
            const activeFolder = document.querySelector(".folder-name.active");
            let baseDirectory = "";

            if (activeFolder) {
                baseDirectory = activeFolder.getAttribute(
                    "data-base-directory",
                );
            } else {
                baseDirectory = BASE_DIRECTORY;
            }

            if (files.length > 0) {
                const selectedFiles = Array.from(files);

                // Get List file in the folder selected
                const folderData = await getFolders(baseDirectory);
                let fileNames = [];
                let newFileName = "";
                fileNames = folderData.files.map((file) => file.name);

                const eachUpload = (selectedFiles, index) => {
                    //get object file or stop uploading
                    if (index >= selectedFiles.length) return;
                    const selectedFile = selectedFiles[index];

                    //check name
                    if (folderData.files.length > 0) {
                        newFileName = getUniqueFileName(
                            selectedFile.name,
                            fileNames,
                        );
                    }

                    //prepare data
                    const fileInfo = {
                        name:
                            newFileName === ""
                                ? selectedFile.name
                                : newFileName,
                        size: formatFileSize(selectedFile.size),
                        type: selectedFile.type,
                        mine: selectedFile.type,
                        src: URL.createObjectURL(selectedFile),
                    };

                    showConfirmModal({
                        title: "Do yout want to upload this image",
                        fileInfo: fileInfo,
                        onYes: () => {
                            fetchUploadFile(selectedFile, () => {
                                eachUpload(selectedFiles, index + 1);
                            });
                        },
                        onNo: () => {
                            console.log("Canceled!");
                            eachUpload(selectedFiles, index + 1);
                        },
                    });
                };

                eachUpload(selectedFiles, 0);
            }
        });

        // handle get unquied file name
        function getUniqueFileName(fileName, existingFileNames) {
            let baseName =
                fileName.substring(0, fileName.lastIndexOf(".")) || fileName;
            const extension =
                fileName.substring(fileName.lastIndexOf(".")) || "";
            let newName = fileName;

            let copyIndex = 1;
            while (existingFileNames.includes(newName)) {
                newName = `${baseName} (copy${
                    copyIndex > 1 ? ` ${copyIndex}` : ""
                })${extension}`;
                copyIndex++;
            }
            return newName;
        }

        // ====================================================
        // Fetching file function (Call API)
        // ====================================================

        // Fetch upload file
        function fetchUploadFile(file, onDone) {
            const activeFolder = document.querySelector(".folder-name.active");
            const formData = new FormData();
            let baseDirectory = "";

            if (activeFolder) {
                baseDirectory = activeFolder.getAttribute(
                    "data-base-directory",
                );
            } else {
                baseDirectory = BASE_DIRECTORY;
            }

            imageContainer.innerHTML = skeletomImageLoading;

            formData.append("file", file);
            formData.append("base_directory", baseDirectory);

            axios
                .post("/files/upload", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(function (response) {
                    const data = response.data;

                    if (data.success) {
                        return getFolders(
                            baseDirectory.replaceAll("/", "."),
                        ).then((folderData) => {
                            renderImages(folderData.files);
                        });
                    } else {
                        console.error("Upload failed:", data.message);
                        showDialog("Upload file failed. Plase try again!");
                    }
                })
                .catch(function (error) {
                    console.error("Error uploading files:", error);
                    showDialog(`Upload file failed. Plase try again!`);
                    return getFolders(baseDirectory.replaceAll("/", ".")).then(
                        (folderData) => {
                            renderImages(folderData.files);
                        },
                    );
                })
                .finally(onDone);
        }

        // Fetch remove file
        function fetchRemoveFile(file, baseDirectory) {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("base_directory", baseDirectory);
            imageContainer.innerHTML = skeletomImageLoading;

            axios
                .post("/files/remove", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    if (data) {
                        return getFolders(
                            baseDirectory.replaceAll("/", "."),
                        ).then((folderData) => {
                            renderImages(folderData.files);
                            showDialog(data.message);
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error removing files:", error);
                    showDialog(
                        "Cannot conduct the request. Please try again later!",
                    );
                });
        }

        // Fetch rename file
        function fetchRenameFile(baseDirectory, currentFileName, newFileName) {
            const formData = new FormData();

            formData.append("basePath", baseDirectory);
            formData.append("currentFileName", currentFileName);
            formData.append("newFileName", newFileName);

            imageContainer.innerHTML = skeletomImageLoading;

            axios
                .post("/files/rename", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    showDialog(data?.message);

                    return getFolders(baseDirectory.replaceAll("/", ".")).then(
                        (folderData) => {
                            renderImages(folderData.files);
                        },
                    );
                })
                .catch((error) => {
                    console.error("Error renaming files:", error);
                    showDialog(
                        "Cannot conduct the request. Please try again later!",
                    );
                });
        }

        // Fetch move file
        function fetchMoveFile(oldBase, newBase, fileName) {
            const formData = new FormData();

            formData.append("oldBase", oldBase);
            formData.append("newBase", newBase);
            formData.append("fileName", fileName);

            imageContainer.innerHTML = skeletomImageLoading;

            axios
                .post("/files/move", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                .then((response) => {
                    const data = response.data;
                    return getFolders(oldBase.replaceAll("/", "."));
                })
                .then((folderData) => {
                    renderImages(folderData.files);
                })
                .catch((error) => {
                    console.error("Error moving files:", error);
                });
        }

        // ====================================================
        // Handel create folder
        // ====================================================

        // Show the create folder modal when the user clicks "Create folder"
        createFolderBtn.addEventListener("click", function () {
            // Hide the confirm-box and show file-name-box
            showInputModal({
                title: "Create New Folder",
                inputTitle: "Enter new folder name",
                onOk: (folderName) => {
                    showConfirmModal({
                        title: `Are you sure you want to create a folder named "${folderName}"?`,
                        onYes: () => {
                            createFolderInputYes(folderName);
                        },
                        onNo: () => {
                            console.log("Canceled!");
                        },
                    });
                },
                onCancel: () => {
                    console.log("Folder creation canceled.");
                },
            });
        });

        // Handel the "Ok" button click for create folder
        function createFolderInputYes(folderName) {
            folderName = folderName.trim();
            if (folderName) {
                const activeFolder = document.querySelector(
                    ".folder-name.active",
                );
                let baseDirectory = "";

                if (activeFolder) {
                    baseDirectory = activeFolder.getAttribute(
                        "data-base-directory",
                    );
                } else {
                    baseDirectory = BASE_DIRECTORY;
                }
                createFolder(folderName, baseDirectory);
                reloadFolders();
            }
        }

        // Handel the "No" button click for create folder
        function createFolderInputNo() {}

        // Function to reload the directory tree after adding, deleting, or editing a folder
        async function reloadFolders() {
            const folderList = document.getElementById("folder-list");
            folderList.innerHTML = "";
            const data = await getFolders(BASE_DIRECTORY);
            renderFolders(data, folderList);
            renderImages(data.files);

            openFolders.forEach(async (folder) => {
                const folderElement = await waitForElementToRender(
                    `[data-base-directory="${folder.path}"]`,
                );
                if (folderElement) {
                    loadFolder(folderElement); // Mở lại thư mục
                }
            });
        }

        // ====================================================
        // Check folder before delete
        // ====================================================
        // Check folder before delete
        async function checkFolderBeforeDelete(baseDirectory) {
            // Get the folder name from the base directory
            const folderName = baseDirectory.split("/").pop();

            // Get the folder data
            const folderData = await getFolders(
                baseDirectory.replaceAll("/", "."),
            );

            // Check if the folder contains any files
            if (folderData.files.length > 0) {
                // Folder contains files, show warning to the user
                showConfirmModal({
                    title: `This folder contains files. Do you still want to delete "${folderName}"?`,
                    onYes: () => {
                        deleteFolderWithChildren(baseDirectory);
                    },
                    onNo: () => {
                        console.log("Deletion canceled!");
                    },
                });
            } else if (folderData.directories.length > 0) {
                // Folder has child folders, check each child folder for files
                let hasFilesInSubfolders = false;
                for (let dir of folderData.directories) {
                    const subfolderData = await getFolders(
                        `${baseDirectory}/${dir}`,
                    );
                    if (subfolderData.files.length > 0) {
                        hasFilesInSubfolders = true;
                        break;
                    }
                }

                if (hasFilesInSubfolders) {
                    // If any subfolder contains files, warn the user
                    showConfirmModal({
                        title: `One or more subfolders of "${folderName}" contain files. Do you still want to delete this folder?`,
                        onYes: () => {
                            deleteFolderWithChildren(baseDirectory);
                        },
                        onNo: () => {
                            console.log("Deletion canceled!");
                        },
                    });
                } else {
                    // No files in subfolders, proceed with deletion
                    deleteFolderWithChildren(baseDirectory);
                }
            } else {
                // Folder is empty, proceed with deletion
                deleteFolderWithChildren(baseDirectory);
            }
        }

        async function deleteFolderWithChildren(baseDirectory) {
            // Proceed with the deletion of the folder
            closeFolder(baseDirectory);
            fetchDeleteFolder(baseDirectory);
            reloadFolders();
        }

        // ====================================================
        // Open sub menu
        // ====================================================

        // The right-click event to display the folder creation menu in the directory tree.
        fileTree.addEventListener("contextmenu", function (event) {
            event.preventDefault();
            const activeFolder = document.querySelector(".folder-name.active");
            let baseDirectory = "";

            if (activeFolder) {
                baseDirectory = activeFolder.getAttribute(
                    "data-base-directory",
                );
            } else {
                baseDirectory = BASE_DIRECTORY;
            }

            // Remove any existing menu
            const existingMenu = document.querySelector(".menu-box");
            if (existingMenu) {
                existingMenu.remove();
            }

            // Create a new context menu
            const menuBox = document.createElement("div");
            menuBox.className = "menu-box"; // Assign class for styling
            menuBox.innerHTML = `
        <ul>
            <li id="create-folder" class="menu-item">
                <div class="item-icon">
                    <i class="fa-solid fa-folder-plus"></i>
                </div>
                <div class="item-text">
                    Create
                </div>
            </li>
        </ul>
        `;

            // Position the menu based on mouse coordinates relative to the folder item
            const parentRect = fileTree.getBoundingClientRect(); // Get the folder item's position
            const x = event.clientX - parentRect.left; // Calculate X position
            const y = event.clientY - parentRect.top; // Calculate Y position

            menuBox.style.position = "absolute"; // Set position to absolute for precise placement
            menuBox.style.top = `${y}px`; // Position menu vertically
            menuBox.style.left = `${x}px`; // Position menu horizontally

            fileTree.appendChild(menuBox); // Append the menu to the folder item

            // Prevent clicks on the menu from closing it
            menuBox.addEventListener("click", function (event) {
                event.stopPropagation();
            });

            // Add click event listeners to menu items
            menuBox.querySelectorAll(".menu-item").forEach((item) => {
                item.addEventListener("click", function () {
                    switch (item.id) {
                        case "create-folder":
                            showInputModal({
                                title: "Create New Folder",
                                inputTitle: "Enter new folder name",
                                onOk: (folderName) => {
                                    showConfirmModal({
                                        title: `Are you sure you want to create a folder named "${folderName}"?`,
                                        onYes: () => {
                                            createFolder(
                                                folderName,
                                                baseDirectory,
                                            );
                                            reloadFolders();
                                        },
                                        onNo: () => {
                                            console.log("Canceled!");
                                        },
                                    });
                                },
                                onCancel: () => {
                                    console.log("Folder creation canceled.");
                                },
                            });
                            break;
                        default:
                            break;
                    }
                });
            });
        });

        //The right-click event to display the upload file menu in the file container.
        filesBox.addEventListener("contextmenu", function (event) {
            event.preventDefault();
            // Remove any existing menu
            const existingMenu = document.querySelector("#menu-files-box");
            if (existingMenu) {
                existingMenu.remove();
            }

            // Create a new context menu
            const menuBox = document.createElement("div");
            menuBox.className = "menu-box";
            menuBox.id = "menu-files-box";
            menuBox.innerHTML = `
        <ul>
            <li id="create-folder" class="menu-item">
                <div class="item-icon">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                </div>
                <div class="item-text">
                    Upload file
                </div>
            </li>
        </ul>
        `;

            const x = event.clientX;
            const y = event.clientY;

            menuBox.style.position = "absolute"; // Set position to absolute for precise placement
            menuBox.style.top = `${y}px`; // Position menu vertically
            menuBox.style.left = `${x}px`; // Position menu horizontally

            document.body.appendChild(menuBox); // Append the menu to the folder item

            // Prevent clicks on the menu from closing it
            menuBox.addEventListener("click", function (event) {
                event.stopPropagation();
            });

            // Add click event listeners to menu items
            menuBox.querySelectorAll(".menu-item").forEach((item) => {
                item.addEventListener("click", function () {
                    switch (item.id) {
                        case "create-folder":
                            fileInput.click();
                            break;
                        default:
                            break;
                    }
                });
            });
        });

        // ====================================================
        // Drag and Drop Functionality
        // ====================================================

        // Handle drag start for image items
        // Set drag image
        // function handleDragStart(e) {
        //     const dragItem = e.target.closest(".image-item");
        //     if (dragItem) {
        //         const imageSrc = dragItem.getAttribute("data-image-src");
        //         e.dataTransfer.setData("text", imageSrc);

        //         const tempDiv = document.createElement("div");
        //         tempDiv.style.position = "absolute";
        //         tempDiv.style.top = "-9999px";
        //         tempDiv.style.left = "-9999px";

        //         const tempImage = document.createElement("img");
        //         tempImage.src = imageSrc;
        //         tempImage.style.transform = "rotate(-20deg)";
        //         tempImage.style.opacity = "1";
        //         tempImage.style.width = "80px";
        //         tempImage.style.boxShadow = "0 0 5px #ccc";
        //         tempImage.style.borderRadius = "3px";
        //         tempImage.style.padding = "4px";

        //         // Append the image to the temporary div
        //         tempDiv.appendChild(tempImage);

        //         // Append the temporary div to the body so that CSS effects can be applied
        //         document.body.appendChild(tempDiv);

        //         // Use the temporary div as the drag image
        //         e.dataTransfer.setDragImage(tempDiv, 10, 5);

        //         // Remove the temporary div from the DOM after a short delay
        //         setTimeout(() => document.body.removeChild(tempDiv), 0);
        //     }
        // }

        // Allow drop action
        function allowDrop(e) {
            e.preventDefault();
        }

        imageContainer.addEventListener("drop", handleDrop);

        function handleDrop(e) {
            const files = e.dataTransfer.files;

            const dataTransfer = new DataTransfer();
            // Thêm các file được kéo thả vào DataTransfer
            [...files].forEach((file) => dataTransfer.items.add(file));
            // Gán danh sách file từ DataTransfer vào thẻ input
            fileInput.files = dataTransfer.files;
            // Kích hoạt sự kiện `change` để xử lý như khi chọn file qua input
            fileInput.dispatchEvent(new Event("change"));
        }

        // ====================================================
        // Folder Drop & Confirmation Popup
        // ====================================================

        // Add drop event listeners to folders
        function dropItem(folderItems) {
            folderItems.forEach((folderItem) => {
                const folderIcon = folderItem.querySelector("#ic-folder");

                // folderItem.addEventListener("dragover", allowDrop);
                // folderItem.addEventListener("dragenter", (e) => {
                //     e.preventDefault();
                //     folderIcon.className = "fa-regular fa-folder-open"; // Open folder icon
                // });

                // folderItem.addEventListener("dragleave", (e) => {
                //     if (!folderItem.contains(e.relatedTarget)) {
                //         folderIcon.className = "fa-solid fa-folder"; // Closed folder icon
                //     }
                // });

                folderItem.addEventListener("drop", (e) => {
                    e.preventDefault();
                    folderIcon.className = "fa-solid fa-folder"; // Closed folder icon after drop

                    const data = e.dataTransfer.getData("text");
                    if (data) {
                        showConfirmModal({
                            title: "Are you sure?",
                            onYes: () => {

                                let urlObj = new URL(data);
                                let pathname = urlObj.pathname;

                                let lastSlashIndex = pathname.lastIndexOf("/");
                                let oldBase = pathname.substring(
                                    1,
                                    lastSlashIndex,
                                );
                                let fileName = pathname.substring(
                                    lastSlashIndex + 1,
                                );

                                let newBase = folderItem.getAttribute(
                                    "data-base-directory",
                                );

                                fetchMoveFile(oldBase, newBase, fileName);
                            },
                            onNo: () => {
                                console.log("Canceled!");
                            },
                        });
                    }
                });
            });
        }

        //Drag file
        // ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        //     imageContainer.addEventListener(eventName, preventDefaults, false);
        // });

        // function preventDefaults(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        // }

        // Thêm hiệu ứng khi kéo file vào khu vực
        // ["dragenter", "dragover"].forEach((eventName) => {
        //     imageContainer.addEventListener(
        //         eventName,
        //         () => imageContainer.classList.add("highlight"),
        //         false
        //     );
        // });

        ["dragleave", "drop"].forEach((eventName) => {
            imageContainer.addEventListener(
                eventName,
                () => imageContainer.classList.remove("highlight"),
                false,
            );
        });

        // ====================================================
        // Show Modal confirm
        // ====================================================

        // Function to show modal and assign actions to buttons
        function showConfirmModal({
            title = "Do you want to continue?",
            fileInfo,
            onYes,
            onNo,
        }) {
            // Create the HTML for the confirmation modal
            const existingMenu = document.querySelector(".menu-box");

            if (existingMenu) {
                existingMenu.remove();
            }

            const modalHTML = `
            <div class="confirm-popup">
                <div class="confirm-box">
                    <img class="confirm-box-img" src="/images/alert.png" alt="confirm box image">
                    <h3 class="confirm-title">${title}</h3>
                    ${
                        fileInfo
                            ? `<div class="preview-file-box">
                                    <img class="preview-file" src="${
                                        fileInfo.mine.startsWith("image/")
                                            ? fileInfo.src
                                            : "/images/image-default.png"
                                    }" alt="Preview Image">
                                </div>
                                <div class="file-info">
                                    <p><strong>File Name:</strong> ${fileInfo.name}</p>
                                    <p><strong>File Size:</strong> ${fileInfo.size}</p>
                                    <p><strong>File Type:</strong> ${fileInfo.type}</p>
                                </div>
                            `
                            : ""
                    }
                    <div class="confirm-button">
                        <button class="btnc btnc-no">No</button>
                        <button class="btnc btnc-yes">Yes</button>
                    </div>
                </div>
            </div>
             `;

            // Insert modal into the DOM
            const modalElement = document.getElementById("confirm-modal");
            modalElement.innerHTML = modalHTML;

            setTimeout(() => {
                if (!modalElement.innerHTML) {
                    modalElement.innerHTML = modalHTML;
                }
            }, 100);

            setTimeout(() => {
                // Event listener for the "Yes" button
                modalElement
                    .querySelector(".btnc-yes")
                    .addEventListener("click", () => {
                        if (typeof onYes === "function") onYes();
                        modalElement.innerHTML = ""; // Close modal
                    });

                // Event listener for the "No" button
                modalElement
                    .querySelector(".btnc-no")
                    .addEventListener("click", () => {
                        if (typeof onNo === "function") onNo();
                        modalElement.innerHTML = ""; // Close modal
                    });
            }, 150);
        }

        function showDialog(title) {
            const modalHTML = `
        <div class="confirm-popup">
            <div class="confirm-box">
                <img class="confirm-box-img" src="/images/alert.png" alt="">
                <h3 class="confirm-title">${title}</h3>
                <div class="confirm-button">
                <button class="btnc btnc-yes">Ok</button>
                </div>
            </div>
        </div>
        `;

            // Insert modal into the DOM
            const modalElement = document.getElementById("confirm-modal");
            modalElement.innerHTML = modalHTML;

            // Event listener for the "Yes" button
            modalElement
                .querySelector(".btnc-yes")
                .addEventListener("click", () => {
                    modalElement.innerHTML = ""; // Close modal
                });
        }

        function showInputModal({
            title = "New folder",
            onOk,
            onCancel,
            inputTitle,
            inputValue,
        }) {
            // Create the HTML for the input modal
            const existingMenu = document.querySelector(".menu-box");
            if (existingMenu) {
                existingMenu.remove();
            }
            const modalHTML = `
        <div class="file-name-popup">
            <div class="file-name-box">
                <h3 class="title">${title}</h3>
                <div class="file-name-input">
                    <label for="file-name-input">${inputTitle}</label>
                    <input id="file-name-input" autofocus type="text" name="file-name" value="${
                        inputValue ?? ""
                    }">
                </div>
                <div class="file-name-button">
                <button class="btn btn-no">Cancel</button>
                <button class="btn btn-yes">Ok</button>
                </div>
            </div>
        </div>
`;

            // Insert modal into the DOM
            const modalElement = document.getElementById("input-modal");
            modalElement.innerHTML = modalHTML;

            // Focus on the input element after modal is rendered
            const inputElement = document.getElementById("file-name-input");
            if (inputElement) {
                setTimeout(() => inputElement.focus(), 0);
                // Move the cursor to the end of the current value
                const valueLength = inputElement.value.length;
                inputElement.setSelectionRange(valueLength, valueLength);
            }

            // Event listener for the "Ok" button
            modalElement
                .querySelector(".btn-yes")
                .addEventListener("click", () => {
                    const folderName =
                        modalElement.querySelector("#file-name-input").value;
                    if (folderName && typeof onOk === "function") {
                        onOk(folderName);
                    }
                    modalElement.innerHTML = ""; // Close modal
                });

            modalElement
                .querySelector(".btn-yes")
                .addEventListener("keydown", (event) => {
                    if (event.key === "Enter") {
                        const folderName =
                            modalElement.querySelector(
                                "#file-name-input",
                            ).value;
                        if (folderName && typeof onOk === "function") {
                            onOk(folderName);
                        }
                        modalElement.innerHTML = ""; // Close modal
                    }
                });

            // Event listener for the "Cancel" button
            modalElement
                .querySelector(".btn-no")
                .addEventListener("click", () => {
                    if (typeof onCancel === "function") onCancel();
                    modalElement.innerHTML = ""; // Close modal
                });
        }
        // ====================================================
        // Extention Functions
        // ====================================================
        // Capacity unit conversion function
        function formatFileSize(size) {
            const units = ["B", "KB", "MB", "GB"];
            let i = 0;
            for (i = 0; size >= 1024 && i < units.length - 1; i++) {
                size /= 1024;
            }

            return Math.round(size * 100) / 100 + " " + units[i];
        }
    }
}
