import { connectStreamSource, disconnectStreamSource } from '@hotwired/turbo';

const subscribeTo = (type, channel) => {
    if (type === 'presence') {
        return window.Echo.join(channel);
    }

    return window.Echo[type](channel);
};

class TurboEchoStreamSourceElement extends HTMLElement {
    async connectedCallback() {
        connectStreamSource(this);
        this.subscription = subscribeTo(this.type, this.channel).listenToAll((event, data) => {
            this.dispatchMessageEvent(data.streams);
        });
    }

    disconnectedCallback() {
        disconnectStreamSource(this);
        if (this.subscription) {
            window.Echo.leave(this.channel);
            this.subscription = null;
        }
    }

    dispatchMessageEvent(data) {
        const event = new MessageEvent('message', { data });
        return this.dispatchEvent(event);
    }

    get channel() {
        return this.getAttribute('channel');
    }

    get type() {
        return this.getAttribute('type') || 'private';
    }
}

customElements.define('turbo-echo-stream-source', TurboEchoStreamSourceElement);
