/**
 * PPFTEMPLATE - Luxury Section Scroll Engine
 * "Mỗi lần lướt xuống là qua 1 phần" (One scroll = One section snap)
 * Fully compatible with native browser scrollbar & bronze luxury theme.
 */
(function () {
    'use strict';

    function initSectionScroll() {
        const container = document.querySelector('.home-snap-container');
        if (!container) return;

        const sections = Array.from(document.querySelectorAll('.home-section'));
        const footer = document.querySelector('.footer');
        if (footer) sections.push(footer);

        if (sections.length <= 1) return;

        document.documentElement.classList.add('is-home-snap');
        document.body.classList.add('is-home-snap');

        let isLocked = false;
        let lastScrollTime = 0;
        const COOLDOWN_MS = 800; // ms to complete smooth scroll before accepting next trigger

        function getCurrentIndex() {
            const currentScroll = window.scrollY;
            let closestIdx = 0;
            let minDistance = Infinity;

            sections.forEach((sec, idx) => {
                const top = sec.offsetTop;
                const distance = Math.abs(currentScroll - top);
                if (distance < minDistance) {
                    minDistance = distance;
                    closestIdx = idx;
                }
            });

            return closestIdx;
        }

        function scrollToSection(targetIdx) {
            if (targetIdx < 0 || targetIdx >= sections.length) return;

            isLocked = true;
            lastScrollTime = Date.now();

            const targetSection = sections[targetIdx];
            const targetTop = targetSection.offsetTop;

            window.scrollTo({
                top: targetTop,
                behavior: 'smooth'
            });

            // Update active state
            sections.forEach((s, idx) => {
                if (idx === targetIdx) {
                    s.classList.add('is-active-section');
                } else {
                    s.classList.remove('is-active-section');
                }
            });

            setTimeout(() => {
                isLocked = false;
            }, COOLDOWN_MS);
        }

        // Mouse Wheel & Trackpad Listener (One wheel tick = Exactly one section)
        function onWheel(e) {
            // Ignore if interacting with modals, search modal, language modal, or open mega menu
            if (e.target.closest('#search-modal, #cart-modal, #languageModal, .kb-mega-menu, .modal')) {
                return;
            }

            // Threshold to ignore micro-jitters
            if (Math.abs(e.deltaY) < 18) return;
            // Ignore horizontal gestures (e.g. 3D mirror hall horizontal dragging)
            if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;

            const now = Date.now();
            if (isLocked || (now - lastScrollTime < COOLDOWN_MS)) {
                e.preventDefault();
                return;
            }

            const currentIdx = getCurrentIndex();
            const currentSection = sections[currentIdx];

            // If current section is taller than viewport (e.g. on small screen), check bounds
            if (currentSection && currentSection !== footer) {
                const rect = currentSection.getBoundingClientRect();
                const atBottom = rect.bottom <= (window.innerHeight + 15);
                const atTop = rect.top >= -15;

                if (e.deltaY > 0 && !atBottom) {
                    return; // let user scroll down inside section
                }
                if (e.deltaY < 0 && !atTop) {
                    return; // let user scroll up inside section
                }
            }

            e.preventDefault();

            if (e.deltaY > 0) {
                // Scroll down -> Next section
                if (currentIdx < sections.length - 1) {
                    scrollToSection(currentIdx + 1);
                }
            } else {
                // Scroll up -> Previous section
                if (currentIdx > 0) {
                    scrollToSection(currentIdx - 1);
                }
            }
        }

        // Keyboard Navigation (ArrowDown, ArrowUp, PageDown, PageUp)
        function onKeyDown(e) {
            if (['INPUT', 'TEXTAREA'].includes(e.target.tagName) || e.target.isContentEditable) {
                return;
            }

            const currentIdx = getCurrentIndex();
            if (e.key === 'ArrowDown' || e.key === 'PageDown') {
                if (currentIdx < sections.length - 1) {
                    e.preventDefault();
                    scrollToSection(currentIdx + 1);
                }
            } else if (e.key === 'ArrowUp' || e.key === 'PageUp') {
                if (currentIdx > 0) {
                    e.preventDefault();
                    scrollToSection(currentIdx - 1);
                }
            } else if (e.key === 'Home') {
                e.preventDefault();
                scrollToSection(0);
            } else if (e.key === 'End') {
                e.preventDefault();
                scrollToSection(sections.length - 1);
            }
        }

        // Touch Swipe Navigation for Mobile
        let touchStartY = 0;
        let touchStartX = 0;
        let touchStartTime = 0;

        function onTouchStart(e) {
            if (e.touches.length !== 1) return;
            touchStartY = e.touches[0].clientY;
            touchStartX = e.touches[0].clientX;
            touchStartTime = Date.now();
        }

        function onTouchEnd(e) {
            if (e.changedTouches.length !== 1) return;
            const touchEndY = e.changedTouches[0].clientY;
            const touchEndX = e.changedTouches[0].clientX;
            const dy = touchEndY - touchStartY;
            const dx = touchEndX - touchStartX;
            const dt = Date.now() - touchStartTime;

            // Must be vertical swipe, fast enough or long enough
            if (Math.abs(dy) < 50 || Math.abs(dy) <= Math.abs(dx)) return;
            if (dt > 800) return;

            const now = Date.now();
            if (isLocked || (now - lastScrollTime < COOLDOWN_MS)) return;

            const currentIdx = getCurrentIndex();
            if (dy < 0) {
                // Swipe up -> Next section
                if (currentIdx < sections.length - 1) {
                    scrollToSection(currentIdx + 1);
                }
            } else {
                // Swipe down -> Previous section
                if (currentIdx > 0) {
                    scrollToSection(currentIdx - 1);
                }
            }
        }

        window.addEventListener('wheel', onWheel, { passive: false });
        window.addEventListener('keydown', onKeyDown);
        window.addEventListener('touchstart', onTouchStart, { passive: true });
        window.addEventListener('touchend', onTouchEnd, { passive: true });

        // Scroll to Top Button integration
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        if (scrollTopBtn) {
            scrollTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                scrollToSection(0);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSectionScroll);
    } else {
        initSectionScroll();
    }
})();
