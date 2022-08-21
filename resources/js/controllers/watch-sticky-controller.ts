import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    observer?: IntersectionObserver;

    connect() {
        this.observer = new IntersectionObserver(
            (entries) => {
                const el = entries[0].target;
                const isSticky =
                    entries[0].intersectionRatio < 1 && getComputedStyle(el).position === 'sticky';

                el.classList.toggle('is-sticky', isSticky);
            },
            { threshold: 1 }
        );

        this.observer.observe(this.element);
    }

    disconnect() {
        this.observer?.disconnect();
    }
}
