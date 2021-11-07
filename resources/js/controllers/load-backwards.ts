import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    anchor?: HTMLElement;
    top?: number;
    observer?: MutationObserver;

    lockScrollPosition(e: CustomEvent) {
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

    unlockScrollPosition(e: CustomEvent) {
        if (e.target !== e.currentTarget) return;

        setTimeout(() => {
            this.observer?.disconnect();
            delete this.observer;
        });
    }
}
