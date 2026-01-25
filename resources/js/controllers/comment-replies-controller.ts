import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo';

/**
 * Controller for the <x-waterhole::comment-replies> component.
 *
 * @internal
 */
export default class extends Controller {
    connect() {
        this.element.addEventListener('click', (e) => {
            const expanded =
                this.element.getAttribute('aria-expanded') === 'false';
            this.element.setAttribute('aria-expanded', String(expanded));
            const controlled = this.element
                .closest('.comment')
                ?.querySelector<HTMLElement>('.comment__replies');
            if (controlled) {
                controlled.hidden = !expanded;
            }
            if (!expanded) {
                e.preventDefault();
            }
        });
    }

    focusAfterLoad() {
        addEventListener(
            'turbo:frame-render',
            (e) => {
                // Safari will try to scroll down when we focus on the replies
                // element (if it is tall), but we don't want that, so revert
                // it afterwards.
                const top = window.scrollY;
                (e.target as FrameElement)
                    .querySelector<HTMLElement>('.comment__replies')
                    ?.focus();
                window.scroll({ top });
            },
            { once: true },
        );
    }
}
