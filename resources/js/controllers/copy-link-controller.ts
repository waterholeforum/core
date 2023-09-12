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

    copy(e: Event) {
        e.preventDefault();

        const target = e.target as HTMLElement;
        copy(target.getAttribute('href') || '');

        if (this.hasMessageValue) {
            const alert = cloneFromTemplate('template-alert-success');
            alert.querySelector('.alert__message')!.textContent =
                this.messageValue;
            Waterhole.alerts.show(alert, { key: 'copy-link' });
        }
    }
}
