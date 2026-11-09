// v-reveal: fade/slide elements in when they enter the viewport. Optional value = delay in ms.
export const reveal = {
    mounted(el, binding) {
        el.classList.add('reveal');
        if (binding.value) el.style.setProperty('--reveal-delay', `${binding.value}ms`);
        if (!('IntersectionObserver' in window)) {
            el.classList.add('is-visible');
            return;
        }
        el._revealObserver = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-visible');
                    el._revealObserver.disconnect();
                }
            },
            { threshold: 0.12 },
        );
        el._revealObserver.observe(el);
    },
    unmounted(el) {
        el._revealObserver?.disconnect();
    },
};

// v-tilt: perspective tilt + glare that follows the pointer (pairs with the .tilt CSS class).
export const tilt = {
    mounted(el, binding) {
        const max = binding.value ?? 8;
        el.classList.add('tilt');
        el._tiltMove = (event) => {
            const rect = el.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width;
            const y = (event.clientY - rect.top) / rect.height;
            el.style.setProperty('--ry', `${(x - 0.5) * max * 2}deg`);
            el.style.setProperty('--rx', `${(0.5 - y) * max * 2}deg`);
            el.style.setProperty('--mx', `${x * 100}%`);
            el.style.setProperty('--my', `${y * 100}%`);
        };
        el._tiltLeave = () => {
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        };
        el.addEventListener('pointermove', el._tiltMove);
        el.addEventListener('pointerleave', el._tiltLeave);
    },
    unmounted(el) {
        el.removeEventListener('pointermove', el._tiltMove);
        el.removeEventListener('pointerleave', el._tiltLeave);
    },
};
