import { install, uninstall } from '@github/hotkey';
import { StreamElement } from '@hotwired/turbo';

function initHotkeys(el: Element) {
    el.querySelectorAll<HTMLElement>('[data-hotkey]').forEach((el) =>
        install(el),
    );
}

function initHotkeysEvent(e: Event) {
    initHotkeys(e.target as Element);
}

function deregisterHotkeys(e: Event) {
    (e.target as Element)
        .querySelectorAll<HTMLElement>('[data-hotkey]')
        .forEach((el) => uninstall(el));
}

document.addEventListener('turbo:before-visit', deregisterHotkeys);
document.addEventListener('turbo:load', initHotkeysEvent);

document.addEventListener('turbo:before-frame-render', deregisterHotkeys);
document.addEventListener('turbo:frame-load', initHotkeysEvent);

document.addEventListener('turbo:morph', initHotkeysEvent);
document.addEventListener('turbo:before-stream-render', (e) => {
    const { detail } = e;
    const fallback = detail.render;

    detail.render = async (stream: StreamElement) => {
        fallback(stream);

        const els = [];

        if (stream.target) {
            els.push(document.querySelector(stream.target));
        }

        if (stream.targets) {
            els.push(...document.querySelectorAll(stream.targets));
        }

        els.filter((el) => !!el).forEach(initHotkeys);
    };
});
