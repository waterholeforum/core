import { Controller } from '@hotwired/stimulus';
import copy from 'clipboard-copy';

/**
 * Controller to power a "copy link" button.
 */
export default class extends Controller {
    connect() {
        this.element.addEventListener('click', (e) => {
            copy(this.element.getAttribute('href') || '');
            e.preventDefault();
        });
    }
}
