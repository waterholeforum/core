import * as Turbo from '@hotwired/turbo';
import { FrameElement, TurboFrameMissingEvent } from '@hotwired/turbo';
import { cloneFromTemplate } from '../utils';

declare global {
    interface Window {
        Turbo: any;
    }
}

window.Turbo = Turbo;

document.addEventListener('turbo:before-render', (e) => {
    const newAlerts = e.detail.newBody.querySelector('#alerts');
    if (!newAlerts) return;
    [...newAlerts.children].forEach((el) =>
        Waterhole.alerts.show(el as HTMLElement),
    );
});

document.addEventListener('turbo:before-fetch-response', async (e) => {
    const response = (e as CustomEvent).detail.fetchResponse.response;
    if (response.ok || response.status === 422) return;
    e.preventDefault();

    const { target } = e;
    if (target instanceof FrameElement) {
        const el = cloneFromTemplate('frame-error');
        el.querySelector('button')?.addEventListener('click', () => {
            target.reload();
        });
        target.replaceChildren(el);
    } else {
        Waterhole.fetchError(response);
    }
});

document.addEventListener('turbo:frame-missing', async (e) => {
    e.preventDefault();
    const { detail } = e as TurboFrameMissingEvent;
    detail.visit(detail.response, { action: 'replace' });
});

Waterhole.fetchError = async function (response?: Response) {
    // TODO: use messages instead of templates
    let templateId;
    switch (response?.status) {
        case 401:
        case 403:
            templateId = 'forbidden-alert';
            break;

        case 419:
            templateId = 'session-expired-alert';
            break;

        case 422:
            const alert = cloneFromTemplate('template-alert-danger');
            alert.querySelector('.alert__message')!.textContent = (
                await response.json()
            ).message;
            Waterhole.alerts.show(alert, { key: 'fetchError' });
            break;

        case 429:
            templateId = 'too-many-requests-alert';
            break;

        default:
            templateId = 'fatal-error-alert';
    }

    if (templateId) {
        const alert = cloneFromTemplate(templateId);

        if (alert) {
            Waterhole.alerts.show(alert, { key: 'fetchError' });
        }
    }
};
