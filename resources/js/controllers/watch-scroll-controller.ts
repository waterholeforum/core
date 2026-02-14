import { Controller } from '@hotwired/stimulus';

/**
 *
 */
export default class extends Controller<HTMLElement> {
    private observer?: ResizeObserver;

    connect() {
        this.element.addEventListener('scroll', this.onScroll);
        document.addEventListener('turbo:morph', this.onScroll);

        this.observer = new ResizeObserver(() => this.onScroll());
        this.observer.observe(this.element);

        this.onScroll();
    }

    disconnect() {
        this.element.removeEventListener('scroll', this.onScroll);
        document.removeEventListener('turbo:morph', this.onScroll);

        this.observer?.disconnect();
    }

    private onScroll = () => {
        const el = this.element;

        el.classList.toggle('is-scrolled-down', el.scrollTop > 0);
        el.classList.toggle('is-scrolled-right', el.scrollLeft > 0);
        el.classList.toggle(
            'is-scrolled-up',
            el.scrollTop < el.scrollHeight - el.offsetHeight - 1,
        );
        el.classList.toggle(
            'is-scrolled-left',
            el.scrollLeft < el.scrollWidth - el.offsetWidth - 1,
        );
    };
}
