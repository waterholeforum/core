import { Controller } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';

/**
 * Controller for an alert.
 *
 * Provides an action for alerts to dismiss themselves.
 *
 * @internal
 */
export default class extends Controller {
    dismiss(e: MouseEvent) {
        // If this alert is contained inside a <ui-alerts> element, then we
        // will dismiss it via that element. Otherwise, we can just straight up
        // remove it from the DOM.
        const alert = e.currentTarget as HTMLElement;
        const container = alert.closest<AlertsElement>('ui-alerts');
        if (container) {
            container.dismiss(alert);
        } else {
            alert.remove();
        }
    }
}
