import { Controller } from '@hotwired/stimulus';
import { FrameElement, TurboBeforeFrameRenderEvent } from '@hotwired/turbo';
import { debounce } from 'lodash-es';

/**
 * Controller for displaying similar posts underneath the post title field.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    static targets = ['submit', 'frame'];

    declare readonly submitTarget?: HTMLButtonElement;

    submit() {
        this.submitTarget?.click();
    }

    frameTargetConnected(element: FrameElement) {
        element.addEventListener('turbo:before-frame-render', (e) => {
            element.hidden = !(e as TurboBeforeFrameRenderEvent).detail.newFrame
                .children.length;
        });
    }

    input = debounce(this.submit, 250);
}
