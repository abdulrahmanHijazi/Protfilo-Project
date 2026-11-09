// Small three.js stage: renders the admin-configured models of one placement,
// with floating, auto-rotation, mouse-follow and drag-to-rotate per model.
import * as THREE from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { buildShape, disposeObject } from './shapes';

export function createStage(container, options = {}) {
    const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.05;
    renderer.domElement.style.touchAction = 'pan-y';
    container.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const pmrem = new THREE.PMREMGenerator(renderer);
    const envTexture = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    scene.environment = envTexture;

    const camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
    camera.position.set(0, 0, options.cameraZ ?? 6.5);

    const accent = new THREE.Color(options.lightColor || '#C9A227');
    scene.add(new THREE.AmbientLight(0xffffff, 0.35));
    const key = new THREE.DirectionalLight(accent, 2.2);
    key.position.set(4, 5, 5);
    scene.add(key);
    const rim = new THREE.PointLight(0x8fb4ff, 18, 20);
    rim.position.set(-5, -2, -3);
    scene.add(rim);
    const fill = new THREE.PointLight(accent, 10, 16);
    fill.position.set(0, -3, 4);
    scene.add(fill);

    const root = new THREE.Group();
    scene.add(root);

    let particles = null;
    if (options.particles) {
        const count = 700;
        const positions = new Float32Array(count * 3);
        for (let i = 0; i < count; i++) {
            const r = 3 + Math.random() * 7;
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.acos(2 * Math.random() - 1);
            positions[i * 3] = r * Math.sin(phi) * Math.cos(theta);
            positions[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
            positions[i * 3 + 2] = r * Math.cos(phi) - 3;
        }
        const geometry = new THREE.BufferGeometry();
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particles = new THREE.Points(
            geometry,
            new THREE.PointsMaterial({
                color: new THREE.Color(options.particlesColor || '#E6C65A'),
                size: 0.035,
                transparent: true,
                opacity: 0.75,
                depthWrite: false,
                blending: THREE.AdditiveBlending,
            }),
        );
        scene.add(particles);
    }

    /** @type {{ wrapper: THREE.Group, inner: THREE.Object3D, model: object, base: THREE.Vector3, phase: number }[]} */
    let items = [];

    function setModels(models = []) {
        items.forEach(({ wrapper }) => {
            root.remove(wrapper);
            disposeObject(wrapper);
        });
        items = models.map((model, index) => {
            const wrapper = new THREE.Group();
            const inner = buildShape(model);
            wrapper.add(inner);
            const [x = 0, y = 0, z = 0] = model.position || [];
            wrapper.position.set(x, y, z);
            wrapper.scale.setScalar(model.scale ?? 1);
            root.add(wrapper);
            return { wrapper, inner, model, base: new THREE.Vector3(x, y, z), phase: index * 1.7 };
        });
        container.style.cursor = models.some((m) => m.draggable) ? 'grab' : '';
    }

    // Pointer: mouse-follow everywhere on the window, drag-rotate on the canvas.
    const pointer = new THREE.Vector2();
    const drag = { active: false, x: 0, y: 0, rotX: 0, rotY: 0, velX: 0, velY: 0 };

    function onPointerMove(event) {
        pointer.x = (event.clientX / window.innerWidth) * 2 - 1;
        pointer.y = -(event.clientY / window.innerHeight) * 2 + 1;
        if (!drag.active) return;
        const dx = event.clientX - drag.x;
        const dy = event.clientY - drag.y;
        drag.x = event.clientX;
        drag.y = event.clientY;
        drag.velY = dx * 0.008;
        drag.velX = dy * 0.008;
        drag.rotY += drag.velY;
        drag.rotX += drag.velX;
    }
    function onPointerDown(event) {
        if (!items.some(({ model }) => model.draggable)) return;
        drag.active = true;
        drag.x = event.clientX;
        drag.y = event.clientY;
        container.style.cursor = 'grabbing';
    }
    function onPointerUp() {
        if (!drag.active) return;
        drag.active = false;
        container.style.cursor = 'grab';
    }
    renderer.domElement.addEventListener('pointerdown', onPointerDown);
    window.addEventListener('pointermove', onPointerMove, { passive: true });
    window.addEventListener('pointerup', onPointerUp);

    function resize() {
        const width = container.clientWidth || 1;
        const height = container.clientHeight || 1;
        renderer.setSize(width, height, false);
        renderer.domElement.style.width = '100%';
        renderer.domElement.style.height = '100%';
        camera.aspect = width / height;
        // Keep models inside narrow (mobile) viewports.
        camera.position.z = (options.cameraZ ?? 6.5) * (camera.aspect < 0.9 ? 1.35 : 1);
        camera.updateProjectionMatrix();
    }
    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(container);
    resize();

    let visible = true;
    const visibilityObserver = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
    });
    visibilityObserver.observe(container);

    const clock = new THREE.Clock();
    let frame = 0;
    const motion = reduceMotion ? 0.15 : 1;

    function tick() {
        frame = requestAnimationFrame(tick);
        if (!visible || document.hidden) {
            clock.getDelta();
            return;
        }
        const dt = Math.min(clock.getDelta(), 0.05);
        const t = clock.elapsedTime;

        if (!drag.active) {
            drag.velX *= 0.94;
            drag.velY *= 0.94;
            drag.rotX += drag.velX;
            drag.rotY += drag.velY;
        }

        items.forEach(({ wrapper, inner, model, base, phase }) => {
            const speed = (model.rotation_speed ?? 0.4) * motion;
            inner.rotation.y += speed * dt;
            inner.rotation.x += speed * dt * 0.35;
            inner.userData.update?.(t);

            const float = (model.float_intensity ?? 0.3) * motion;
            wrapper.position.y = base.y + Math.sin(t * 1.1 + phase) * float * 0.35;
            wrapper.position.x = base.x + Math.cos(t * 0.7 + phase) * float * 0.12;

            const follow = model.mouse_follow ?? 0.5;
            const targetY = pointer.x * follow * 0.6 + (model.draggable ? drag.rotY : 0);
            const targetX = -pointer.y * follow * 0.4 + (model.draggable ? drag.rotX : 0);
            wrapper.rotation.y += (targetY - wrapper.rotation.y) * 0.08;
            wrapper.rotation.x += (targetX - wrapper.rotation.x) * 0.08;
        });

        if (particles) {
            particles.rotation.y += dt * 0.02 * motion;
            particles.rotation.x = pointer.y * 0.05;
        }
        renderer.render(scene, camera);
    }
    tick();

    return {
        setModels,
        dispose() {
            cancelAnimationFrame(frame);
            resizeObserver.disconnect();
            visibilityObserver.disconnect();
            renderer.domElement.removeEventListener('pointerdown', onPointerDown);
            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerup', onPointerUp);
            items.forEach(({ wrapper }) => disposeObject(wrapper));
            if (particles) disposeObject(particles);
            envTexture.dispose();
            pmrem.dispose();
            renderer.dispose();
            renderer.domElement.remove();
        },
    };
}

export function webglAvailable() {
    try {
        const canvas = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (canvas.getContext('webgl2') || canvas.getContext('webgl')));
    } catch (e) {
        return false;
    }
}
