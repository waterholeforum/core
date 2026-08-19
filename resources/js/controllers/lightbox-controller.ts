import { Controller } from '@hotwired/stimulus';

/**
 * Enhances content images with a PhotoSwipe lightbox.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    private observer?: MutationObserver;

    connect() {
        this.enhanceImages();
        this.element.addEventListener('click', this.onClick);
        this.element.addEventListener('keydown', this.onKeydown);
        this.element.addEventListener('load', this.onLoad, true);

        this.observer = new MutationObserver(() => this.enhanceImages());
        this.observer.observe(this.element, { childList: true, subtree: true });
    }

    disconnect() {
        this.element.removeEventListener('click', this.onClick);
        this.element.removeEventListener('keydown', this.onKeydown);
        this.element.removeEventListener('load', this.onLoad, true);
        this.observer?.disconnect();
    }

    private onClick = (e: MouseEvent) => {
        if (
            e.defaultPrevented ||
            e.button !== 0 ||
            e.metaKey ||
            e.ctrlKey ||
            e.shiftKey ||
            e.altKey
        ) {
            return;
        }

        const image = this.closestImage(e.target);

        if (image) {
            e.preventDefault();
            this.open(image);
        }
    };

    private onKeydown = (e: KeyboardEvent) => {
        if (e.key !== 'Enter' && e.key !== ' ') return;

        const image = this.closestImage(e.target);

        if (image) {
            e.preventDefault();
            this.open(image);
        }
    };

    private onLoad = (e: Event) => {
        if (e.target instanceof HTMLImageElement) {
            this.enhanceImage(e.target);
        }
    };

    private open(image: HTMLImageElement) {
        const images = Array.from(
            this.element.querySelectorAll<HTMLImageElement>(
                'img[data-lightbox-image]',
            ),
        );

        Waterhole.openLightbox?.({
            dataSource: images.map((image) => {
                let width = Number(image.getAttribute('width'));
                let height = Number(image.getAttribute('height'));

                if (width <= 0 || height <= 0) {
                    width = image.naturalWidth;
                    height = image.naturalHeight;
                }

                return {
                    src: image.currentSrc || image.src,
                    srcset: image.srcset || undefined,
                    width,
                    height,
                    msrc: image.currentSrc || image.src,
                    alt: image.alt || undefined,
                    caption:
                        image
                            .closest('figure')
                            ?.querySelector('figcaption')
                            ?.textContent?.trim() ||
                        image.title.trim() ||
                        undefined,
                    element: image,
                };
            }),
            index: images.indexOf(image),
            bgOpacity: 0.92,
            paddingFn: () => ({ top: 24, right: 16, bottom: 24, left: 16 }),
        });
    }

    private enhanceImages() {
        this.element
            .querySelectorAll<HTMLImageElement>('img')
            .forEach((image) => this.enhanceImage(image));
    }

    private enhanceImage(image: HTMLImageElement) {
        if (
            image.classList.contains('emoji') ||
            image.closest('a[href], button, [data-lightbox-ignore]')
        ) {
            return;
        }

        if (!image.complete || !image.naturalWidth || !image.naturalHeight)
            return;

        image.dataset.lightboxImage = '';

        if (!image.hasAttribute('tabindex')) {
            image.tabIndex = 0;
        }

        if (!image.hasAttribute('role')) {
            image.setAttribute('role', 'button');
        }

        if (!image.hasAttribute('aria-label')) {
            image.setAttribute(
                'aria-label',
                image.alt ? `Open image: ${image.alt}` : 'Open image',
            );
        }
    }

    private closestImage(target: EventTarget | null) {
        if (!(target instanceof Element)) return null;

        return target.closest<HTMLImageElement>('img[data-lightbox-image]');
    }
}
