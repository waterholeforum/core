import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';

export default class extends Controller {
    disable() {
        (this.element as FrameElement).disabled = true;
    }
}
