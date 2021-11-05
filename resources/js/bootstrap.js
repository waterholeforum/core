import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '2d80bcb99dd1f29f0399',
    cluster: 'ap4',
    forceTLS: true,
    namespace: 'Waterhole.Events',
});

document.addEventListener('turbo:before-fetch-request', (e) => {
    e.detail.fetchOptions.headers['X-Socket-ID'] = window.Echo.socketId();
});
