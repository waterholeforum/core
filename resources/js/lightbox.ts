import 'photoswipe/style.css';
import PhotoSwipe, { type PhotoSwipeOptions } from 'photoswipe';

let activeLightbox: PhotoSwipe | undefined;

document.addEventListener('turbo:before-cache', () => {
    if (!activeLightbox) return;

    // PhotoSwipe normally defers cleanup through its closing lifecycle. Mark
    // it as destroying to remove the overlay before Turbo snapshots the page.
    activeLightbox.isDestroying = true;
    activeLightbox.destroy();
});

Waterhole.openLightbox = (options: PhotoSwipeOptions) => {
    const lightbox = new PhotoSwipe(options);
    activeLightbox = lightbox;

    lightbox.on('destroy', () => {
        if (activeLightbox === lightbox) activeLightbox = undefined;
    });

    lightbox.on('uiRegister', () => {
        lightbox.ui?.registerElement({
            name: 'caption',
            className: 'pswp__caption hide-if-empty',
            appendTo: 'root',
            onInit: (element, pswp) => {
                pswp.on('change', () => {
                    element.textContent = pswp.currSlide?.data.caption || '';
                });
            },
        });
    });

    lightbox.init();
};
