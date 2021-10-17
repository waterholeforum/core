import { Controller } from '@hotwired/stimulus';

export class HeaderController extends Controller {
    static targets = ['breadcrumb'];

    breadcrumbTarget?: HTMLElement;
    observer?: IntersectionObserver;

    initialize() {
        this.observer = new IntersectionObserver(entries => {
            this.breadcrumbTarget!.hidden = entries[0].isIntersecting;
        }, {
            rootMargin: `-${(this.element as HTMLElement).offsetHeight}px`,
        });
    }

    connect() {
        window.addEventListener('scroll', this.handleScroll);
        this.handleScroll();

        const h1 = document.querySelector('h1');
        this.breadcrumbTarget!.innerHTML = h1?.innerHTML || '';
        if (h1) {
            this.observer?.disconnect();
            this.observer?.observe(h1);
        }
    }

    disconnect() {
        window.removeEventListener('scroll', this.handleScroll);
    }

    handleScroll = () => {
        this.element.classList.toggle('is-sticky', window.scrollY > 0);
    }
}
