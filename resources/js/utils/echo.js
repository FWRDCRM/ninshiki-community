import Echo from "laravel-echo";

export function echo(options = {
    key: '',
    host: '',
    port: 8080,
    scheme: 'http'}) {
    window.Pusher = Pusher
    return  new Echo({
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
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        }
    })
}
