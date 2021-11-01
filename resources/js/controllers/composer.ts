import { Controller } from '@hotwired/stimulus';
import animateScrollTo from 'animated-scroll-to';

export class Composer extends Controller {
    static targets = ['handle'];

    handleTarget?: HTMLElement;

    connect() {
        const height = Number(localStorage.getItem('composer_height'));
        if (height) {
            (this.element as HTMLElement).style.height = height + 'px';
        }
    }

    open(e: MouseEvent) {
        // TODO: if opening in new table, exit
        e.preventDefault();

        this.element.classList.add('is-open');
        this.element.querySelector('textarea')?.focus();

        animateScrollTo(document.documentElement.offsetHeight, {
            minDuration: 200,
            maxDuration: 200
        });
    }

    close() {
        this.element.classList.remove('is-open');
    }

    startResize(e: MouseEvent) {
        if (e.target !== this.handleTarget) return;
        e.preventDefault();

        const el = this.element as HTMLElement;
        const startY = e.clientY;
        const startHeight = el.offsetHeight;
        const startBottom = el.getBoundingClientRect().bottom;

        const move = (e: MouseEvent) => {
            const height = startHeight - (e.clientY - startY);
            el.style.height = height + 'px';
            localStorage.setItem('composer_height', String(height));
            window.scroll(0, window.scrollY + el.getBoundingClientRect().bottom - startBottom);
        };

        el.classList.add('is-resizing');
        document.addEventListener('mousemove', move);
        document.addEventListener('mouseup', () => {
            document.removeEventListener('mousemove', move);
            el.classList.remove('is-resizing');
        }, { once: true });
    }
}
