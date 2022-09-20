import { Controller } from '@hotwired/stimulus';
import { renderStreamMessage } from '@hotwired/turbo';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';

export default class extends Controller {
    static targets = ['post'];

    static values = {
        id: Number,
    };

    postTarget?: HTMLElement;
    idValue?: number;

    connect() {
        document.addEventListener('turbo:before-stream-render', this.beforeStreamRender);
        document.addEventListener('turbo:frame-render', this.showPostOnFirstPage);
    }

    disconnect() {
        document.removeEventListener('turbo:before-stream-render', this.beforeStreamRender);
        document.removeEventListener('turbo:frame-render', this.showPostOnFirstPage);
    }

    private showPostOnFirstPage = () => {
        if (document.querySelector('[data-index="0"]')) {
            this.postTarget!.hidden = false;
        }
    };

    private beforeStreamRender = (e: Event) => {
        const stream = e.target as StreamElement;
        if (stream.action === 'remove' && stream.target?.endsWith('post_' + this.idValue)) {
            window.history.back();
            window.addEventListener(
                'popstate',
                () => {
                    window.requestAnimationFrame(() => {
                        renderStreamMessage(stream.outerHTML);
                    });
                },
                { once: true }
            );
            e.preventDefault();
        }
    };
}
