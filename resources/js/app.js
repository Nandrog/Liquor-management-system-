import './bootstrap';

import * as bootstrap from 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import { createApp } from 'vue';
import ChatApp from './components/ChatApp.vue';

const app = createApp({});
app.component('chat-app', ChatApp);
app.mount('#app');
window.dispatchEvent(new CustomEvent('vue-mounted'));





