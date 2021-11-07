import { Controller } from '@hotwired/stimulus';
import placement from 'placement.js';

export default class extends Controller {
    static targets = ['button'];

    buttonTarget?: HTMLButtonElement;

    private handleSelectionChange = () => {
        setTimeout(this.updateQuoteButton.bind(this), 100);
    };

    connect() {
        document.addEventListener('mouseup', this.handleSelectionChange);
    }

    disconnect() {
        document.removeEventListener('mouseup', this.handleSelectionChange);
    }

    async updateQuoteButton() {
        if (! this.buttonTarget) return;
        this.buttonTarget.hidden = true;

        const selection = window.getSelection();

        if (
            ! selection
            || selection.isCollapsed
            || ! selection.anchorNode
            || ! selection.focusNode
        ) {
            return;
        }

        const range = selection.getRangeAt(0);
        const parent = range.commonAncestorContainer;

        // If the selection spans outside of the content area, or there
        // is no selection at all, we will not proceed.
        if (parent !== this.element && ! this.element.contains(parent)) {
            return;
        }

        this.buttonTarget.hidden = false;

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

        placement(anchor, this.buttonTarget, { placement: side as any });
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
}
