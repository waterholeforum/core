import { Controller } from '@hotwired/stimulus';

export default class extends Controller<HTMLDetailsElement> {
    private toggle = () => {
        if (!this.element.open) {
            return;
        }

        const target = this.element.querySelector<HTMLElement>(
            ':is(button, [href], input, select, textarea, [tabindex])' +
                ':not([hidden])' +
                ':not([disabled])' +
                ':not([type="hidden"])' +
                ':not([tabindex="-1"])',
        );

        if (target) {
            target.focus();
        }
    };

    connect() {
        this.element.addEventListener('toggle', this.toggle);
    }

    disconnect() {
        this.element.removeEventListener('toggle', this.toggle);
    }
}
