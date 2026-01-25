import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo';

/**
 * Controller for some useful <turbo-frame> actions.
 */
export default class extends Controller<FrameElement> {
    connect() {
        if (!this.element.id) {
            this.element.id = this.element.dataset.id || '';
        }
    }

    reload() {
        this.element.reload();
    }

    disable() {
        this.element.disabled = true;
    }

    removeSrc() {
        this.element.removeAttribute('src');
    }

    /**
     * Remove the <turbo-frame> so that it doesn't interfere with child actions.
     *
     * Potentially this could be replaced by data-turbo-frame="_parent" on child
     * links and forms in the future.
     */
    remove() {
        this.element.replaceWith(...this.element.children);
    }
}
