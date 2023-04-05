import { Controller } from '@hotwired/stimulus';
import StickyObserver from 'sticky-observer';

/**
 * Controller to set up a StickyObserver.
 */
export default class extends Controller {
    observer?: StickyObserver;

    connect() {
        this.observer = new StickyObserver(this.element, (stuck) => {
            this.element.classList.toggle('is-stuck', stuck);
            this.element.classList.toggle('is-unstuck', !stuck);
        });
    }

    disconnect() {
        this.observer?.stop();
    }
}
