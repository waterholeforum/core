import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['header', 'breadcrumb', 'title'];

    declare readonly headerTarget: HTMLElement;
    declare readonly breadcrumbTarget: HTMLElement;

    declare observer: IntersectionObserver;

    initialize() {
        this.observer = new IntersectionObserver(
            (entries) => {
                this.breadcrumbTarget.hidden = entries[0].isIntersecting;
            },
            { rootMargin: `-${this.headerTarget?.offsetHeight || 0}px` }
        );
    }

    titleTargetConnected(element: HTMLElement) {
        this.breadcrumbTarget.innerHTML = element.innerHTML || '';
        this.observer.observe(element);
    }

    titleTargetDisconnected() {
        this.observer.disconnect();
    }

    incrementDocumentTitle() {
        Waterhole.documentTitle.increment();
    }
}
