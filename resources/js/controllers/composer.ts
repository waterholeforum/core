import { Controller } from '@hotwired/stimulus';
import animateScrollTo from 'animated-scroll-to';
import { shouldOpenInNewTab } from '../utils';

export default class extends Controller {
    static targets = ['handle'];

    connect() {
        const height = Number(localStorage.getItem('composer_height'));
        if (height) {
            (this.element as HTMLElement).style.height = height + 'px';
        }

        if (window.location.hash.substr(1) === this.element.id) {
            this.open();
        }
    }

    handleTargetConnected(element: HTMLElement) {
        element.hidden = false;
    }

    placeholderClick(e: MouseEvent) {
        if (shouldOpenInNewTab(e)) return;

        e.preventDefault();

        this.open();

        animateScrollTo(document.documentElement.offsetHeight, {
            minDuration: 200,
            maxDuration: 200
        });
    }

    open() {
        this.element.classList.add('is-open');
        this.element.querySelector('textarea')?.focus();
    }

    close() {
        this.element.classList.remove('is-open');
    }

    submitEnd(e: CustomEvent) {
        if (e.detail.fetchResponse.contentType.startsWith('text/vnd.turbo-stream.html')) {
            this.close();
            // const comments = document.querySelectorAll('.comment');
            // const comment = comments[comments.length - 1];
            // if (comment) {
            //     animateScrollTo(comment);
            // }
        }
    }

    startResize(e: MouseEvent) {
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
