import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['reload'];

    reloadTarget?: HTMLButtonElement;

    reload() {
        this.reloadTarget?.click();
    }
}
