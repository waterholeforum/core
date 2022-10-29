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

    show() {
        this.pickerTarget.hidden = false;
    }

    hide() {
        this.pickerTarget.hidden = true;
    }

    colorChanged(e: CustomEvent) {
        this.inputTarget.color = e.detail.value;
        this.pickerTarget.color = e.detail.value;
        this.swatchTarget.style.backgroundColor = e.detail.value;
    }
}
