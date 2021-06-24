import { Controller } from '@hotwired/stimulus';

/**
 * Controller for the login page.
 *
 * Carries over the value of the email input when you navigate away (eg. to the
 * register or forgot password page).
 *
 * @internal
 */
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
