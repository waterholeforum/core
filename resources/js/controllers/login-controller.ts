import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    disconnect() {
        const value =
            this.element.querySelector<HTMLInputElement>('input[name=email]')?.value || '';

        document.addEventListener(
            'turbo:load',
            () => {
                const input = document.querySelector<HTMLInputElement>('input[name=email]');
                if (input) {
                    input.value = value;
                }
            },
            { once: true }
        );
    }
}
