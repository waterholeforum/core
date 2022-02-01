import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    email: string = '';

    connect() {
        const input = this.element.querySelector<HTMLInputElement>('input[name=email]');
        if (input) {
            this.email = input.value;
            input.addEventListener('input', () => {
                this.email = input.value;
            });
        }
    }

    disconnect() {
        document.addEventListener('turbo:load', () => {
            const input = document.querySelector<HTMLInputElement>('input[name=email]');
            if (input) {
                input.value = this.email;
            }
        }, { once: true });
    }
}
