import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';

/**
 * Controller for a comment replies button.
 *
 * @internal
 */
export default class extends Controller {
    connect() {
        this.element.addEventListener('click', e => {
            const expanded = this.element.getAttribute('aria-expanded') === 'false';
            this.element.setAttribute('aria-expanded', String(expanded));
            const controlled = this.element.closest('.comment')?.querySelector<HTMLElement>('.comment__replies');
            if (controlled) {
                controlled.hidden = ! expanded;
            }
            if (! expanded) {
                e.preventDefault();
            }
        });
    }

    focusAfterLoad() {
        addEventListener('turbo:frame-render', e => {
            (e.target as FrameElement).querySelector<HTMLElement>('.comment__replies')?.focus();
        }, { once: true });
    }
}
