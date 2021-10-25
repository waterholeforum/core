import { Controller } from '@hotwired/stimulus';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';
import { AlertsElement } from '../../../../../../packages/inclusive-elements';

export class AlertsController extends Controller {
    connect() {
        document.addEventListener('turbo:before-stream-render', this.streamAlerts);
    }

    disconnect() {
        document.removeEventListener('turbo:before-stream-render', this.streamAlerts);
    }

    dismiss(e: MouseEvent) {
        this.alertsElement.dismiss((e.currentTarget as HTMLElement).parentElement!.parentElement!);
    }

    get alertsElement() {
        return this.element as AlertsElement;
    }

    private streamAlerts = (e: Event) => {
        const stream = e.target as StreamElement;
        if (stream.targetElements.includes(this.element) && stream.action === 'append') {
            const alert = stream.templateContent.firstElementChild as HTMLElement;
            if (alert) {
                this.alertsElement.show(alert);
                e.preventDefault();
            }
        }
    };
}
