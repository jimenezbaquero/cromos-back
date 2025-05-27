import '../css/app.css';
import './bootstrap';

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import {createI18n} from 'vue-i18n';
import messages from '../lang'; // index.js exportando idiomas
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const i18n = createI18n({
  legacy: false,
  locale: 'es', // idioma por defecto
  fallbackLocale: 'en',
  messages,
});

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    ),
  setup({el, App, props, plugin}) {
    return createApp({render: () => h(App, props)})
      .use(plugin)
      .use(ZiggyVue)
      .use(i18n)
      .use(Toast, {
        transition: 'Vue-Toastification__fade',
        maxToasts: 3,
        newestOnTop: true,
        position: 'top-right',
        timeout: 4000,
      })
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
