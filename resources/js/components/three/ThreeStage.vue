<script setup>
import { ref, onMounted, onBeforeUnmount, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Hand } from 'lucide-vue-next';

// Renders the 3D models configured for one placement in the admin panel (Portfolio → 3D Models).
const props = defineProps({
    placement: { type: String, required: true },
    particles: { type: Boolean, default: false },
    cameraZ: { type: Number, default: 6.5 },
    hint: { type: Boolean, default: true },
});

const { t } = useI18n();
const page = usePage();
const container = ref(null);
const failed = ref(false);
let stage = null;

const settings = computed(() => page.props.settings || {});
const models = computed(() => page.props.scenes?.[props.placement] ?? []);
const draggable = computed(() => models.value.some((m) => m.draggable));

onMounted(async () => {
    if (!models.value.length) return;
    const { createStage, webglAvailable } = await import('@/lib/three/engine.js');
    if (!container.value) return;
    if (!webglAvailable()) {
        failed.value = true;
        return;
    }
    stage = createStage(container.value, {
        particles: props.particles && settings.value.scene_particles !== false,
        particlesColor: settings.value.particles_color,
        lightColor: settings.value.scene_bloom_color,
        cameraZ: props.cameraZ,
    });
    stage.setModels(models.value);
});

watch(models, (next) => stage?.setModels(next), { deep: true });

onBeforeUnmount(() => {
    stage?.dispose();
    stage = null;
});
</script>

<template>
    <div v-if="models.length && !failed" class="relative h-full w-full">
        <div ref="container" class="absolute inset-0" :aria-label="t('scene.label')" role="img"></div>
        <p v-if="hint && draggable"
            class="pointer-events-none absolute bottom-3 start-1/2 -translate-x-1/2 rtl:translate-x-1/2 flex items-center gap-1.5 rounded-full border border-(--line) bg-(--bg)/70 px-3 py-1 text-[11px] text-(--muted) backdrop-blur">
            <Hand class="h-3.5 w-3.5 text-(--accent)" /> {{ t('scene.drag_hint') }}
        </p>
    </div>
    <slot v-else name="fallback" />
</template>
