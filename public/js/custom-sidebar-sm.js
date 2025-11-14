document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const customsb = document.getElementById('custom-sidebar');
    const sidebarFooter = document.getElementById('sidebar-footer');
    const mainContent = document.getElementById('mainContent');
    const openSidebar = document.getElementById('openSidebar');
    const closeSidebar = document.getElementById('closeSidebar');

    // Open sidebar
    openSidebar.addEventListener('click', () => {
        sidebar.style.animation = 'slideIn 0.3s forwards';
        sidebarFooter.style.animation = 'slideIn 0.3s forwards';
        customsb.style.zIndex = '10001';
        mainContent.style.display = 'none';
        closeSidebar.style.right = '-9%';
    });

    // Close sidebar
    closeSidebar.addEventListener('click', () => {
        sidebar.style.animation = 'slideOut 0.3s forwards';
        sidebarFooter.style.animation = 'slideOut 0.3s forwards';
        customsb.style.zIndex = '-1';
        mainContent.style.display = 'block';
        mainContent.style.right = '-13%';
        closeSidebar.style.right = '0%';
    });
});