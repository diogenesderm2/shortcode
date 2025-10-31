import axios from 'axios';
import { configureEcho } from "@laravel/echo-vue";
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



const scheme = import.meta.env.VITE_REVERB_SCHEME ?? (window.location.protocol === 'https:' ? 'https' : 'http');
const host = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;
const port = Number(import.meta.env.VITE_REVERB_PORT ?? (scheme === 'https' ? 443 : 80));
const key = import.meta.env.VITE_REVERB_APP_KEY;

console.log('Reverb env:', { scheme, host, port, key });

// Inicializa via helper da lib
configureEcho({
    broadcaster: "reverb",
    key,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Fallback: se por algum motivo o global não foi criado
if (!window.Echo) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key,
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
