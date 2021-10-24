import { Controller } from '@hotwired/stimulus';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';

export class PostPage extends Controller {
    static targets = ['post'];

    static values = {
        id: Number,
    };

    postTarget?: HTMLElement;
    idValue?: number;

    showPostOnFirstPage() {
        if (document.querySelector('[data-index="0"]')) {
            this.postTarget!.hidden = false;
        }
    }

    beforeStreamRender(e: CustomEvent) {
        const stream = e.target as StreamElement;
        if (stream.action === 'remove' && stream.target?.endsWith('post_'+this.idValue)) {
            window.history.back();
        }
    }
}
