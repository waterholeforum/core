import { Controller } from '@hotwired/stimulus';

interface SlideData {
    src: string;
    srcset?: string;
    width: number;
    height: number;
    msrc: string;
    alt?: string;
    element: HTMLElement;
}

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

        this.observer = new MutationObserver(() => this.enhanceImages());
        this.observer.observe(this.element, { childList: true, subtree: true });
    }

    disconnect() {
        this.element.removeEventListener('click', this.onClick);
        this.element.removeEventListener('keydown', this.onKeydown);
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

    private open(image: HTMLImageElement) {
        const dataSource = this.getImages().map((image) =>
            this.getSlideData(image),
        );
        const index = dataSource.findIndex((item) => item.element === image);

        if (index === -1) return;

        Waterhole.openLightbox?.({
            dataSource,
            index,
            bgOpacity: 0.92,
            paddingFn: () => ({ top: 24, right: 16, bottom: 24, left: 16 }),
        });
    }

    private enhanceImages() {
        this.element
            .querySelectorAll<HTMLImageElement>('img:not(.emoji)')
            .forEach((image) => {
                if (
                    !(image.currentSrc || image.src) ||
                    image.closest('a[href], button, [data-lightbox-ignore]')
                ) {
                    return;
                }

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
            });
    }

    private getImages() {
        return Array.from(
            this.element.querySelectorAll<HTMLImageElement>(
                'img[data-lightbox-image]',
            ),
        );
    }

    private closestImage(target: EventTarget | null) {
        if (!(target instanceof Element)) return null;

        return target.closest<HTMLImageElement>(
            'img[data-lightbox-image]',
        );
    }

    private getSlideData(image: HTMLImageElement): SlideData {
        const width = this.getDimension(image, 'width');
        const height = this.getDimension(image, 'height');

        return {
            src: image.currentSrc || image.src,
            srcset: image.srcset || undefined,
            width,
            height,
            msrc: image.currentSrc || image.src,
            alt: image.alt || undefined,
            element: image,
        };
    }

    private getDimension(
        image: HTMLImageElement,
        dimension: 'width' | 'height',
    ) {
        return (
            Number(image.getAttribute(dimension)) ||
            image[dimension === 'width' ? 'naturalWidth' : 'naturalHeight'] ||
            (dimension === 'width' ? 1600 : 1200)
        );
    }
}
