import { Controller } from '@hotwired/stimulus';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';
import { AlertsElement } from 'inclusive-elements';

export default class extends Controller {
    connect() {
        document.addEventListener('turbo:before-stream-render', this.streamAlerts);
        document.addEventListener('turbo:visit', this.dismissFlash);
    }

    disconnect() {
        document.removeEventListener('turbo:before-stream-render', this.streamAlerts);
        document.removeEventListener('turbo:visit', this.dismissFlash);
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
                this.alertsElement.show(alert, { key: 'flash' });
                e.preventDefault();
            }
        }
    };

    private dismissFlash = () => {
        Waterhole.alerts.dismiss('flash');
    };
}
