import { Controller } from '@hotwired/stimulus';
import animateScrollTo from 'animated-scroll-to';
import { shouldOpenInNewTab } from '../utils';

/**
 * Controller for the <x-waterhole::composer> component.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    static targets = ['handle'];

    connect() {
        const height = Number(localStorage.getItem('composer_height'));
        if (height) {
            this.element.style.height = height + 'px';
        }

        window.addEventListener('hashchange', this.onHashChange);
        this.onHashChange();
    }

    disconnect() {
        window.removeEventListener('hashchange', this.onHashChange);
    }

    private onHashChange = (e?: any) => {
        // Turbo sends the hashchange event before the hash has actually
        // updated, so do this after a tick.
        setTimeout(() => {
            if (window.location.hash === '#reply') {
                this.open();
                window.scroll({ top: document.body.scrollHeight });
            }
        });
    };

    placeholderClick(e: MouseEvent) {
        if (shouldOpenInNewTab(e)) return;

        e.preventDefault();

        this.open();

        animateScrollTo(document.documentElement.offsetHeight, {
            minDuration: 200,
            maxDuration: 200,
        });
    }

    open() {
        this.element.classList.add('is-open');
        setTimeout(() => this.element.querySelector('textarea')?.focus());
    }

    close() {
        this.element.classList.remove('is-open');
    }

    submitEnd(e: CustomEvent) {
        if (e.detail.fetchResponse.contentType.startsWith('text/vnd.turbo-stream.html')) {
            this.close();
        }
    }

    startResize(e: PointerEvent) {
        e.preventDefault();

        const el = this.element as HTMLElement;
        const startY = e.clientY;
        const startHeight = el.offsetHeight;
        const startBottom = el.getBoundingClientRect().bottom;

        const move = (e: PointerEvent) => {
            const height = startHeight - (e.clientY - startY);
            el.style.height = height + 'px';
            localStorage.setItem('composer_height', String(height));
            window.scroll(0, window.scrollY + el.getBoundingClientRect().bottom - startBottom);
        };

        document.addEventListener('pointermove', move);
        document.addEventListener(
            'pointerup',
            () => {
                document.removeEventListener('pointermove', move);
            },
            { once: true }
        );
    }
}
