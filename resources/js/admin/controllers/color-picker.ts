import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'picker', 'swatch'];

    inputTarget?: any;
    pickerTarget?: any;
    swatchTarget?: any;

    colorChanged(e: CustomEvent) {
        this.inputTarget!.color = e.detail.value;
        this.pickerTarget!.color = e.detail.value;
        this.swatchTarget!.style.backgroundColor = e.detail.value;
    }

    show() {
        this.pickerTarget!.hidden = false;
    }

    hide() {
        this.pickerTarget!.hidden = true;
    }
}
