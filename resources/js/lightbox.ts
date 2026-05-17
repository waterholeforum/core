import 'photoswipe/style.css';
import PhotoSwipe, { type PhotoSwipeOptions } from 'photoswipe';

Waterhole.openLightbox = (options: PhotoSwipeOptions) => {
    new PhotoSwipe(options).init();
};
