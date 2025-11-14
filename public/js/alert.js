function closeAlert() {
    const pageOverlayes = document.querySelectorAll('.page-overlay');
    pageOverlayes.forEach(function(pay) {
        pay.style.display = 'none';
    });
}