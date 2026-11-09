// Builders for the built-in 3D shapes managed from the admin "3D Models" resource.
// Every builder returns an Object3D sized to roughly fit a 2.5-unit box; animated
// parts expose `userData.update(time)` which ThreeStage calls each frame.
import * as THREE from 'three';
import { RoundedBoxGeometry } from 'three/examples/jsm/geometries/RoundedBoxGeometry.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

function glowColor(model) {
    const c = (model.emissive_color || '').toLowerCase();
    return c && c !== '#000000' ? model.emissive_color : model.color;
}

export function makeMaterial(model, overrides = {}) {
    const opacity = model.opacity ?? 1;
    return new THREE.MeshPhysicalMaterial({
        color: new THREE.Color(model.color || '#C9A227'),
        metalness: model.metalness ?? 0.8,
        roughness: model.roughness ?? 0.25,
        emissive: new THREE.Color(model.emissive_color || '#000000'),
        emissiveIntensity: model.emissive_intensity ?? 0,
        wireframe: !!model.wireframe,
        transparent: opacity < 1,
        opacity,
        clearcoat: 0.6,
        clearcoatRoughness: 0.2,
        ...overrides,
    });
}

function glowMaterial(model, intensity = 1.5) {
    return new THREE.MeshStandardMaterial({
        color: new THREE.Color(glowColor(model)),
        emissive: new THREE.Color(glowColor(model)),
        emissiveIntensity: intensity,
        roughness: 0.4,
        metalness: 0.1,
    });
}

function mesh(geometry, material) {
    return new THREE.Mesh(geometry, material);
}

function atom(model) {
    const group = new THREE.Group();
    group.add(mesh(new THREE.IcosahedronGeometry(0.42, 3), glowMaterial(model, 0.9)));

    const orbits = [];
    [0, Math.PI / 3, -Math.PI / 3].forEach((tilt, i) => {
        const orbit = new THREE.Group();
        orbit.rotation.set(Math.PI / 2 + tilt * 0.4, tilt, 0);
        orbit.add(mesh(new THREE.TorusGeometry(1.35, 0.018, 12, 160), makeMaterial(model, { wireframe: false })));
        const electron = mesh(new THREE.SphereGeometry(0.09, 24, 24), glowMaterial(model, 2.2));
        orbit.add(electron);
        orbits.push({ electron, speed: 1.4 + i * 0.45, phase: i * 2 });
        group.add(orbit);
    });

    group.userData.update = (t) => {
        orbits.forEach(({ electron, speed, phase }) => {
            const a = t * speed + phase;
            electron.position.set(Math.cos(a) * 1.35, Math.sin(a) * 1.35, 0);
        });
    };
    return group;
}

function planet(model) {
    const group = new THREE.Group();
    group.add(mesh(new THREE.SphereGeometry(0.95, 64, 64), makeMaterial(model)));

    const ring = mesh(
        new THREE.RingGeometry(1.3, 1.95, 128),
        makeMaterial(model, { side: THREE.DoubleSide, transparent: true, opacity: 0.55, wireframe: false, metalness: 0.9, roughness: 0.3 }),
    );
    ring.rotation.x = Math.PI / 2.3;
    group.add(ring);

    const moon = mesh(new THREE.SphereGeometry(0.16, 32, 32), glowMaterial(model, 1.2));
    group.add(moon);
    group.userData.update = (t) => {
        moon.position.set(Math.cos(t * 0.8) * 2.3, Math.sin(t * 0.8) * 0.6, Math.sin(t * 0.8) * 2.3);
    };
    return group;
}

function server(model) {
    const group = new THREE.Group();
    const leds = [];
    const units = 4;
    for (let i = 0; i < units; i++) {
        const y = (i - (units - 1) / 2) * 0.55;
        const unit = mesh(new RoundedBoxGeometry(2, 0.42, 1.3, 4, 0.06), makeMaterial(model, { clearcoat: 0.1, envMapIntensity: 0.35 }));
        unit.position.y = y;
        group.add(unit);

        for (let j = 0; j < 3; j++) {
            const led = mesh(new THREE.BoxGeometry(0.08, 0.08, 0.02), glowMaterial(model, 2));
            led.position.set(-0.75 + j * 0.16, y, 0.66);
            leds.push({ led, phase: Math.random() * Math.PI * 2, speed: 2 + Math.random() * 4 });
            group.add(led);
        }
        const bar = mesh(new THREE.BoxGeometry(0.9, 0.05, 0.02), glowMaterial(model, 0.8));
        bar.position.set(0.45, y, 0.66);
        group.add(bar);
    }
    group.userData.update = (t) => {
        leds.forEach(({ led, phase, speed }) => {
            led.material.emissiveIntensity = Math.sin(t * speed + phase) > 0.2 ? 2.4 : 0.2;
        });
    };
    return group;
}

function codeScreenTexture(color) {
    const canvas = document.createElement('canvas');
    canvas.width = 512;
    canvas.height = 320;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#0b0b0d';
    ctx.fillRect(0, 0, 512, 320);
    const lines = [
        ['Route::', 'apiResource', "('projects')"],
        ['class ', 'Project', ' extends Model'],
        ['  use ', 'HasTranslations', ';'],
        ['public function ', 'handle', '()'],
        ['  return ', 'response', '()->json()'],
        ['$query->', 'where', "('active')"],
        ['  ->with(', "'media'", ')'],
        ['  ->paginate(', '15', ');'],
    ];
    ctx.font = 'bold 22px monospace';
    lines.forEach((parts, i) => {
        let x = 24;
        parts.forEach((part, j) => {
            ctx.fillStyle = j === 1 ? color : j === 0 ? '#8a8476' : '#e9e4d5';
            ctx.fillText(part, x, 44 + i * 36);
            x += ctx.measureText(part).width;
        });
    });
    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    return texture;
}

function laptop(model) {
    const group = new THREE.Group();
    const base = mesh(new RoundedBoxGeometry(2.6, 0.1, 1.7, 3, 0.04), makeMaterial(model));
    base.position.y = -0.6;
    group.add(base);

    const hinge = new THREE.Group();
    hinge.position.set(0, -0.55, -0.82);
    hinge.rotation.x = -0.25;
    const lid = mesh(new RoundedBoxGeometry(2.6, 1.65, 0.07, 3, 0.03), makeMaterial(model));
    lid.position.y = 0.82;
    hinge.add(lid);
    const screen = mesh(
        new THREE.PlaneGeometry(2.4, 1.48),
        new THREE.MeshBasicMaterial({ map: codeScreenTexture(glowColor(model)), toneMapped: false }),
    );
    screen.position.set(0, 0.82, 0.04);
    hinge.add(screen);
    group.add(hinge);
    return group;
}

function codeBrackets(model) {
    const group = new THREE.Group();
    const bar = (len) => mesh(new RoundedBoxGeometry(len, 0.2, 0.28, 3, 0.08), makeMaterial(model));
    const angle = Math.PI / 4.5;
    const len = 1.05;

    const lt = new THREE.Group();
    const a = bar(len); a.rotation.z = angle; a.position.set(0, 0.32, 0);
    const b = bar(len); b.rotation.z = -angle; b.position.set(0, -0.32, 0);
    lt.add(a, b);
    lt.position.x = -1.35;

    const gt = lt.clone();
    gt.scale.x = -1;
    gt.position.x = 1.35;

    const slash = mesh(new RoundedBoxGeometry(1.9, 0.2, 0.28, 3, 0.08), glowMaterial(model, 0.7));
    slash.rotation.z = Math.PI / 2.6;

    group.add(lt, gt, slash);
    return group;
}

function database(model) {
    const group = new THREE.Group();
    for (let i = 0; i < 3; i++) {
        const disk = mesh(new THREE.CylinderGeometry(1, 1, 0.5, 64), makeMaterial(model));
        disk.position.y = (i - 1) * 0.62;
        group.add(disk);
        if (i < 2) {
            const ring = mesh(new THREE.TorusGeometry(1.005, 0.025, 8, 96), glowMaterial(model, 1.6));
            ring.rotation.x = Math.PI / 2;
            ring.position.y = (i - 1) * 0.62 + 0.31;
            group.add(ring);
        }
    }
    return group;
}

function glb(model) {
    const group = new THREE.Group();
    if (!model.model_url) return group;
    new GLTFLoader().load(
        model.model_url,
        (gltf) => {
            const object = gltf.scene;
            const box = new THREE.Box3().setFromObject(object);
            const size = box.getSize(new THREE.Vector3()).length() || 1;
            const center = box.getCenter(new THREE.Vector3());
            object.position.sub(center);
            const holder = new THREE.Group();
            holder.add(object);
            holder.scale.setScalar(3 / size);
            group.add(holder);
            const mixer = gltf.animations?.length ? new THREE.AnimationMixer(object) : null;
            gltf.animations?.forEach((clip) => mixer.clipAction(clip).play());
            let last = 0;
            group.userData.update = (t) => {
                if (mixer) mixer.update(t - last);
                last = t;
            };
        },
        undefined,
        (error) => console.warn('[3D] Failed to load model', model.model_url, error),
    );
    return group;
}

const BUILDERS = {
    torus_knot: (m) => mesh(new THREE.TorusKnotGeometry(0.95, 0.3, 260, 36), makeMaterial(m)),
    icosahedron: (m) => mesh(new THREE.IcosahedronGeometry(1.25, m.wireframe ? 1 : 0), makeMaterial(m, { flatShading: true })),
    octahedron: (m) => mesh(new THREE.OctahedronGeometry(1.25, 0), makeMaterial(m, { flatShading: true })),
    dodecahedron: (m) => mesh(new THREE.DodecahedronGeometry(1.2, 0), makeMaterial(m, { flatShading: true })),
    sphere: (m) => mesh(new THREE.SphereGeometry(1.2, 96, 96), makeMaterial(m)),
    torus: (m) => mesh(new THREE.TorusGeometry(1, 0.36, 64, 160), makeMaterial(m)),
    cube: (m) => mesh(new RoundedBoxGeometry(1.7, 1.7, 1.7, 8, 0.22), makeMaterial(m)),
    atom,
    planet,
    server,
    laptop,
    code: codeBrackets,
    database,
    glb,
};

export function buildShape(model) {
    const builder = BUILDERS[model.shape] || BUILDERS.torus_knot;
    return builder(model);
}

export function disposeObject(object) {
    object.traverse((child) => {
        child.geometry?.dispose?.();
        const materials = Array.isArray(child.material) ? child.material : [child.material];
        materials.forEach((material) => {
            if (!material) return;
            material.map?.dispose?.();
            material.dispose?.();
        });
    });
}
