import { Controller } from '@hotwired/stimulus';
import { renderStreamMessage, StreamElement } from '@hotwired/turbo';
import { getHeaderHeight } from '../utils';

/**
 * Controller for the post page.
 *
 * @internal
 */
export default class extends Controller {
    static targets = [
        'post',
        'currentPage',
        'commentsLink',
        'commentsPagination',
    ];

    static values = {
        id: Number,
    };

    declare readonly postTarget: HTMLElement;
    declare readonly hasCurrentPageTarget: boolean;
    declare readonly currentPageTarget: HTMLElement;
    declare readonly hasCommentsLinkTarget: boolean;
    declare readonly commentsLinkTarget: HTMLElement;
    declare readonly hasCommentsPaginationTarget: boolean;
    declare readonly commentsPaginationTarget: HTMLElement;
    declare readonly idValue: number;

    connect() {
        document.addEventListener(
            'turbo:before-stream-render',
            this.beforeStreamRender,
        );
        document.addEventListener(
            'turbo:frame-render',
            this.showPostOnFirstPage,
        );

        window.addEventListener('scroll', this.onScroll, { passive: true });
        this.onScroll();
    }

    disconnect() {
        document.removeEventListener(
            'turbo:before-stream-render',
            this.beforeStreamRender,
        );
        document.removeEventListener(
            'turbo:frame-render',
            this.showPostOnFirstPage,
        );

        window.removeEventListener('scroll', this.onScroll);
    }

    private showPostOnFirstPage = () => {
        if (document.getElementById('page_1')) {
            this.postTarget.hidden = false;
        }
    };

    // If the post is deleted via an action, the returned Turbo Stream will try
    // to remove it from the page. We will navigate back to the post feed before
    // the stream is executed.
    private beforeStreamRender = (e: Event) => {
        const stream = e.target as StreamElement;
        if (
            stream.action === 'remove' &&
            stream.targets?.endsWith('post_' + this.idValue)
        ) {
            window.history.back();
            window.addEventListener(
                'popstate',
                () => {
                    window.requestAnimationFrame(() => {
                        renderStreamMessage(stream.outerHTML);
                    });
                },
                { once: true },
            );
            e.preventDefault();
        }
    };

    private onScroll = () => {
        // Wait for the scrollspy controller to update which page
        // is currently selected.
        setTimeout(() => {
            if (this.hasCurrentPageTarget) {
                this.currentPageTarget.textContent =
                    this.element.querySelector(
                        '.comments-pagination [aria-current="page"]',
                    )?.textContent || '1';
            }
        });

        if (this.hasCommentsLinkTarget && this.hasCommentsPaginationTarget) {
            this.commentsLinkTarget.hidden =
                this.postTarget.getBoundingClientRect().bottom <
                getHeaderHeight() + 10;
            this.commentsPaginationTarget.hidden =
                !this.commentsLinkTarget.hidden;
        }
    };
}
