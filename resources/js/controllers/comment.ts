import { Controller } from '@hotwired/stimulus';
import { TooltipElement } from '../../../../../../packages/inclusive-elements';
import { isElementInViewport } from '../utils';
import placement from 'placement.js';

// let collapsed: string[] = [];
//
// try {
//     collapsed = JSON.parse(window.localStorage.getItem('collapsed_comments') || '[]');
// } catch (e) {}

export class Comment extends Controller {
    static targets = ['parentTooltip', 'body', 'quoteButton'];

    parentTooltipTarget?: TooltipElement;
    bodyTarget?: HTMLElement;
    quoteButtonTarget?: HTMLButtonElement;

    selectedText?: string;
    selectionChangeTimeout?: number;

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

    async showQuoteButton(e: Event) {
        if (! this.quoteButtonTarget) return;

        clearTimeout(this.selectionChangeTimeout);
        await new Promise(resolve => this.selectionChangeTimeout = window.setTimeout(resolve, 100));

        this.quoteButtonTarget.hidden = true;

        if (! this.bodyTarget) return;

        const selection = window.getSelection();

        if (
            ! selection
            || ! selection.rangeCount
            || ! selection.anchorNode
            || ! selection.focusNode
        ) {
            return;
        }

        const range = selection.getRangeAt(0);
        const parent = range.commonAncestorContainer;

        // If the selection spans outside of the content area, or there
        // is no selection at all, we will not proceed.
        if (
            (parent !== this.bodyTarget && ! this.bodyTarget.contains(parent))
            || range.collapsed
        ) {
            return;
        }

        this.quoteButtonTarget.hidden = false;

        // Place the quote button according to where the focus of the
        // selection is (ie. where the selection began).
        const position = selection.anchorNode.compareDocumentPosition(selection.focusNode);
        const rects = range.getClientRects();
        let anchor, side;

        if (
            position & Node.DOCUMENT_POSITION_PRECEDING
            || (! position && selection.focusOffset < selection.anchorOffset)
        ) {
            const rect = rects[0];
            anchor = new DOMRect(rect.left, rect.top);
            side = 'top';
        } else {
            const rect = rects[rects.length - 1];
            anchor = new DOMRect(rect.right, rect.bottom);
            side = 'bottom';
        }

        placement(anchor, this.quoteButtonTarget, { placement: side as any });
    }

    quoteSelectedText() {
        const container = document.createElement('div');
        const selection = window.getSelection();
        if (! selection) return;

        container.appendChild(selection.getRangeAt(0).cloneContents());
        container.querySelectorAll('img').forEach(el => el.replaceWith(el.alt));

        selection.removeAllRanges();

        // Wait until the next tick so that the composer has had a chance to
        // open (via turbo:before-fetch-request) before we dispatch the event.
        setTimeout(() => {
            this.dispatch('quote-text', {
                detail: { text: container.textContent },
                bubbles: true,
                cancelable: true,
            })
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
