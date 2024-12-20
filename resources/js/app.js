import {createApp, h} from 'vue'
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import {createInertiaApp} from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    title: title => title ? `${title} - Ninshiki` : 'Ninshiki',
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        cssLayer: {
                            name: 'primevue',
                            order: 'tailwind-base, primevue, tailwind-utilities'
                        }
                    }
                }
            })
            .mount(el)
    },
})
