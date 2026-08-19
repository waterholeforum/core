import 'photoswipe/style.css';
import PhotoSwipe, { type PhotoSwipeOptions } from 'photoswipe';

Waterhole.openLightbox = (options: PhotoSwipeOptions) => {
    const lightbox = new PhotoSwipe(options);

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
