/**
 * Confirm before deleting an item.
 *
 * @param {HTMLElement} element - The HTML element (typically a button) that triggered the function.
 */
function confirmDelete(element) {
    const form = element.closest("form");

    const modal = new bootstrap.Modal(
        document.getElementById("deleteConfirmationModal"),
    );
    modal.show();

    document.getElementById("confirmDeleteBtn").onclick = function () {
        form.submit();
        modal.hide();
    };
}
