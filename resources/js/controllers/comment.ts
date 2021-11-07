import { Controller } from '@hotwired/stimulus';
import { TooltipElement } from '../../../../../../packages/inclusive-elements';
import { isElementInViewport } from '../utils';

// let collapsed: string[] = [];
//
// try {
//     collapsed = JSON.parse(window.localStorage.getItem('collapsed_comments') || '[]');
// } catch (e) {}

export default class extends Controller {
    static targets = ['parentTooltip'];

    parentTooltipTarget?: TooltipElement;

    get commentId(): string {
        return this.element.getAttribute('data-comment-id') || '';
    }

    get parentId(): string {
        return this.element.getAttribute('data-parent-id') || '';
    }

    get parentElements(): HTMLElement[] {
        return Array.from(document.querySelectorAll<HTMLElement>(`[data-comment-id="${this.parentId}"]`));
    }

    highlightParent() {
        this.parentElements.forEach(el => {
            el.classList.add('is-highlighted');
        });

        if (this.parentTooltipTarget) {
            this.parentTooltipTarget.disabled = this.parentElements.some(el => isElementInViewport(el, .5));
        }
    }

    stopHighlightingParent() {
        this.parentElements.forEach(el => {
            el.classList.remove('is-highlighted');
        });
    }

    // isCollapsed() {
    //     return collapsed.includes(this.commentId);
    // }

    // connect() {
    //     if (this.lineTarget) {
    //         this.lineTarget.classList.add('comment__line--clickable');
    //     }
    //
    //     if (this.isCollapsed()) {
    //         this.element.setAttribute('aria-expanded', 'false');
    //     }
    // }
    //
    // toggle() {
    //     const expanded = this.element.getAttribute('aria-expanded') === 'false';
    //     this.element.setAttribute('aria-expanded', String(expanded));
    //
    //     if (! expanded) {
    //         const top = (this.element as HTMLElement).getBoundingClientRect().top;
    //         const scrollPadding = ['.header', '.post-comments__toolbar']
    //             .reduce((a, c) => a + (document.querySelector<HTMLElement>(c)?.offsetHeight || 0), 0);
    //
    //         if (top < scrollPadding) {
    //             window.scrollTo({ top: scrollY + top - scrollPadding });
    //         }
    //     }
    //
    //     if (expanded) {
    //         collapsed = collapsed.filter(id => id !== this.commentId);
    //     } else if (! this.isCollapsed() && this.commentId) {
    //         collapsed.push(this.commentId);
    //     }
    //
    //     window.localStorage.setItem('collapsed_comments', JSON.stringify(collapsed));
    // }
}
