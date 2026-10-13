// ==========================================================================
// THEME TOGGLE SYSTEM (Light / Dark Mode)
// ==========================================================================
(function () {
    const THEME_KEY = 'site_theme_mode';

    function getPreferredTheme() {
        const stored = localStorage.getItem(THEME_KEY);
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
        return 'dark'; // Default luxury dark mode
    }

    function applyTheme(theme, dispatch) {
        if (dispatch === undefined) dispatch = true;
        document.documentElement.setAttribute('data-theme', theme);
        if (document.body) {
            document.body.setAttribute('data-theme', theme);
        }
        localStorage.setItem(THEME_KEY, theme);

        const toggleBtns = document.querySelectorAll('.theme-toggle-btn');
        toggleBtns.forEach(btn => {
            btn.setAttribute('data-current-theme', theme);
            const title = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
            btn.setAttribute('aria-label', title);
            btn.setAttribute('title', title);
        });

        if (dispatch) {
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
        }
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || getPreferredTheme();
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next, true);
    }

    window.toggleTheme = toggleTheme;
    window.applyTheme = applyTheme;

    // Apply on load
    const initialTheme = getPreferredTheme();
    applyTheme(initialTheme, false);

    document.addEventListener('DOMContentLoaded', function () {
        applyTheme(getPreferredTheme(), false);
    });
})();
