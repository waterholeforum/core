import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    remove({ params: { name }}: any) {
        this.element.removeAttribute(name);
    }
}
