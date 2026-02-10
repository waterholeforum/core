import { Controller } from '@hotwired/stimulus';
import { clamp } from 'lodash-es';
import { getHeaderHeight } from '../utils';

/**
 * Controller to apply "active" nav link styles based on the scroll position.
 */
export default class extends Controller<HTMLElement> {
    current?: HTMLElement;

    connect() {
        this.onScroll();

        window.addEventListener('scroll', this.onScroll);
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    private onScroll = () => {
        const links = Array.from(this.links());
        const headerHeight = getHeaderHeight();

        links.forEach((a) => a.removeAttribute('aria-current'));

        links.reverse().some((a) => {
            const id = a.hash.substring(1);
            if (!id) return;
            const el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top <= headerHeight + 100) {
                a.setAttribute('aria-current', 'page');

                if (this.current !== a) {
                    const container = this.element;
                    const link = a.getBoundingClientRect();
                    const outer = container.getBoundingClientRect();

                    this.element.scroll({
                        top: clamp(
                            container.scrollTop +
                                (link.top - outer.top) -
                                (container.clientHeight - link.height) / 2,
                            0,
                            container.scrollHeight - container.clientHeight,
                        ),
                        left: clamp(
                            container.scrollLeft +
                                (link.left - outer.left) -
                                (container.clientWidth - link.width) / 2,
                            0,
                            container.scrollWidth - container.clientWidth,
                        ),
                        behavior: this.current ? 'smooth' : 'auto',
                    });
                }

                this.current = a;
                return true;
            }
        });
    };

    private links() {
        return this.element.querySelectorAll<HTMLAnchorElement>('a[href*="#"]');
    }
}
