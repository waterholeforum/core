import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '2d80bcb99dd1f29f0399', // TODO: replace with config vars
    cluster: 'ap4',
    forceTLS: true,
    namespace: 'Waterhole.Events',
});

window.Echo.registerTurboRequestInterceptor();
