import { Controller } from '@hotwired/stimulus';
import { getHeaderHeight } from '../utils';

/**
 * Controller for the page.
 */
export default class extends Controller {
    static targets = ['breadcrumb', 'title'];

    declare readonly breadcrumbTarget: HTMLElement;

    declare observer: IntersectionObserver;

    initialize() {
        this.observer = new IntersectionObserver(
            (entries) => {
                this.breadcrumbTarget.hidden = entries[0].isIntersecting;
            },
            { rootMargin: `-${getHeaderHeight()}px` }
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
