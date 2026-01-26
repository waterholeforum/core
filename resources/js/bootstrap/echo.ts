import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { TurboEchoStreamSourceElement } from '../elements/turbo-echo-stream-tag';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo<'pusher' | 'reverb'>;
    }
}

window.Pusher = Pusher;

if (window.Waterhole.echoConfig.broadcaster) {
    window.Echo = new Echo({
        namespace: 'Waterhole.Events',
        ...window.Waterhole.echoConfig,
    });

    window.Echo.registerTurboRequestInterceptor();

    customElements.define(
        'turbo-echo-stream-source',
        TurboEchoStreamSourceElement,
    );
}
