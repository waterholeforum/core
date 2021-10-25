import { Controller } from '@hotwired/stimulus';

let collapsed: string[] = [];

try {
    collapsed = JSON.parse(window.localStorage.getItem('collapsed_comments') || '[]');
} catch (e) {}

export class Comment extends Controller {
    // static targets = ['line'];

    lineTarget?: HTMLElement;

    get commentId(): string {
        return this.element.getAttribute('data-id') || '';
    }

    isCollapsed() {
        return collapsed.includes(this.commentId);
    }

    connect() {
        if (this.lineTarget) {
            this.lineTarget.classList.add('comment__line--clickable');
        }

        if (this.isCollapsed()) {
            this.element.setAttribute('aria-expanded', 'false');
        }
    }

    toggle() {
        const expanded = this.element.getAttribute('aria-expanded') === 'false';
        this.element.setAttribute('aria-expanded', String(expanded));

        if (! expanded) {
            const top = (this.element as HTMLElement).getBoundingClientRect().top;
            const scrollPadding = ['.header', '.post-comments__toolbar']
                .reduce((a, c) => a + (document.querySelector<HTMLElement>(c)?.offsetHeight || 0), 0);

            if (top < scrollPadding) {
                window.scrollTo({ top: scrollY + top - scrollPadding });
            }
        }

        if (expanded) {
            collapsed = collapsed.filter(id => id !== this.commentId);
        } else if (! this.isCollapsed() && this.commentId) {
            collapsed.push(this.commentId);
        }

        window.localStorage.setItem('collapsed_comments', JSON.stringify(collapsed));
    }
}
