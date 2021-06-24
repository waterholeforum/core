import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { ModalElement } from 'inclusive-elements';

/**
 * Controller for the modal element.
 */
export default class extends Controller<ModalElement> {
    static targets = ['frame', 'loading'];

    declare readonly frameTarget: FrameElement;
    declare readonly loadingTarget: HTMLDivElement;

    connect() {
        this.frameTarget.removeAttribute('disabled');
    }

    loading() {
        if (!this.element.open) {
            this.frameTarget.hidden = true;
            this.loadingTarget.hidden = false;
        }

        this.show();
    }

    loaded() {
        this.frameTarget.hidden = false;
        this.loadingTarget.hidden = true;

        if (this.frameTarget.children.length) {
            this.show();
        } else {
            this.hide();
        }
    }

    show() {
        if (!this.element.open) {
            this.element.open = true;
        }
    }

    hide(e?: Event) {
        if (e instanceof MouseEvent) {
            e.preventDefault();
        }

        if (this.element.open) {
            this.element.close();
        }
    }
}
