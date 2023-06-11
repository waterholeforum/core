import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { cloneFromTemplate } from '../utils';

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

    replaceWithSpinner() {
        this.element.replaceChildren(cloneFromTemplate('loading'));
    }

    /**
     * Remove the <turbo-frame> so that it doesn't interfere with child actions.
     */
    remove() {
        this.element.replaceWith(...this.element.children);
    }
}
