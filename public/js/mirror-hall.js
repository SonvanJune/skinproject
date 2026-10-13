/**
 * Mirror Hall 3D Cylinder Carousel
 * Inspired by GetLayers Mirror Hall (3D rotunda, reflections, draggable inertia)
 */
(function () {
    'use strict';

    function initMirrorHall() {
        const stage = document.getElementById('mirrorHallStage');
        const track = document.getElementById('mirrorHallTrack');
        if (!stage || !track) return;

        let cards = Array.from(track.querySelectorAll('.mirror-hall-card'));
        if (cards.length === 0) return;

        // If card count is less than 6, duplicate cards to fill the 3D cylinder
        const minCards = 6;
        const originalCount = cards.length;
        if (cards.length > 0 && cards.length < minCards) {
            const repeatTimes = Math.ceil(minCards / cards.length);
            for (let r = 1; r < repeatTimes; r++) {
                for (let i = 0; i < originalCount; i++) {
                    const clone = cards[i].cloneNode(true);
                    clone.setAttribute('data-original-index', i);
                    track.appendChild(clone);
                }
            }
            cards = Array.from(track.querySelectorAll('.mirror-hall-card'));
        }

        const N = cards.length;
        const deltaTheta = (2 * Math.PI) / N;

        let targetAngle = 0;
        let currentAngle = 0;
        let velocity = 0;
        let isDragging = false;
        let startX = 0;
        let lastX = 0;
        let lastTime = 0;
        let activeIndex = 0;
        let autoPlayTimer = null;
        let isHovered = false;

        const currCounterEl = document.getElementById('mirrorHallCurr');
        const totalCounterEl = document.getElementById('mirrorHallTotal');
        const dotsContainer = document.getElementById('mirrorHallDots');
        const prevBtn = document.getElementById('mirrorHallPrev');
        const nextBtn = document.getElementById('mirrorHallNext');

        if (totalCounterEl) {
            totalCounterEl.textContent = String(originalCount).padStart(2, '0');
        }

        // Build dots for the original items
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < originalCount; i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'mirror-hall-dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                dot.dataset.index = i;
                dot.addEventListener('click', () => {
                    goToOriginalIndex(i);
                });
                dotsContainer.appendChild(dot);
            }
        }

        // Calculate dynamic radius based on card width and viewport
        function getRadius() {
            const w = window.innerWidth;
            if (w < 480) return 360;
            if (w < 768) return 460;
            if (w < 1200) return 620;
            return 760;
        }

        let radius = getRadius();
        window.addEventListener('resize', () => {
            radius = getRadius();
        });

        function updateHUD(origIdx) {
            if (currCounterEl) {
                currCounterEl.textContent = String(origIdx + 1).padStart(2, '0');
            }
            if (dotsContainer) {
                const dots = dotsContainer.querySelectorAll('.mirror-hall-dot');
                dots.forEach((d, idx) => {
                    if (idx === origIdx) {
                        d.classList.add('is-active');
                    } else {
                        d.classList.remove('is-active');
                    }
                });
            }
        }

        function normalizeAngle(ang) {
            let a = ang % (2 * Math.PI);
            if (a > Math.PI) a -= 2 * Math.PI;
            if (a < -Math.PI) a += 2 * Math.PI;
            return a;
        }

        // Snap targetAngle to nearest slot
        function snapToNearest() {
            const nearestIndex = Math.round(-targetAngle / deltaTheta);
            targetAngle = -nearestIndex * deltaTheta;
        }

        function rotateStep(direction) {
            // direction: 1 for next, -1 for prev
            targetAngle -= direction * deltaTheta;
        }

        function goToOriginalIndex(origIdx) {
            // Find which card corresponds to this origIdx closest to current angle
            let bestDelta = Infinity;
            let bestCardIdx = 0;

            cards.forEach((c, idx) => {
                const cOrigIdx = parseInt(c.dataset.originalIndex ?? c.dataset.index ?? (idx % originalCount), 10);
                if (cOrigIdx === origIdx) {
                    const cardBaseAngle = -idx * deltaTheta;
                    let diff = normalizeAngle(cardBaseAngle - targetAngle);
                    if (Math.abs(diff) < Math.abs(bestDelta)) {
                        bestDelta = diff;
                        bestCardIdx = idx;
                    }
                }
            });

            targetAngle += bestDelta;
        }

        // Card Click Handler
        cards.forEach((card, idx) => {
            card.dataset.index = idx;
            if (!card.dataset.originalIndex) {
                card.dataset.originalIndex = idx % originalCount;
            }

            card.addEventListener('click', (e) => {
                // If user was dragging significantly, ignore click
                if (Math.abs(startX - lastX) > 10) return;

                const phi = (idx * deltaTheta) + currentAngle;
                const norm = normalizeAngle(phi);

                // If not front card, rotate it to front
                if (Math.abs(norm) > 0.18) {
                    e.preventDefault();
                    targetAngle -= norm;
                }
            });
        });

        // Pointer Drag & Inertia
        function onPointerDown(e) {
            isDragging = true;
            startX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
            lastX = startX;
            lastTime = performance.now();
            velocity = 0;
            stage.classList.add('is-dragging');
            stopAutoplay();
        }

        function onPointerMove(e) {
            if (!isDragging) return;
            const clientX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
            const dx = clientX - lastX;
            const now = performance.now();
            const dt = Math.max(1, now - lastTime);

            lastX = clientX;
            lastTime = now;

            // Direct angular movement
            const angleDelta = (dx / radius) * 1.05;
            targetAngle += angleDelta;
            currentAngle += angleDelta;

            // Track velocity for inertia
            velocity = (angleDelta / dt) * 14;
        }

        function onPointerUp() {
            if (!isDragging) return;
            isDragging = false;
            stage.classList.remove('is-dragging');

            // Apply inertia momentum, then snap
            targetAngle += velocity * 4;
            snapToNearest();
            startAutoplay();
        }

        stage.addEventListener('mousedown', onPointerDown);
        window.addEventListener('mousemove', onPointerMove);
        window.addEventListener('mouseup', onPointerUp);

        stage.addEventListener('touchstart', onPointerDown, { passive: true });
        window.addEventListener('touchmove', onPointerMove, { passive: true });
        window.addEventListener('touchend', onPointerUp);
        window.addEventListener('touchcancel', onPointerUp);

        // Prev & Next Buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                rotateStep(-1);
                stopAutoplay();
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                rotateStep(1);
                stopAutoplay();
                startAutoplay();
            });
        }

        // Keyboard Controls
        stage.setAttribute('tabindex', '0');
        stage.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                rotateStep(-1);
                e.preventDefault();
            } else if (e.key === 'ArrowRight') {
                rotateStep(1);
                e.preventDefault();
            }
        });

        // Hover Pauses Autoplay
        stage.addEventListener('mouseenter', () => {
            isHovered = true;
            stopAutoplay();
        });

        stage.addEventListener('mouseleave', () => {
            isHovered = false;
            startAutoplay();
        });

        function startAutoplay() {
            stopAutoplay();
            if (isHovered) return;
            autoPlayTimer = setInterval(() => {
                if (!isDragging && !isHovered) {
                    rotateStep(1);
                }
            }, 4500);
        }

        function stopAutoplay() {
            if (autoPlayTimer) {
                clearInterval(autoPlayTimer);
                autoPlayTimer = null;
            }
        }

        startAutoplay();

        // Animation Loop
        let closestOrigIndex = 0;

        function animate() {
            requestAnimationFrame(animate);

            // Smooth interpolation
            const diff = targetAngle - currentAngle;
            if (isDragging) {
                // Dragging tracks directly
            } else {
                currentAngle += diff * 0.085;
            }

            let minAngleAbs = Infinity;
            let currentBestCard = null;

            cards.forEach((card, idx) => {
                const phi = (idx * deltaTheta) + currentAngle;
                const norm = normalizeAngle(phi);
                const cosVal = Math.cos(norm);
                const sinVal = Math.sin(norm);

                // Track closest to front
                const absAngle = Math.abs(norm);
                if (absAngle < minAngleAbs) {
                    minAngleAbs = absAngle;
                    currentBestCard = card;
                }

                // If card is too far behind the 3D ring, hide to save GPU
                if (cosVal < -0.38) {
                    card.style.display = 'none';
                    return;
                }
                card.style.display = 'block';

                const X = radius * sinVal;
                const Z = radius * (cosVal - 1);
                const rotY = -norm * (180 / Math.PI);

                // Dynamic scale & opacity based on depth
                const depthProgress = (cosVal + 0.38) / 1.38; // 0 to 1
                const scale = 0.72 + 0.28 * depthProgress;
                const opacity = Math.min(1, Math.max(0.18, depthProgress * 1.05));
                const zIndex = Math.round((cosVal + 1) * 200);

                card.style.transform = `translate3d(${X.toFixed(2)}px, 0px, ${Z.toFixed(2)}px) rotateY(${rotY.toFixed(2)}deg) scale(${scale.toFixed(3)})`;
                card.style.zIndex = zIndex;
                card.style.opacity = opacity.toFixed(3);
            });

            if (currentBestCard) {
                cards.forEach(c => c.classList.remove('is-active'));
                currentBestCard.classList.add('is-active');

                const origIdx = parseInt(
                    currentBestCard.dataset.originalIndex ?? currentBestCard.dataset.index ?? 0,
                    10
                );
                if (origIdx !== closestOrigIndex) {
                    closestOrigIndex = origIdx;
                    updateHUD(closestOrigIndex);
                }
            }
        }

        animate();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMirrorHall);
    } else {
        initMirrorHall();
    }
})();
