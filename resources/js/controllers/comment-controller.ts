import { Controller } from '@hotwired/stimulus';
import { TooltipElement } from 'inclusive-elements';
import { isElementInViewport } from '../utils';

/**
 * Controller for the comment component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['parentTooltip'];

    parentTooltipTarget?: TooltipElement;

    // connect() {
    //     if (window.location.hash.replace('#comment_', '') === this.commentId) {
    //         this.element.classList.add('is-highlighted');
    //     }
    // }

    get commentId(): string {
        return this.element.getAttribute('data-comment-id') || '';
    }

    get parentId(): string {
        return this.element.getAttribute('data-parent-id') || '';
    }

    get parentElements(): HTMLElement[] {
        return Array.from(
            document.querySelectorAll<HTMLElement>(`[data-comment-id="${this.parentId}"]`)
        );
    }

    highlightParent() {
        this.parentElements.forEach((el) => {
            el.classList.add('is-highlighted');
        });

        if (this.parentTooltipTarget) {
            this.parentTooltipTarget.disabled = this.parentElements.some((el) =>
                isElementInViewport(el, 0.5)
            );
        }
    }

    stopHighlightingParent() {
        this.parentElements.forEach((el) => {
            el.classList.remove('is-highlighted');
        });
    }
}
