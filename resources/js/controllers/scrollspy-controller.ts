import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.onScroll();

        window.addEventListener('scroll', this.onScroll);
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    private onScroll = () => {
        const links = Array.from(this.links());

        links.forEach(a => a.removeAttribute('aria-current'));

        links.reverse().some(a => {
            const id = a.hash.substring(1);
            if (! id) return;
            const el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top < window.innerHeight / 2) {
                a.setAttribute('aria-current', 'page');
                return true;
            }
        });
    }

    private links() {
        return this.element.querySelectorAll<HTMLAnchorElement>('a[href*="#"]');
    }
}
