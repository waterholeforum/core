import { Controller } from '@hotwired/stimulus';
import { ModalElement } from 'inclusive-elements';
import { getHeaderHeight } from '../utils';

/**
 * Controller for the page.
 */
export default class extends Controller {
    static targets = ['breadcrumb', 'title'];

    declare readonly hasBreadcrumbTarget: boolean;
    declare readonly breadcrumbTarget: HTMLElement;

    declare observer: IntersectionObserver;

    private hideBreadcrumb?: number;

    initialize() {
        this.observer = new IntersectionObserver(
            (entries) => {
                if (!this.hasBreadcrumbTarget) return;
                if (this.hideBreadcrumb) {
                    cancelAnimationFrame(this.hideBreadcrumb);
                }
                this.breadcrumbTarget.hidden = entries[0].isIntersecting;
                if (!entries[0].isIntersecting) {
                    this.breadcrumbTarget.innerHTML =
                        entries[0].target.innerHTML || '';
                }
            },
            { rootMargin: `-${getHeaderHeight()}px` },
        );
    }

    connect() {
        this.hideBreadcrumb = requestAnimationFrame(() => {
            if (!this.hasBreadcrumbTarget) return;
            this.breadcrumbTarget.hidden = true;
        });
    }

    titleTargetConnected(element: HTMLElement) {
        this.observer.observe(element);
    }

    titleTargetDisconnected() {
        this.observer.disconnect();
    }

    incrementDocumentTitle() {
        Waterhole.documentTitle.increment();
    }

    closeModal() {
        document.querySelector<ModalElement>('#modal-element')?.close();
    }
}
