import './bootstrap'
import '../css/app.css'
import 'primeicons/primeicons.css'

import { createApp, h, DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Toaster from './Components/ui/toast/Toaster.vue'

import { createI18n } from 'vue-i18n'
import ar from './locales/ar.json'
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue'
const i18n = createI18n({
  legacy: false,
  locale: 'ar',
  fallbackLocale: 'ar',
  messages: {
    ar,
  },
})

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: async (name) => {
    const page = await resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
    )
    // page.default.layout ??= AuthenticatedLayout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(ZiggyVue)
      .mount(el)

    const toasterEl = document.getElementById('toaster')
    if (toasterEl) {
      createApp(Toaster).mount(toasterEl)
    }
  },
  progress: {
    color: '#4B5563',
  },
})
