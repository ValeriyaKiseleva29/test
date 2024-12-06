import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(ElementPlus)
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: '869214',
    wsHost: 'localhost',
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

app.mount('#app')
