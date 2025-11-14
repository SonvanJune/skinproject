document.querySelectorAll('.faq-q').forEach(q => {
      q.addEventListener('click', () => {
        const a = q.nextElementSibling;
        const t = q.querySelector('.toggle');
        if (a.style.display === 'block') {
          a.style.display = 'none';
          t.textContent = '+';
        } else {
          a.style.display = 'block';
          t.textContent = '–';
        }
      });
    });

    // Print
    document.getElementById('printBtn').addEventListener('click', () => window.print());

    // Contact
    document.getElementById('contactBtn').addEventListener('click', () => {
      window.location.href = 'mailto:support@yourshop.example?subject=H%E1%BB%97%20tr%E1%BB%A3%20v%E1%BB%81%20h%C6%B0%E1%BB%9Bng%20d%E1%BA%ABn';
    });

    // Lightbox
    document.querySelectorAll('.thumb img').forEach(img => {
      img.addEventListener('click', () => {
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.inset = 0;
        overlay.style.background = 'rgba(2,6,23,0.6)';
        overlay.style.display = 'grid';
        overlay.style.placeItems = 'center';
        overlay.style.zIndex = 9999;

        const clone = img.cloneNode();
        clone.style.maxWidth = '90%';
        clone.style.maxHeight = '90%';
        clone.style.cursor = 'zoom-out';
        clone.addEventListener('click', () => document.body.removeChild(overlay));

        overlay.appendChild(clone);
        document.body.appendChild(overlay);
      });
    });