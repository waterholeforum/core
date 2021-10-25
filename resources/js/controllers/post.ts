import { Controller } from '@hotwired/stimulus';

export class PostController extends Controller {
    appearAsRead() {
        if (this.element.classList.contains('is-unread')) {
            this.element.classList.remove('is-unread', 'is-new');
            this.element.classList.add('is-read');
        }
    }
}
