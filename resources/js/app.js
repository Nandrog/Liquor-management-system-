import './bootstrap';

import * as bootstrap from 'bootstrap';  // This is correct for Bootstrap JS
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
// --- VUE SETUP FIX ---
import { createApp } from 'vue';
import ChatApp from './components/ChatApp.vue';
// Check if the specific chat container element exists on the page before trying to mount Vue.
// This prevents errors on pages that don't have the chat app.
const chatElement = document.getElementById('chat-container');
if (chatElement) {
const app = createApp({});
app.component('chat-app', ChatApp);
app.mount('#chat-container'); // Mount Vue ONLY to the chat container
}






