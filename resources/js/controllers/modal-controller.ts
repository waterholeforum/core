import { Controller } from '@hotwired/stimulus';
import { FrameElement, TurboBeforeFrameRenderEvent } from '@hotwired/turbo';
import { ModalElement } from 'inclusive-elements';

/**
 * Controller for the modal element.
 */
export default class extends Controller<ModalElement> {
    static targets = ['frame', 'loading'];

    declare readonly frameTarget: FrameElement;
    declare readonly loadingTarget: HTMLDivElement;
    private suppressBeforeModalClose = false;

    connect() {
        this.frameTarget.removeAttribute('disabled');
        this.element.addEventListener('beforeclose', this.beforeClose);
    }

    disconnect() {
        this.element.removeEventListener('beforeclose', this.beforeClose);
    }

    loading() {
        if (!this.element.open) {
            this.frameTarget.hidden = true;
            this.loadingTarget.hidden = false;
        }

        this.show();
    }

    beforeFrameRender(e: TurboBeforeFrameRenderEvent) {
        this.element.toggleAttribute(
            'static',
            e.detail.newFrame.hasAttribute('data-modal-static'),
        );
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

        this.element.removeAttribute('static');

        if (this.element.open) {
            this.suppressBeforeModalClose = true;
            this.element.close();
        }
    }

    private beforeClose = (e: Event) => {
        if (this.suppressBeforeModalClose) {
            this.suppressBeforeModalClose = false;
            return;
        }

        const beforeModalClose = new CustomEvent(
            'waterhole:before-modal-close',
            { bubbles: true, cancelable: true },
        );

        if (!this.element.dispatchEvent(beforeModalClose)) {
            e.preventDefault();
        }
    };
}
