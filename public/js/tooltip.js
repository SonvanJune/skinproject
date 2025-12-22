(function () {
  let tooltip = null;

  function createTooltip(text, x, y) {
    tooltip = document.createElement('div');
    tooltip.className = 'global-hover-tooltip';
    tooltip.innerText = text;

    Object.assign(tooltip.style, {
      position: 'fixed',
      left: x + 'px',
      top: y + 'px',
      transform: 'translateX(-50%)',
      background: '#111',
      color: '#fff',
      padding: '0.4rem 0.6rem',
      borderRadius: '0.4rem',
      fontSize: '0.85rem',
      lineHeight: '1.2',
      maxWidth: '300px',
      textAlign: 'center',
      zIndex: 99999,
      pointerEvents: 'none',
      opacity: '0',
      transition: 'opacity 0.15s ease, transform 0.15s ease'
    });

    document.body.appendChild(tooltip);

    requestAnimationFrame(() => {
      tooltip.style.opacity = '1';
      tooltip.style.transform = 'translateX(-50%) translateY(0.25rem)';
    });
  }

  function removeTooltip() {
    if (!tooltip) return;
    tooltip.remove();
    tooltip = null;
  }

  document.addEventListener('mouseover', function (e) {
    const target = e.target.closest('a, h1, h2, h3, h4, h5');
    if (!target) return;

    const text = target.innerText.trim();
    if (!text) return;

    const rect = target.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.bottom + 6;

    createTooltip(text, x, y);
  });

  document.addEventListener('mouseout', function (e) {
    if (!e.target.closest('a, h1, h2, h3')) return;
    removeTooltip();
  });
})();
