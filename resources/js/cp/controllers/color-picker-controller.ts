import { Controller } from '@hotwired/stimulus';

/**
 * Controller for the <x-waterhole::color-picker> component.
 *
 * Provides actions to show and hide the color picker on input focus/blur.
 * Also keeps the input, picker, and swatch in sync when the color is changed.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['input', 'picker', 'swatch'];

    declare readonly inputTarget: any;
    declare readonly pickerTarget: any;
    declare readonly swatchTarget: any;

    private timeout?: number;

    show() {
        clearTimeout(this.timeout);
        this.pickerTarget.hidden = false;
    }

    hide() {
        clearTimeout(this.timeout);
        this.timeout = window.setTimeout(
            () => (this.pickerTarget.hidden = true),
        );
    }

    colorChanged(e: CustomEvent) {
        this.inputTarget.color = e.detail.value;
        this.pickerTarget.color = e.detail.value;
        this.swatchTarget.style.backgroundColor = e.detail.value;
    }
}
