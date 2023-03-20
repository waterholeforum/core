import { Controller } from '@hotwired/stimulus';

/**
 * Controller to allow an element to show/hide based on the value of an input.
 *
 * The `if` target should point to the input or select element. `then` targets
 * point to the element(s) to be revealed. Optionally, a `data-reveal-value`
 * attribute can be added to each `then` target to only reveal it if the input
 * equals that value. Otherwise, the target will be revealed if the input has
 * a truthy value or is checked.
 */
export default class extends Controller {
    static targets = ['if', 'then'];

    declare readonly thenTargets: HTMLElement[];

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

        this.thenTargets.forEach((el) => {
            if (!el.dataset.revealValue) el.hidden = !value;
            else {
                let values: any = el.dataset.revealValue;
                try {
                    values = JSON.parse(values);
                } catch (e) {
                    //
                } finally {
                    if (Array.isArray(values)) {
                        values = values.map((v: any) => String(v));
                    } else {
                        values = [el.dataset.revealValue];
                    }
                }
                el.hidden = !values.includes(value);
            }
        });
    };
}
