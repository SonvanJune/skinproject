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

  document.addEventListener('click', (e) => {
      if (customsb.style.zIndex !== '10001') return;

      if (
        sidebar.contains(e.target) ||
        openSidebar.contains(e.target)
      ) {
        return;
      }

      sidebar.style.animation = 'slideOut 0.3s forwards';
      sidebarFooter.style.animation = 'slideOut 0.3s forwards';
      customsb.style.zIndex = '-1';
      mainContent.style.display = 'block';
      mainContent.style.right = '-13%';
      closeSidebar.style.right = '0%';
    });
});


  const toggleBtn = document.getElementById('topbarsm-toggleSearch');
  const searchBar = document.getElementById('topbarsm-searchBar');
  const searchIcon = document.getElementById('topbarsm-search-icon');

  toggleBtn.addEventListener('click', () => {
    searchBar.classList.toggle('active');
    if(searchBar.classList.contains('active')) {
      searchIcon.classList.remove('bi-search');
      searchIcon.classList.add('bi-x');
    } else {
      searchIcon.classList.remove('bi-x');
      searchIcon.classList.add('bi-search');
    }
  });

  function topbarsmGoToSearchPage() {
   const searchBar = document.getElementById('topbarsm-searchBar');
   const searchInput = document.getElementById('topbarsm-searchInput');

   const baseUrl = searchBar.dataset.topbarSmSearchUrl;
   const searchTerm = searchInput.value;

   if (!searchTerm) return;

   const url = baseUrl + '?s=' + encodeURIComponent(searchTerm);
   window.location.href = url;
  }