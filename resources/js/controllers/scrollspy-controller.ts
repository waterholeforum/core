import { Controller } from '@hotwired/stimulus';
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
            if (el && el.getBoundingClientRect().top <= headerHeight + 50) {
                a.setAttribute('aria-current', 'page');

                if (this.current !== a && this.element) {
                    this.element.scroll({
                        top:
                            a.offsetTop +
                            a.offsetHeight / 2 -
                            this.element.offsetHeight / 2,
                        left:
                            a.offsetLeft +
                            a.offsetWidth / 2 -
                            this.element.offsetWidth / 2,
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
