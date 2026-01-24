import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['count', 'unit'];

    declare readonly countTarget: HTMLInputElement;
    declare readonly unitTarget: HTMLSelectElement;

    connect() {
        this.update();
    }

    update = () => {
        const isIndefinite = this.unitTarget.value === 'indefinite';

        this.countTarget.disabled = isIndefinite;

        if (isIndefinite) {
            this.countTarget.value = '';
        } else if (!this.countTarget.value) {
            this.countTarget.value = '7';
        }
    };
}
