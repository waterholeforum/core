import { Controller } from '@hotwired/stimulus';

/**
 *
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    static targets = ['expander'];

    declare readonly expanderTarget: HTMLButtonElement;

    connect() {
        if (this.element.scrollHeight > this.element.offsetHeight) {
            this.expanderTarget.hidden = false;
        }
    }

    expand() {
        this.element.style.maxHeight = 'none';
        this.expanderTarget.hidden = true;
    }
}
