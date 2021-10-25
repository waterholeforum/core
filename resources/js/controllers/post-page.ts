import { Controller } from '@hotwired/stimulus';
import { renderStreamMessage } from '@hotwired/turbo';
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

    async beforeStreamRender(e: CustomEvent) {
        const stream = e.target as StreamElement;
        if (stream.action === 'remove' && stream.target?.endsWith('post_'+this.idValue)) {
            window.history.back();
            window.addEventListener('popstate', () => {
                window.requestAnimationFrame(() => {
                    renderStreamMessage(stream.outerHTML);
                });
            }, { once: true });
            e.preventDefault();
        }
    }
}
