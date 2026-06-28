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
        'commentsLinks',
        'commentsPagination',
        'commentsHeading',
    ];

    static values = {
        id: Number,
    };

    declare readonly postTarget: HTMLElement;
    declare readonly commentsHeadingTarget: HTMLElement;
    declare readonly hasCurrentPageTarget: boolean;
    declare readonly currentPageTarget: HTMLElement;
    declare readonly hasCommentsLinksTarget: boolean;
    declare readonly commentsLinksTarget: HTMLElement;
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
        document.addEventListener('turbo:morph', this.onScroll);
        this.element.addEventListener(
            'scrollspy:change',
            this.onScrollspyChange,
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
        document.removeEventListener('turbo:morph', this.onScroll);
        this.element.removeEventListener(
            'scrollspy:change',
            this.onScrollspyChange,
        );

        window.removeEventListener('scroll', this.onScroll);
    }

    private showPostOnFirstPage = () => {
        if (document.getElementById('page_1')) {
            this.postTarget.hidden = false;
            this.commentsHeadingTarget.hidden = false;
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

    private onScrollspyChange = (event: Event) => {
        const active = (event as CustomEvent).detail?.active as
            | HTMLElement
            | undefined;

        if (!active?.classList.contains('comments-pagination__page-link')) {
            return;
        }

        const currentPage = active.closest('.comments-pagination__page');

        if (this.hasCurrentPageTarget) {
            this.currentPageTarget.textContent =
                active.dataset.pageNumber || '1';
        }

        this.element
            .querySelectorAll<HTMLElement>('.comments-pagination__highlights')
            .forEach((highlights) => {
                const hidden =
                    highlights.closest('.comments-pagination__page') !==
                    currentPage;

                highlights.hidden = hidden;
                highlights.classList.add('transition-hidden');
            });
    };

    private onScroll = () => {
        if (this.hasCommentsLinksTarget && this.hasCommentsPaginationTarget) {
            const commentsLinksVisible =
                this.postTarget.getBoundingClientRect().bottom >=
                getHeaderHeight() + 10;

            this.commentsLinksTarget.hidden = !commentsLinksVisible;
            this.commentsPaginationTarget.hidden = commentsLinksVisible;
            this.commentsLinksTarget.classList.add('transition-hidden');
            this.commentsPaginationTarget.classList.add('transition-hidden');
        }
    };
}
