window.addEventListener("selectedFilesUpdated", function () {
    selectedFiles = JSON.parse(sessionStorage.getItem("filesSection")) || [];
    renderFiles();
});

function generateSlug(title) {
    const slug = title
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d")
        .replace(/\s+/g, "-") 
        .replace(/[^\w\-]+/g, "");

    document.getElementById("postSlugLabel").value = slug;
}

const closeIcons = document.querySelectorAll(".remove-image");
closeIcons.forEach((closeIcon) => {
    closeIcon.addEventListener("click", function () {
        const previewItem = this.closest(".dz-preview");
        if (previewItem) {
            previewItem.remove();
        }
        document.getElementById("imagePathInput").value = "";
        document.getElementById("imageAltInput").value = "";
    });
});

window.onload = function () {
    if (document.getElementById("availabilitySwitch1").checked) {
        document.getElementById("release-in").style.display = "block";
        document.getElementById("releaseLabel").value = getPostReleaseDate;
        document.getElementById("availabilitySwitch1").value = 1;
    } else {
        document.getElementById("release-in").style.display = "none";
        document.getElementById("releaseLabel").value = getPostReleaseDate;
        document.getElementById("availabilitySwitch1").value = 0;
    }
};

document
    .getElementById("availabilitySwitch1")
    .addEventListener("change", function () {
        if (this.checked) {
            document.getElementById("release-in").style.display = "block";
            document.getElementById("releaseLabel").value = getPostReleaseDate;
            this.value = 1;
        } else {
            document.getElementById("release-in").style.display = "none";
            document.getElementById("releaseLabel").value = getPostReleaseDate;
            this.value = 0;
        }
    });

function resetRelease() {
    const input = document.getElementById("releaseLabel");
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const hour = String(now.getHours()).padStart(2, "0");
    const minute = String(now.getMinutes()).padStart(2, "0");
    const second = String(now.getSeconds()).padStart(2, "0");

    const formatted = `${year}-${month}-${day}T${hour}:${minute}:${second}`;
    input.value = formatted;
    return formatted;
}
