import * as THREE from './three.module.js';

(function () {
    const canvas = document.getElementById('bronze-bg-canvas');
    if (!canvas) return;

    let scene, camera, renderer;
    let bgMaterial, sparkParticles;
    const sparkCount = 60;
    const sparkData = [];
    const clock = new THREE.Clock();

    let mouseX = 0, mouseY = 0, targetMouseX = 0, targetMouseY = 0;
    let currentScroll = 0;
    let maxScroll = 1;
    let lastRenderTime = 0;
    const TARGET_FPS = 36;
    const FRAME_INTERVAL = 1000 / TARGET_FPS;

    let targetThemeValue = document.documentElement.getAttribute('data-theme') === 'light' ? 1.0 : 0.0;

    const shaderUniforms = {
        uTime: { value: 0 },
        uResolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
        uMouse: { value: new THREE.Vector2(0, 0) },
        uScroll: { value: 0 },
        uTheme: { value: targetThemeValue } // 0.0 for Dark, 1.0 for Light
    };

    function updateScrollDimensions() {
        maxScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
    }

    function init() {
        scene = new THREE.Scene();
        const isLight = document.documentElement.getAttribute('data-theme') === 'light';
        scene.background = isLight ? new THREE.Color('#ede8df') : new THREE.Color('#070709');

        camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 100);
        camera.position.set(0, 0, 3.0);
        scene.add(camera);

        // --- Liquid Bronze / Champagne Pearl Wave Shader Plane (attached to camera) ---
        createBackgroundShader();

        // --- Forge Sparks / Diamond Mica Dust ---
        createSparks();

        // --- WebGL Renderer (Optimized for minimal GPU consumption) ---
        renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            antialias: false,
            alpha: false,
            powerPreference: 'low-power'
        });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(1.0);

        updateScrollDimensions();
        window.addEventListener('resize', onWindowResize, { passive: true });
        window.addEventListener('mousemove', onMouseMove, { passive: true });
        window.addEventListener('scroll', updateScrollDimensions, { passive: true });
        window.addEventListener('themeChanged', updateThemeMode);
        document.addEventListener('visibilitychange', onVisibilityChange);

        // Pre-render 1st frame to ensure WebGL buffer is ready before revealing canvas
        renderer.render(scene, camera);

        // Gracefully fade in luxury background over ambient underlay
        requestAnimationFrame(() => {
            canvas.classList.add('is-ready');
            updateThemeMode();
        });

        requestAnimationFrame(animate);
    }

    function onVisibilityChange() {
        if (document.hidden) {
            clock.stop();
        } else {
            clock.start();
        }
    }

    function updateThemeMode() {
        const isLight = document.documentElement.getAttribute('data-theme') === 'light';
        targetThemeValue = isLight ? 1.0 : 0.0;
        if (scene) {
            scene.background = isLight ? new THREE.Color('#ede8df') : new THREE.Color('#070709');
        }
        if (sparkParticles) {
            sparkParticles.material.opacity = isLight ? 0.12 : 0.85;
        }
        if (canvas.classList.contains('is-ready')) {
            canvas.style.opacity = '1.0';
        }
    }

    function createBackgroundShader() {
        const vertexShader = `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
        `;

        const fragmentShader = `
            varying vec2 vUv;
            uniform float uTime;
            uniform vec2 uResolution;
            uniform vec2 uMouse;
            uniform float uScroll;
            uniform float uTheme; // 0.0 = Midnight Bronze, 1.0 = Champagne Pearl & Ivory Gold

            void main() {
                vec2 uv = (gl_FragCoord.xy - 0.5 * uResolution.xy) / uResolution.y;
                float aspect = uResolution.x / uResolution.y;
                
                float time = uTime * 0.08;
                float scroll = uScroll;
                
                float angle1 = 0.6;
                float angle2 = -0.7;
                float angle3 = 1.2;
                
                float freq1 = 2.4;
                float freq2 = 3.2;
                float freq3 = 4.0;
                
                vec2 warpedUv = uv;
                float scrollDeform = scroll * 4.5;
                
                warpedUv.x += sin(uv.y * 2.5 + time * 0.22 + scrollDeform) * 0.35;
                warpedUv.y += cos(uv.x * 2.5 - time * 0.16 - scrollDeform * 0.8) * 0.35;
                
                warpedUv.x += sin(uv.y * 1.2 - time * 0.12 - scrollDeform * 1.4) * 0.25;
                warpedUv.y += cos(uv.x * 1.2 + time * 0.18 + scrollDeform * 1.2) * 0.25;
                
                vec2 scrollDrift = vec2(scroll * 0.04, -scroll * 0.02);
                vec2 mouseShift = vec2(uMouse.x * aspect * 0.05, uMouse.y * 0.05);
                warpedUv += scrollDrift + mouseShift;
                
                vec2 dir1 = vec2(cos(angle1), sin(angle1));
                vec2 dir2 = vec2(cos(angle2), sin(angle2));
                vec2 dir3 = vec2(cos(angle3), sin(angle3));
                
                float w1 = sin(dot(warpedUv, dir1) * freq1 + time * 1.0);
                float w2 = cos(dot(warpedUv, dir2) * freq2 - time * 1.4 + w1 * 0.4);
                float w3 = sin(dot(warpedUv, dir3) * freq3 + time * 1.8 + w2 * 0.5);
                
                float waveField = w1 * 0.50 + w2 * 0.35 + w3 * 0.15;
                
                float wideSheen = pow(max(0.0, 1.0 - abs(waveField - 0.1)), 2.5);
                float crispSpecular = pow(max(0.0, 1.0 - abs(waveField - 0.15)), 8.0);
                float crest = wideSheen * 0.5 + crispSpecular * 0.9;
                
                float t = smoothstep(0.0, 1.0, scroll);

                // --- 1. DARK MODE: Molten Bronze (Scroll = 0) -> Deep Sapphire (Scroll = 1) ---
                vec3 dark_c0_shadow = vec3(0.0010, 0.0006, 0.0004);
                vec3 dark_c0_wave1  = vec3(0.085, 0.040, 0.015);
                vec3 dark_c0_wave2  = vec3(0.050, 0.022, 0.008);
                vec3 dark_c0_crest  = vec3(0.45, 0.30, 0.18);
                
                vec3 dark_c1_shadow = vec3(0.0004, 0.0006, 0.0012);
                vec3 dark_c1_wave1  = vec3(0.015, 0.035, 0.065);
                vec3 dark_c1_wave2  = vec3(0.008, 0.020, 0.045);
                vec3 dark_c1_crest  = vec3(0.18, 0.35, 0.55);
                
                vec3 dark_colShadow = mix(dark_c0_shadow, dark_c1_shadow, t);
                vec3 dark_colWave1  = mix(dark_c0_wave1, dark_c1_wave1, t);
                vec3 dark_colWave2  = mix(dark_c0_wave2, dark_c1_wave2, t);
                vec3 dark_colCrest  = mix(dark_c0_crest, dark_c1_crest, t);
                
                vec3 dark_color = dark_colShadow;
                dark_color = mix(dark_color, dark_colWave2, smoothstep(-0.6, 0.2, waveField));
                dark_color = mix(dark_color, dark_colWave1, smoothstep(0.0, 0.8, waveField));
                dark_color += dark_colCrest * crest * 1.4;
                dark_color *= (1.0 - dot(uv, uv) * 0.15);
                // --- 2. LIGHT MODE: Soft Cashmere Linen & Gentle Muted Champagne Silk ---
                // Calming, eye-friendly warm tones (No blinding specular, soft satin sheen)
                vec3 light_c0_base   = vec3(0.932, 0.915, 0.885); // Warm Cashmere Linen (#eee7dc)
                vec3 light_c0_wave1  = vec3(0.835, 0.775, 0.695); // Muted Soft Bronze Silk
                vec3 light_c0_wave2  = vec3(0.890, 0.850, 0.795); // Soft Warm Sand
                vec3 light_c0_crest  = vec3(0.965, 0.950, 0.925); // Gentle Pearl Highlight
                vec3 light_c0_gold   = vec3(0.740, 0.610, 0.440); // Soft Antique Bronze Ribbon

                // Scrolled: Soft Slate Cashmere & Muted Sapphire Silk
                vec3 light_c1_base   = vec3(0.915, 0.922, 0.932);
                vec3 light_c1_wave1  = vec3(0.785, 0.830, 0.875);
                vec3 light_c1_wave2  = vec3(0.855, 0.880, 0.910);
                vec3 light_c1_crest  = vec3(0.955, 0.965, 0.975);
                vec3 light_c1_gold   = vec3(0.480, 0.600, 0.720);

                vec3 light_colBase  = mix(light_c0_base, light_c1_base, t);
                vec3 light_colWave1 = mix(light_c0_wave1, light_c1_wave1, t);
                vec3 light_colWave2 = mix(light_c0_wave2, light_c1_wave2, t);
                vec3 light_colCrest = mix(light_c0_crest, light_c1_crest, t);
                vec3 light_colGold  = mix(light_c0_gold, light_c1_gold, t);

                vec3 light_color = light_colBase;
                light_color = mix(light_color, light_colWave2, smoothstep(-0.6, 0.2, waveField) * 0.45);
                light_color = mix(light_color, light_colWave1, smoothstep(0.0, 0.8, waveField) * 0.35);
                light_color = mix(light_color, light_colGold, wideSheen * 0.20);
                light_color = mix(light_color, light_colCrest, crispSpecular * 0.30);
                light_color *= (1.0 - dot(uv, uv) * 0.08);

                // Smoothly blend between dark & light mode
                vec3 finalColor = mix(dark_color, light_color, uTheme);
                gl_FragColor = vec4(finalColor, 1.0);
            }
        `;

        bgMaterial = new THREE.ShaderMaterial({
            vertexShader: vertexShader,
            fragmentShader: fragmentShader,
            uniforms: shaderUniforms,
            depthWrite: false,
            depthTest: false
        });

        const bgGeometry = new THREE.PlaneGeometry(30, 30);
        const bgMesh = new THREE.Mesh(bgGeometry, bgMaterial);
        bgMesh.position.set(0.0, 0.0, -8.0);
        bgMesh.renderOrder = -10;
        camera.add(bgMesh);
    }

    function createSparkTexture() {
        const c = document.createElement('canvas');
        c.width = 16;
        c.height = 16;
        const ctx = c.getContext('2d');
        const gradient = ctx.createRadialGradient(8, 8, 0, 8, 8, 8);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
        gradient.addColorStop(0.25, 'rgba(255, 235, 180, 0.85)');
        gradient.addColorStop(0.6, 'rgba(215, 155, 65, 0.35)');
        gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, 16, 16);
        return new THREE.CanvasTexture(c);
    }

    function createSparks() {
        const geometry = new THREE.BufferGeometry();
        const positions = new Float32Array(sparkCount * 3);
        const colors = new Float32Array(sparkCount * 3);

        for (let i = 0; i < sparkCount; i++) {
            const x = (Math.random() - 0.5) * 8.0;
            const y = (Math.random() - 0.5) * 6.0;
            const z = (Math.random() - 0.5) * 6.0;
            positions[i * 3] = x;
            positions[i * 3 + 1] = y;
            positions[i * 3 + 2] = z;

            if (Math.random() < 0.70) {
                // Molten fiery bronze/orange & warm 24K gold
                colors[i * 3]     = 1.0;
                colors[i * 3 + 1] = 0.55 + Math.random() * 0.25;
                colors[i * 3 + 2] = 0.12 + Math.random() * 0.15;
            } else {
                // Cool sapphire / diamond pearl
                colors[i * 3]     = 0.65 + Math.random() * 0.25;
                colors[i * 3 + 1] = 0.85 + Math.random() * 0.15;
                colors[i * 3 + 2] = 1.0;
            }

            sparkData.push({
                speedX: (Math.random() - 0.5) * 0.3,
                speedY: 0.12 + Math.random() * 0.25,
                speedZ: (Math.random() - 0.5) * 0.3,
                swaySpeed: 0.5 + Math.random() * 1.2,
                swayRadius: 0.04 + Math.random() * 0.1,
                phase: Math.random() * Math.PI * 2
            });
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        const material = new THREE.PointsMaterial({
            size: 0.032,
            vertexColors: true,
            transparent: true,
            opacity: 0.85,
            blending: THREE.AdditiveBlending,
            depthWrite: false,
            map: createSparkTexture()
        });

        sparkParticles = new THREE.Points(geometry, material);
        scene.add(sparkParticles);
    }

    function onMouseMove(event) {
        targetMouseX = (event.clientX / window.innerWidth) * 2 - 1;
        targetMouseY = (event.clientY / window.innerHeight) * 2 - 1;
    }

    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(1.0);
        shaderUniforms.uResolution.value.set(window.innerWidth, window.innerHeight);
        updateScrollDimensions();
    }

    function animate(currentTime) {
        requestAnimationFrame(animate);

        // Do not render if tab is in the background
        if (document.hidden) return;

        // Frame rate limiter
        const elapsed = currentTime - lastRenderTime;
        if (elapsed < FRAME_INTERVAL) return;
        lastRenderTime = currentTime - (elapsed % FRAME_INTERVAL);

        const deltaTime = Math.min(clock.getDelta(), 0.1);

        // Smoothly transition theme uniform if switching modes
        if (Math.abs(targetThemeValue - shaderUniforms.uTheme.value) > 0.005) {
            shaderUniforms.uTheme.value += (targetThemeValue - shaderUniforms.uTheme.value) * 0.1;
        } else {
            shaderUniforms.uTheme.value = targetThemeValue;
        }

        // Smoothly adjust spark opacity depending on theme (soft 0.12 in light mode to prevent glare)
        if (sparkParticles) {
            sparkParticles.material.opacity = THREE.MathUtils.lerp(0.85, 0.12, shaderUniforms.uTheme.value);
        }

        // Read window.scrollY without triggering layout thrashing
        const scrollTop = window.scrollY !== undefined ? window.scrollY
            : (window.pageYOffset !== undefined ? window.pageYOffset : 0);
        const targetScroll = maxScroll > 0 ? scrollTop / maxScroll : 0;

        currentScroll += (targetScroll - currentScroll) * 0.04;
        mouseX += (targetMouseX - mouseX) * 0.05;
        mouseY += (targetMouseY - mouseY) * 0.05;

        // Sparks physics with scroll turbulence (optimized for 60 particles)
        if (sparkParticles) {
            const positions = sparkParticles.geometry.attributes.position.array;
            const time = clock.getElapsedTime();
            const scrollVelocity = Math.abs(targetScroll - currentScroll);
            const speedMultiplier = 1.0 + scrollVelocity * 6.0;

            for (let i = 0; i < sparkCount; i++) {
                const idx = i * 3;
                const data = sparkData[i];
                positions[idx]     += data.speedX * deltaTime * speedMultiplier;
                positions[idx + 1] += data.speedY * deltaTime * speedMultiplier;
                positions[idx + 2] += data.speedZ * deltaTime * speedMultiplier;

                positions[idx]     += Math.sin(time * data.swaySpeed + data.phase) * data.swayRadius * deltaTime;
                positions[idx + 2] += Math.cos(time * data.swaySpeed + data.phase) * data.swayRadius * deltaTime;

                if (positions[idx + 1] > 3.5 || Math.abs(positions[idx]) > 4.5 || Math.abs(positions[idx + 2]) > 4.0) {
                    positions[idx + 1] = -3.2;
                    positions[idx]     = (Math.random() - 0.5) * 6.0;
                    positions[idx + 2] = (Math.random() - 0.5) * 5.0;
                }
            }
            sparkParticles.geometry.attributes.position.needsUpdate = true;
        }

        // Camera gentle drift from mouse
        camera.position.x = mouseX * 0.15;
        camera.position.y = -mouseY * 0.1;

        // Update uniforms
        if (shaderUniforms) {
            shaderUniforms.uTime.value = clock.getElapsedTime();
            shaderUniforms.uMouse.value.set(mouseX, -mouseY);
            shaderUniforms.uScroll.value = currentScroll;
        }

        renderer.render(scene, camera);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
