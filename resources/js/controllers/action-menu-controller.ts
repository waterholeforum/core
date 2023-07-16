import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo';
import { PopupElement } from 'inclusive-elements';

/**
 * Controller for an action menu.
 *
 * @internal
 */
export default class extends Controller<PopupElement> {
    static targets = ['frame'];

    declare readonly hasFrameTarget: boolean;
    declare readonly frameTarget?: FrameElement;

    preload() {
        if (this.hasFrameTarget) {
            this.frameTarget?.setAttribute('loading', 'eager');
        }
    }
}
