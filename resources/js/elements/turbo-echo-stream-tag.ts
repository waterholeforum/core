import { connectStreamSource, disconnectStreamSource } from '@hotwired/turbo';

const subscribeTo = (type: string, channel: string) => {
    if (type === 'presence') {
        return window.Echo.join(channel);
    }

    return (window.Echo as any)[type](channel);
};

export class TurboEchoStreamSourceElement extends HTMLElement {
    subscription: any;

    async connectedCallback() {
        connectStreamSource(this);
        this.subscription = subscribeTo(this.type, this.channel).listenToAll(
            (event: string, e: any) => {
                this.dispatchMessageEvent(e.streams);
            },
        );
    }

    disconnectedCallback() {
        disconnectStreamSource(this);
        if (this.subscription) {
            window.Echo.leave(this.channel);
            this.subscription = null;
        }
    }

    dispatchMessageEvent(data: any) {
        const event = new MessageEvent('message', { data });
        return this.dispatchEvent(event);
    }

    get channel() {
        return this.getAttribute('channel') || '';
    }

    get type() {
        return this.getAttribute('type') || 'private';
    }
}
