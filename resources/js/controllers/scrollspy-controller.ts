import { Controller } from '@hotwired/stimulus';
import { clamp } from 'lodash-es';
import { getHeaderHeight } from '../utils';

/**
 * Controller to apply "active" nav link styles based on the scroll position.
 */
export default class extends Controller<HTMLElement> {
    current?: HTMLElement;

    static values = {
        persist: {
            type: Boolean,
            default: true,
        },
        selector: {
            type: String,
            default: 'a[href*="#"]:not([data-scrollspy-ignore])',
        },
    };

    declare readonly persistValue: boolean;
    declare readonly selectorValue: string;

    connect() {
        this.onScroll();

        window.addEventListener('scroll', this.onScroll);
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    private onScroll = () => {
        const links = Array.from(this.links());
        const headerHeight = getHeaderHeight();

        links.forEach((a) => a.removeAttribute('aria-current'));

        if (this.element.hidden) {
            this.current = undefined;
            return;
        }

        const active = this.persistValue
            ? this.persistedLink(links, headerHeight)
            : this.visibleLink(links, headerHeight);

        if (!active) {
            this.current = undefined;
            return;
        }

        active.setAttribute('aria-current', 'page');

        if (this.current !== active) {
            this.element.dispatchEvent(
                new CustomEvent('scrollspy:change', {
                    bubbles: true,
                    detail: { active },
                }),
            );

            const container = this.scrollContainer();
            const link = active.getBoundingClientRect();
            const outer = container.getBoundingClientRect();

            container.scroll({
                top: clamp(
                    container.scrollTop +
                        (link.top - outer.top) -
                        (container.clientHeight - link.height) / 2,
                    0,
                    container.scrollHeight - container.clientHeight,
                ),
                left: clamp(
                    container.scrollLeft +
                        (link.left - outer.left) -
                        (container.clientWidth - link.width) / 2,
                    0,
                    container.scrollWidth - container.clientWidth,
                ),
                behavior: this.current ? 'smooth' : 'auto',
            });
        }

        this.current = active;
    };

    private links() {
        return this.element.querySelectorAll<HTMLAnchorElement>(
            this.selectorValue,
        );
    }

    private scrollContainer() {
        let el: HTMLElement | null = this.element;

        while (el) {
            const style = getComputedStyle(el);
            const scrollable =
                /(auto|scroll)/.test(style.overflowY + style.overflow) &&
                el.scrollHeight > el.clientHeight;

            if (scrollable) return el;

            el = el.parentElement;
        }

        return this.element;
    }

    private persistedLink(links: HTMLAnchorElement[], headerHeight: number) {
        return [...links].reverse().find((a) => {
            const el = this.target(a);

            return el && el.getBoundingClientRect().top <= headerHeight + 100;
        });
    }

    private visibleLink(links: HTMLAnchorElement[], headerHeight: number) {
        return links.find((a) => {
            const el = this.target(a);
            if (!el) return false;

            const rect = el.getBoundingClientRect();

            return (
                rect.bottom > headerHeight + 100 &&
                rect.top < window.innerHeight
            );
        });
    }

    private target(a: HTMLAnchorElement) {
        const id = a.hash.substring(1);

        return id ? document.getElementById(id) : null;
    }
}
