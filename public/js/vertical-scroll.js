/**
 * Full-Page Vertical Scroll-Snap & Vertical Carousel Engine
 * Smooth full-viewport (100vh) slide navigation with wheel, trackpad, touch & keyboard control.
 */
(function () {
    'use strict';

    function initVerticalScroll() {
        const viewport = document.getElementById('vscrollViewport');
        const track = document.getElementById('vscrollTrack');
        if (!viewport || !track) return;

        const slides = Array.from(track.querySelectorAll('.vscroll-slide'));
        if (slides.length <= 1) return;

        document.documentElement.classList.add('is-vscroll-page');
        document.body.classList.add('is-vscroll-page');

        let currentIndex = 0;
        let isAnimating = false;
        let lastTriggerTime = 0;
        const ANIM_DURATION = 850; // ms
        const COOLDOWN_LOCK = 950;  // ms

        // Scroll to Top Button integration
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        if (scrollTopBtn) {
            scrollTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                goToSlide(0);
            });
        }

        function updateScrollTopBtn(idx) {
            if (!scrollTopBtn) return;
            if (idx > 0) {
                scrollTopBtn.style.display = 'flex';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        }

        function goToSlide(targetIdx) {
            if (targetIdx < 0 || targetIdx >= slides.length) return;
            if (targetIdx === currentIndex && !isAnimating) return;

            isAnimating = true;
            lastTriggerTime = Date.now();
            currentIndex = targetIdx;

            // Animate Track
            track.style.transform = `translate3d(0, -${targetIdx * 100}%, 0)`;

            // Update active states
            slides.forEach((s, idx) => {
                if (idx === targetIdx) {
                    s.classList.add('is-active');
                    // Reset inner scroll position when entering slide
                    s.scrollTop = 0;
                } else {
                    s.classList.remove('is-active');
                }
            });

            updateScrollTopBtn(targetIdx);

            setTimeout(() => {
                isAnimating = false;
            }, ANIM_DURATION);
        }

        function nextSlide() {
            if (currentIndex < slides.length - 1) {
                goToSlide(currentIndex + 1);
            }
        }

        function prevSlide() {
            if (currentIndex > 0) {
                goToSlide(currentIndex - 1);
            }
        }

        // Wheel & Trackpad Gesture Controller
        function onWheel(e) {
            const now = Date.now();

            // If animation is in progress or within cooldown, prevent default to block browser inertial queue
            if (isAnimating || now - lastTriggerTime < COOLDOWN_LOCK) {
                e.preventDefault();
                return;
            }

            // Ignore primarily horizontal gestures (e.g. dragging or swiping the 3D Mirror Hall)
            if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
                return;
            }

            // Delta threshold to ignore accidental micro-movements
            if (Math.abs(e.deltaY) < 16) {
                return;
            }

            const currentSlide = slides[currentIndex];
            if (currentSlide) {
                const maxScroll = currentSlide.scrollHeight - currentSlide.clientHeight;

                // If this section has scrollable overflow on smaller screens
                if (maxScroll > 10) {
                    const isAtBottom = currentSlide.scrollTop >= maxScroll - 6;
                    const isAtTop = currentSlide.scrollTop <= 6;

                    // Allow natural scroll within slide
                    if (e.deltaY > 0 && !isAtBottom) {
                        return;
                    }
                    if (e.deltaY < 0 && !isAtTop) {
                        return;
                    }
                }
            }

            e.preventDefault();

            if (e.deltaY > 0) {
                nextSlide();
            } else if (e.deltaY < 0) {
                prevSlide();
            }
        }

        // Touch & Swipe Controller for Mobile
        let touchStartY = 0;
        let touchStartX = 0;
        let isTouching = false;

        function onTouchStart(e) {
            if (e.touches.length !== 1) return;
            touchStartY = e.touches[0].clientY;
            touchStartX = e.touches[0].clientX;
            isTouching = true;
        }

        function onTouchMove(e) {
            if (!isTouching || e.touches.length !== 1) return;
            if (isAnimating) {
                e.preventDefault();
                return;
            }

            const currentSlide = slides[currentIndex];
            if (currentSlide) {
                const dy = e.touches[0].clientY - touchStartY;
                const maxScroll = currentSlide.scrollHeight - currentSlide.clientHeight;
                if (maxScroll > 10) {
                    const isAtBottom = currentSlide.scrollTop >= maxScroll - 6;
                    const isAtTop = currentSlide.scrollTop <= 6;
                    if (dy < 0 && !isAtBottom) return; // scroll down inside
                    if (dy > 0 && !isAtTop) return;    // scroll up inside
                }
            }
        }

        function onTouchEnd(e) {
            if (!isTouching) return;
            isTouching = false;

            const now = Date.now();
            if (isAnimating || now - lastTriggerTime < COOLDOWN_LOCK) return;

            const touchEndY = e.changedTouches[0].clientY;
            const touchEndX = e.changedTouches[0].clientX;
            const dy = touchEndY - touchStartY;
            const dx = touchEndX - touchStartX;

            // Must be predominantly vertical and exceed threshold
            if (Math.abs(dy) < 40 || Math.abs(dy) <= Math.abs(dx)) return;

            const currentSlide = slides[currentIndex];
            if (currentSlide) {
                const maxScroll = currentSlide.scrollHeight - currentSlide.clientHeight;
                if (maxScroll > 10) {
                    const isAtBottom = currentSlide.scrollTop >= maxScroll - 6;
                    const isAtTop = currentSlide.scrollTop <= 6;
                    if (dy < 0 && !isAtBottom) return;
                    if (dy > 0 && !isAtTop) return;
                }
            }

            if (dy < 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }

        // Keyboard Controller
        function onKeyDown(e) {
            // Ignore if user is inside an input field
            const target = e.target;
            if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.isContentEditable)) {
                return;
            }

            if (e.key === 'ArrowDown' || e.key === 'PageDown' || e.key === ' ') {
                e.preventDefault();
                nextSlide();
            } else if (e.key === 'ArrowUp' || e.key === 'PageUp') {
                e.preventDefault();
                prevSlide();
            } else if (e.key === 'Home') {
                e.preventDefault();
                goToSlide(0);
            } else if (e.key === 'End') {
                e.preventDefault();
                goToSlide(slides.length - 1);
            }
        }

        // Register Global Event Listeners
        window.addEventListener('wheel', onWheel, { passive: false });
        viewport.addEventListener('touchstart', onTouchStart, { passive: true });
        viewport.addEventListener('touchmove', onTouchMove, { passive: false });
        viewport.addEventListener('touchend', onTouchEnd, { passive: true });
        window.addEventListener('keydown', onKeyDown);

        // Resize handler to maintain correct snap position
        window.addEventListener('resize', () => {
            track.style.transform = `translate3d(0, -${currentIndex * 100}%, 0)`;
        });

        // Hash navigation support (e.g. #vslide-daily or #vslide-mirror)
        function checkHash() {
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const targetSlide = slides.findIndex(s => s.id === hash);
                if (targetSlide !== -1) {
                    goToSlide(targetSlide);
                    return true;
                }
            }
            return false;
        }

        window.addEventListener('hashchange', checkHash);

        // Initialize: if hash exists, jump to it; otherwise go to slide 0
        if (!checkHash()) {
            goToSlide(0);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVerticalScroll);
    } else {
        initVerticalScroll();
    }
})();
