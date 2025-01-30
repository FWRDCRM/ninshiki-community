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
        authEndpoint: '/api/broadcasting/auth',
        authorizer: (channel) => {
            return {
                authorize: async (socketId, callback) => {
                    try {
                        const data = await NinshikiApp.request().post(route('broadcast.auth'),{
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: {
                                socket_id: socketId,
                                channel_name: channel.name,
                            },
                        })
                        callback(false, data)
                    }
                    catch (error) {
                        callback(error)
                    }
                },
            }
        },
    })
}
