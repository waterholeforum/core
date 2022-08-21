import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['if', 'then'];

    thenTargets?: HTMLElement[];

    ifTargetConnected(el: HTMLElement) {
        el.addEventListener('change', this.toggle);
        el.dispatchEvent(new Event('change'));
    }

    ifTargetDisconnected(el: HTMLElement) {
        el.removeEventListener('change', this.toggle);
    }

    private toggle = (e: Event) => {
        const source = e.target as HTMLInputElement | HTMLSelectElement;
        let value = source.value;

        if (
            source instanceof HTMLInputElement &&
            ['checkbox', 'radio'].includes(source.type) &&
            !source.checked
        ) {
            value = '';
        }

        this.thenTargets?.forEach((el) => {
            el.hidden = el.dataset.revealValue ? value != el.dataset.revealValue : !value;
        });
    };
}
