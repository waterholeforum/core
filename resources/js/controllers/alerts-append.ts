import { Controller } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';

export default class extends Controller {
    connect() {
        const alerts = document.getElementById('alerts') as AlertsElement;
        Array.from(this.element.children).forEach(el => {
            alerts.show(el as HTMLElement, { key: 'flash' });
        });
        this.element.remove();
    }
}
