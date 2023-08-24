import { Controller } from '@hotwired/stimulus';
import { TooltipElement } from 'inclusive-elements';
import { isElementInViewport } from '../utils';

const expanded: Record<string, boolean> = {};

/**
 * Controller for the <x-waterhole::comment-full> component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['parentTooltip'];

    declare readonly parentTooltipTarget?: TooltipElement;

    get commentId(): string {
        return this.element.getAttribute('data-comment-id') || '';
    }

    get parentId(): string {
        return this.element.getAttribute('data-parent-id') || '';
    }

    get parentElements(): HTMLElement[] {
        return Array.from(
            document.querySelectorAll<HTMLElement>(
                `[data-comment-id="${this.parentId}"]`,
            ),
        );
    }

    connect() {
        if (expanded[this.commentId]) {
            this.toggleHidden();
        }
    }

    disconnect() {
        expanded[this.commentId] =
            this.element.classList.contains('is-expanded');
    }

    toggleHidden() {
        this.element.classList.toggle('is-expanded');
    }

    highlightParent() {
        this.parentElements.forEach((el) => {
            el.classList.add('is-highlighted');
        });

        if (this.parentTooltipTarget) {
            this.parentTooltipTarget.disabled = this.parentElements.some((el) =>
                isElementInViewport(el, 0.5),
            );
        }
    }

    stopHighlightingParent() {
        this.parentElements.forEach((el) => {
            el.classList.remove('is-highlighted');
        });
    }
}
