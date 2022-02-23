import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    anchor?: HTMLElement;
    top?: number;
    observer?: MutationObserver;

    connect() {
        this.element.addEventListener('turbo:before-fetch-response', this.lockScrollPosition);
        this.element.addEventListener('turbo:frame-render', this.unlockScrollPosition);
    }

    disconnect() {
        this.element.removeEventListener('turbo:before-fetch-response', this.lockScrollPosition);
        this.element.removeEventListener('turbo:frame-render', this.unlockScrollPosition);
    }

    private lockScrollPosition = (e: Event) => {
        if (e.target !== e.currentTarget) return;

        this.anchor = this.element.nextElementSibling as HTMLElement;
        if (this.anchor) {
            this.top = this.anchor.getBoundingClientRect().top;
        }

        this.observer?.disconnect();
        this.observer = new MutationObserver(() => this.restore());
        this.observer.observe(document.body, { subtree: true, childList: true, attributes: true });
    }

    restore() {
        if (this.anchor && this.top) {
            window.scroll({ top: window.scrollY + this.anchor.getBoundingClientRect().top - this.top });
        }
    }

    private unlockScrollPosition = (e: Event) => {
        if (e.target !== e.currentTarget) return;

        setTimeout(() => {
            this.observer?.disconnect();
            delete this.observer;
        });
    }
}
