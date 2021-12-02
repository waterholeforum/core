import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['preview', 'form'];

    previewTarget?: HTMLElement;
    formTarget?: HTMLElement;

    change() {
        this.previewTarget!.hidden = true;
        this.formTarget!.hidden = false;
    }
}
