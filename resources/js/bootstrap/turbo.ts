import * as Turbo from '@hotwired/turbo';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';
import morphdom from 'morphdom';

declare global {
    interface Window {
        Turbo: any;
    }
}

Turbo.start();

window.Turbo = Turbo;

document.addEventListener('turbo:submit-start', e => {
    const submitter = (e as any).detail.formSubmission.submitter;
    if (! submitter) return;
    submitter.disabled = true;
    const popupButton = submitter.closest('ui-popup')?.children[0] as HTMLButtonElement;
    if (popupButton) {
        popupButton.disabled = true;
    }
});

document.addEventListener('turbo:submit-end', e => {
    const submitter = (e as any).detail.formSubmission.submitter;
    if (! submitter) return;
    submitter.disabled = false;
    const popupButton = submitter.closest('ui-popup')?.children[0] as HTMLButtonElement;
    if (popupButton) {
        popupButton.disabled = false;
    }
});

document.addEventListener('turbo:before-stream-render', e => {
    const stream = e.target as StreamElement;
    if (stream.action === 'replace') {
        e.preventDefault();
        stream.targetElements.forEach(el => {
            morphdom(el, stream.templateContent.firstElementChild!);
        });
    }
});

document.addEventListener('turbo:frame-missing', async ({ detail: { fetchResponse }}: any) => {
    const { location, redirected, statusCode, responseHTML } = fetchResponse;
    const response = { redirected, statusCode, responseHTML: await responseHTML };

    Turbo.visit(location, { response });
});

document.addEventListener('turbo:visit', async () => {
    window.Waterhole.alerts.clear();
});
