import { Controller } from '@hotwired/stimulus';
import StickyObserver from 'sticky-observer';

export default class extends Controller {
    observer?: StickyObserver;

    connect() {
        this.observer = new StickyObserver(this.element, (stuck) => {
            this.element.classList.toggle('is-stuck', stuck);
        });
    }

    disconnect() {
        this.observer?.stop();
    }
}
