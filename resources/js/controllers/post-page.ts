import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';

export class PostPage extends Controller {
    static targets = ['post'];

    postTarget?: HTMLElement;

    showPostOnFirstPage() {
        if (document.querySelector('[data-index="0"]')) {
            this.postTarget!.hidden = false;
        }
    }
}
