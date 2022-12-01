import { Controller } from '@hotwired/stimulus';
import { renderStreamMessage } from '@hotwired/turbo';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';

/**
 * Controller for the post page.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['post'];

    static values = {
        id: Number,
    };

    declare readonly postTarget: HTMLElement;
    declare readonly idValue: number;

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
            this.postTarget.hidden = false;
        }
    };

    // If the post is deleted via an action, the returned Turbo Stream will try
    // to remove it from the page. We will navigate back to the post feed before
    // the stream is executed.
    private beforeStreamRender = (e: Event) => {
        const stream = e.target as StreamElement;
        if (stream.action === 'remove' && stream.targets?.endsWith('post_' + this.idValue)) {
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
