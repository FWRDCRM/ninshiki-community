import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export function NinshikiEcho(
    options = {
        key: '',
        host: '',
        port: 8080,
        scheme: 'http',
    },
) {
    window.Pusher = Pusher;
    return (window.Echo = new Echo({
        broadcaster: 'reverb',
        key: options.key,
        wsHost: options.host,
        wsPort: options.port,
        wssPort: options.port,
        forceTLS: (options.scheme ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
        autoReconnect: true,
        authEndpoint: route('broadcast.auth'),
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        },
    }));
}
