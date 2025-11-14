document.addEventListener("DOMContentLoaded", function() {
    const cards = document.querySelectorAll('.post-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('show'); // Thêm lớp để hiển thị
        }, index * 1000); // Đẩy thời gian xuất hiện cho từng sản phẩm
    });
});