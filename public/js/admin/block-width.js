let originalBody = "";

function checkAdminScreenSize() {
    const minWidth = 1024;
    const isSmallScreen = window.innerWidth < minWidth;

    if (isSmallScreen && document.body.dataset.mode !== "denied") {
        originalBody = document.body.innerHTML;

        document.body.innerHTML = `
                    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center; padding: 2rem;">
                        <div>
                            <h2 style="color: #dc3545;">Access Denied</h2>
                            <p style="font-size: 1.1rem;">Admin panel is only accessible on desktop or larger screens.</p>
                            <p style="color: gray;">Please switch to a desktop or enlarge your window.</p>
                        </div>
                    </div>
                `;
        document.body.dataset.mode = "denied";
    }

    if (!isSmallScreen && document.body.dataset.mode === "denied") {
        document.body.innerHTML = originalBody;
        document.body.dataset.mode = "normal";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    checkAdminScreenSize();
});

window.addEventListener("resize", function () {
    checkAdminScreenSize();
});
