import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('turbo:frame-render', this.scrollIntoView);
    }

    disconnect() {
        this.element.removeEventListener('turbo:frame-render', this.scrollIntoView);
    }

    private scrollIntoView = () => {
        const rect = this.element.getBoundingClientRect();
        if (rect.top < 0 || rect.bottom > window.innerHeight) {
            this.element.scrollIntoView();
        }
    };
}
