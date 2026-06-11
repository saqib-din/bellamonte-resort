import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('click', (e) => {
    const el = e.target.closest('input, select, textarea');
    if (!el) return;
    el.focus();
    if (el.type === 'date' && typeof el.showPicker === 'function') {
        try { el.showPicker(); } catch (_) { /* ignore */ }
    }
});

const inertiaEl = document.getElementById('app');
if (inertiaEl) {
    createInertiaApp({
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue'),
            ),
        setup({ el, App, props, plugin }) {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .mount(el);
        },
        progress: { color: '#1c64f2' },
    });
}
