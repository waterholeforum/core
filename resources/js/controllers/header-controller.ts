import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        window.addEventListener('scroll', this.handleScroll);
        this.handleScroll();
    }

    disconnect() {
        window.removeEventListener('scroll', this.handleScroll);
    }

    handleScroll = () => {
        this.element.classList.toggle('is-sticky', window.scrollY > 0);
    };
}
