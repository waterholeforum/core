import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['orderForm'];

    orderFormTarget?: HTMLFormElement;

    showOrderForm() {
        this.orderFormTarget!.hidden = false;
    }
}
