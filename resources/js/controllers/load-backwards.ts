import { Controller } from '@hotwired/stimulus';

export class LoadBackwards extends Controller {
    anchor?: HTMLElement;
    top?: number;
    observer?: MutationObserver;

    lockScrollPosition(e: CustomEvent) {
        this.anchor = this.element.nextElementSibling as HTMLElement;
        if (this.anchor) {
            this.top = this.anchor.getBoundingClientRect().top;
        }

        this.observer = new MutationObserver(() => this.restore());
        this.observer.observe(document.body, { subtree: true, childList: true, attributes: true });
    }

    restore() {
        if (this.anchor && this.top) {
            window.scroll({ top: window.scrollY + this.anchor.getBoundingClientRect().top - this.top });
        }
    }

    unlockScrollPosition() {
        setTimeout(() => {
            this.observer?.disconnect();
            delete this.observer;
        });
    }
}
