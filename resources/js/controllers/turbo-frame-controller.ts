import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';

export default class extends Controller {
    reload() {
        (this.element as FrameElement).reload();
    }

    disable() {
        (this.element as FrameElement).disabled = true;
    }

    removeSrc() {
        (this.element as FrameElement).removeAttribute('src');
    }
}
