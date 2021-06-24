import { Controller } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';

/**
 * Controller for an alert.
 *
 * Provides an action for alerts to dismiss themselves.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    dismiss(e: MouseEvent) {
        // If this alert is contained inside a <ui-alerts> element, then we
        // will dismiss it via that element. Otherwise, we can just straight up
        // remove it from the DOM.
        const container = this.element.closest<AlertsElement>('ui-alerts');
        if (container) {
            container.dismiss(this.element);
        } else {
            this.element.remove();
        }
    }
}
