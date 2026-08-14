import { Controller } from '@hotwired/stimulus';
import { PopupElement } from 'inclusive-elements';

export default class extends Controller<HTMLElement> {
    static targets = ['popup', 'drawer', 'body', 'sidebar'];

    declare readonly popupTarget: PopupElement;
    declare readonly drawerTarget: HTMLElement;
    declare readonly bodyTarget: HTMLElement;
    declare readonly sidebarTarget: HTMLElement;

    private observer = new ResizeObserver(() => this.update());

    connect() {
        this.observer.observe(this.element);
        document.addEventListener('turbo:before-cache', this.restore);

        this.update();
    }

    disconnect() {
        this.observer.disconnect();
        document.removeEventListener('turbo:before-cache', this.restore);
        this.restore();
    }

    private update() {
        if (getComputedStyle(this.popupTarget).display === 'none') {
            this.restore();
        } else {
            this.drawerTarget.append(this.sidebarTarget);
        }
    }

    private restore = () => {
        this.popupTarget.open = false;
        this.bodyTarget.prepend(this.sidebarTarget);
    };
}
