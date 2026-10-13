import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';

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

    const shaderUniforms = {
        uTime: { value: 0 },
        uResolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
        uMouse: { value: new THREE.Vector2(0, 0) },
        uScroll: { value: 0 }
    };

    function updateScrollDimensions() {
        maxScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
    }

    function init() {
        scene = new THREE.Scene();
        scene.background = new THREE.Color('#070709');

        camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 100);
        camera.position.set(0, 0, 3.0);
        scene.add(camera);

        // --- Liquid Bronze Wave Shader Plane (attached to camera) ---
        createBackgroundShader();

        // --- Forge Sparks ---
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
        updateThemeMode();

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
        canvas.style.opacity = isLight ? '0.18' : '1.0';
        canvas.style.transition = 'opacity 0.4s ease';
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

            float hash(float n) { return fract(sin(n) * 43758.5453123); }
            float noise(in vec3 x) {
                vec3 p = floor(x);
                vec3 f = fract(x);
                f = f * f * (3.0 - 2.0 * f);
                float n = p.x + p.y * 57.0 + 113.0 * p.z;
                return mix(mix(mix(hash(n +   0.0), hash(n +   1.0), f.x),
                               mix(hash(n +  57.0), hash(n +  58.0), f.x), f.y),
                           mix(mix(hash(n + 113.0), hash(n + 114.0), f.x),
                               mix(hash(n + 170.0), hash(n + 171.0), f.x), f.y), f.z);
            }

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
                float scrollDeform = scroll * 5.0;
                
                warpedUv.x += sin(uv.y * 2.5 + time * 0.2 + scrollDeform) * 0.35;
                warpedUv.y += cos(uv.x * 2.5 - time * 0.15 - scrollDeform * 0.8) * 0.35;
                
                warpedUv.x += sin(uv.y * 1.2 - time * 0.1 - scrollDeform * 1.5) * 0.25;
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
                
                // Palette: Molten Bronze (Scroll = 0.0) -> Deep Sapphire (Scroll = 1.0)
                vec3 c0_shadow = vec3(0.0010, 0.0006, 0.0004);
                vec3 c0_wave1  = vec3(0.085, 0.040, 0.015);
                vec3 c0_wave2  = vec3(0.050, 0.022, 0.008);
                vec3 c0_crest  = vec3(0.45, 0.30, 0.18);
                
                vec3 c1_shadow = vec3(0.0004, 0.0006, 0.0012);
                vec3 c1_wave1  = vec3(0.015, 0.035, 0.065);
                vec3 c1_wave2  = vec3(0.008, 0.020, 0.045);
                vec3 c1_crest  = vec3(0.18, 0.35, 0.55);
                
                float t = smoothstep(0.0, 1.0, scroll);
                vec3 colShadow = mix(c0_shadow, c1_shadow, t);
                vec3 colWave1  = mix(c0_wave1, c1_wave1, t);
                vec3 colWave2  = mix(c0_wave2, c1_wave2, t);
                vec3 colCrest  = mix(c0_crest, c1_crest, t);
                
                vec3 color = colShadow;
                color = mix(color, colWave2, smoothstep(-0.6, 0.2, waveField));
                color = mix(color, colWave1, smoothstep(0.0, 0.8, waveField));
                
                color += colCrest * crest * 1.4;
                
                float vignette = 1.0 - dot(uv, uv) * 0.15;
                color *= vignette;
                
                gl_FragColor = vec4(color, 1.0);
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
        gradient.addColorStop(0.25, 'rgba(255, 255, 255, 0.85)');
        gradient.addColorStop(0.6, 'rgba(255, 255, 255, 0.3)');
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

            if (Math.random() < 0.65) {
                // Molten fiery bronze/orange
                colors[i * 3] = 1.0;
                colors[i * 3 + 1] = 0.45 + Math.random() * 0.15;
                colors[i * 3 + 2] = 0.08 + Math.random() * 0.1;
            } else {
                // Cool sapphire / steel blue
                colors[i * 3] = 0.55 + Math.random() * 0.15;
                colors[i * 3 + 1] = 0.82 + Math.random() * 0.12;
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
            size: 0.03,
            vertexColors: true,
            transparent: true,
            opacity: 0.8,
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

        // Frame rate limiter (~36 FPS is completely smooth for ambient backgrounds while cutting GPU/CPU usage by 70%+)
        const elapsed = currentTime - lastRenderTime;
        if (elapsed < FRAME_INTERVAL) return;
        lastRenderTime = currentTime - (elapsed % FRAME_INTERVAL);

        const deltaTime = Math.min(clock.getDelta(), 0.1);

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
