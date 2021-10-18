import { Controller } from '@hotwired/stimulus';

export class Scrollspy extends Controller {
    connect() {
        this.onScroll();
    }

    onScroll() {
        const links = Array.from(this.links());

        links.forEach(a => a.removeAttribute('aria-current'));

        links.reverse().some(a => {
            const id = a.hash.substr(1);
            const el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top < window.innerHeight / 2) {
                a.setAttribute('aria-current', 'page');
                return true;
            }
        });
    }

    links() {
        return this.element.querySelectorAll<HTMLAnchorElement>('a[href*="#"]');
    }
}
