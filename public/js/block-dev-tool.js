document.addEventListener("keydown", function (e) {
    if (
        e.key === "F12" ||
        (e.ctrlKey && e.shiftKey && ["I", "C", "J"].includes(e.key)) ||
        (e.ctrlKey && ["U", "S"].includes(e.key.toUpperCase()))
    ) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
});

document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
});
