import { Controller } from '@hotwired/stimulus';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';
import { AlertsElement } from 'inclusive-elements';

/**
 * Controller for the main alerts element.
 *
 * Listens in on Turbo Streams to make sure any alerts are properly appended
 * to the container. Also provides an action for alerts to dismiss themselves.
 *
 * @internal
 */
export default class extends Controller<AlertsElement> {
    connect() {
        document.addEventListener('turbo:before-stream-render', this.streamAlert);
    }

    disconnect() {
        document.removeEventListener('turbo:before-stream-render', this.streamAlert);
    }

    dismiss(e: MouseEvent) {
        // This action will be triggered by a "close" button within an alert,
        // so we will find the closest alert parent and dismiss it from the
        // alerts container.
        const alert = (e.currentTarget as HTMLElement).closest<HTMLElement>('.alert');
        if (alert) {
            this.element.dismiss(alert);
        }
    }

    private streamAlert = (e: Event) => {
        // When a Turbo Stream comes in, we will check to see if it is
        // appending to the alerts container. If it is, we will append it via
        // the AlertsElement's show method, rather than just a plain DOM append.
        const stream = e.target as StreamElement;
        if (stream.targetElements.includes(this.element) && stream.action === 'append') {
            const alert = stream.templateContent.firstElementChild as HTMLElement;
            if (alert) {
                this.element.show(alert);
                e.preventDefault();
            }
        }
    };
}
