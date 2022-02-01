import { install } from '@github/hotkey';

document.addEventListener('turbo:load', () => {
    document.querySelectorAll<HTMLElement>('[data-hotkey]').forEach(el => {
        install(el);
    });
});
