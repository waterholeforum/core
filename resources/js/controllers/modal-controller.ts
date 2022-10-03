import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { ModalElement } from 'inclusive-elements';

/**
 * Controller for the modal element.
 *
 *
 */
export default class extends Controller<ModalElement> {
    static targets = ['frame'];

    declare readonly frameTarget: FrameElement;

    connect() {
        this.element.addEventListener('close', () => {
            this.frameTarget.src = null;
        });

        this.frameTarget.removeAttribute('disabled');
    }

    loading(e: any) {
        this.show();
    }

    loaded() {
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
            this.element.open = false;
        }
    }
}
