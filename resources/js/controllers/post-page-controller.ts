import { Controller } from '@hotwired/stimulus';
import { renderStreamMessage } from '@hotwired/turbo';
import { FrameElement, StreamElement } from '@hotwired/turbo/dist/types/elements';

export default class extends Controller {
    static targets = ['post', 'bottom'];

    static values = {
        id: Number,
    };

    postTarget?: HTMLElement;
    bottomTarget?: HTMLElement;
    idValue?: number;

    connect() {
        if (this.idValue) {
            window.Echo.channel(`Waterhole.Models.Post.${this.idValue}`)
                .listen('NewComment', (data: any) => {
                    if (this.bottomTarget) {
                        const frame = document.createElement('turbo-frame') as FrameElement;
                        frame.id = data.dom_id;
                        frame.src = data.url;
                        this.bottomTarget.before(frame);
                    }
                });
        }

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
    }

    private beforeStreamRender = (e: Event) => {
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
