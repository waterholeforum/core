import { Controller } from '@hotwired/stimulus';
import animateScrollTo from 'animated-scroll-to';
import { getHeaderHeight } from '../utils';

export default class extends Controller {
    static targets = ['newActivity'];

    static values = {
        sort: String,
        channels: Array,
    };

    newActivityTarget?: HTMLElement;
    sortValue?: string;
    channelsValue?: number[];

    connect() {
        this.channelsValue?.forEach(id => {
            window.Echo.channel(`Waterhole.Models.Channel.${id}`)
                .listen('NewComment', () => {
                    if (this.sortValue === 'new-activity') {
                        this.showNewActivity();
                    }
                })
                .listen('NewPost', () => {
                    if (this.sortValue === 'latest' || this.sortValue === 'new-activity') {
                        this.showNewActivity();
                    }
                });
        })
    }

    disconnect() {
        this.channelsValue?.forEach(id => {
            window.Echo.leave(`Waterhole.Models.Channel.${id}`);
        });
    }

    showNewActivity() {
        if (this.newActivityTarget) {
            this.newActivityTarget.hidden = false;
        }
    }

    scrollToTop() {
        animateScrollTo(this.element, {
            verticalOffset: -getHeaderHeight() - 20,
        });
    }
}
