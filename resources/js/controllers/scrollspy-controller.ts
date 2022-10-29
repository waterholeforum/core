import { Controller } from '@hotwired/stimulus';

/**
 * Controller to apply "active" nav link styles based on the scroll position.
 */
export default class extends Controller {
    static targets = ['container'];

    declare readonly containerTarget: HTMLElement;

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

        links.forEach((a) => a.removeAttribute('aria-current'));

        links.reverse().some((a) => {
            const id = a.hash.substring(1);
            if (!id) return;
            const el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top < window.innerHeight / 2) {
                a.setAttribute('aria-current', 'page');

                if (this.current !== a) {
                    this.containerTarget?.scroll({
                        top:
                            a.offsetTop +
                            a.offsetHeight / 2 -
                            this.containerTarget.offsetHeight / 2,
                        left:
                            a.offsetLeft + a.offsetWidth / 2 - this.containerTarget.offsetWidth / 2,
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
