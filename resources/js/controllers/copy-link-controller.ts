import { Controller } from '@hotwired/stimulus';
import copy from 'clipboard-copy';
import { cloneFromTemplate } from '../utils';

/**
 * Controller to power a "copy link" button.
 */
export default class extends Controller {
    static values = { message: String };

    declare readonly hasMessageValue: boolean;
    declare readonly messageValue: string;

    connect() {
        this.element.addEventListener('click', (e) => {
            copy(this.element.getAttribute('href') || '');
            e.preventDefault();

            if (this.hasMessageValue) {
                const alert = cloneFromTemplate('template-alert-success');
                alert.querySelector('.alert__message')!.textContent = this.messageValue;
                Waterhole.alerts.show(alert);
            }
        });
    }
}
