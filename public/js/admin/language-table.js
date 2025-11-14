document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('languageForm');
    const saveBtn = document.getElementById('saveBtn');

    if (!form) return;

    const originalData = new FormData(form);

    form.addEventListener('input', function() {
        const currentData = new FormData(form);
        let changed = false;

        for (let [key, value] of currentData.entries()) {
            if (originalData.get(key) !== value) {
                changed = true;
                break;
            }
        }

        saveBtn.disabled = !changed;

        if (changed) {
            saveBtn.classList.remove('opacity-50');
            saveBtn.classList.add('opacity-100');
        } else {
            saveBtn.classList.add('opacity-50');
            saveBtn.classList.remove('opacity-100');
        }
    });
});