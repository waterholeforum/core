import * as Turbo from '@hotwired/turbo';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';
// @ts-ignore
import { morph } from 'idiomorph';

declare global {
    interface Window {
        Turbo: any;
    }
}

Turbo.start();

window.Turbo = Turbo;

document.addEventListener('turbo:before-stream-render', (e) => {
    const stream = e.target as StreamElement;

    if (stream.action === 'replace') {
        e.preventDefault();
        stream.targetElements.forEach((el) => {
            morph(el, stream.templateContent.firstElementChild!);
        });
    }
});
