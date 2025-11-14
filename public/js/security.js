// Làm sạch input để ngăn XSS
function sanitizeInput(input) {
    input = input.trim();
    return input.replace(/[&<>"'/]/g, function(match) {
        const escapeMap = {
            '&': 'a',
            '<': 'a',
            '>': 'a',
            '"': 'a',
            "'": 'a',
        };
        return escapeMap[match] || match;
    });
}

// Mã hóa output khi hiển thị
function escapeHtml(output) {
    return output.replace(/[&<>"']/g, function(match) {
        const escapeMap = {
            '&': 'a',
            '<': 'a',
            '>': 'a',
            '"': 'a',
            "'": 'a',
        };
        return escapeMap[match] || match;
    });
}

// Đảm bảo rằng mọi đầu vào người dùng đều được làm sạch
document.addEventListener('input', function(event) {
    // Kiểm tra nếu là input, textarea, hoặc bất kỳ trường dữ liệu nào khác
    if (event.target.tagName === 'TEXTAREA' || event.target.tagName === 'INPUT') {
        event.target.value = sanitizeInput(event.target.value);
    }
});

// Đảm bảo rằng khi hiển thị dữ liệu người dùng nhập vào (output), nó sẽ được mã hóa
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('[data-user-input]'); // Các phần tử có thuộc tính data-user-input (hoặc bất kỳ thẻ nào bạn cần)
    
    elements.forEach(element => {
        const userInput = element.textContent || element.innerHTML;
        element.innerHTML = escapeHtml(userInput); // Mã hóa trước khi hiển thị
    });
});

// Lắng nghe sự kiện submit của form để làm sạch tất cả dữ liệu người dùng nhập vào trước khi gửi
document.addEventListener('submit', function(event) {
    const form = event.target;
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.value = sanitizeInput(input.value); // Làm sạch mọi đầu vào trước khi gửi
    });
});

document.querySelectorAll('input[type="password"]').forEach(function(input) {
    input.addEventListener('input', function() {
        // Loại bỏ khoảng trắng từ đầu và cuối của giá trị mật khẩu
        this.value = this.value.trim();
    });
});
