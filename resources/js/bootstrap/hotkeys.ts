import { install } from '@github/hotkey';

function initHotkeys(e: Event) {
    (e.target as Element).querySelectorAll<HTMLElement>('[data-hotkey]').forEach((el) => {
        install(el);
    });
}

document.addEventListener('DOMContentLoaded', initHotkeys);
document.addEventListener('turbo:render', initHotkeys);
document.addEventListener('turbo:frame-render', initHotkeys);
