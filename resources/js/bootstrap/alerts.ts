import { AlertsElement } from 'inclusive-elements';

window.Waterhole.alerts = document.getElementById('alerts') as AlertsElement;

window.Waterhole.fetchError = function(response: Response): void {
    let templateId;
    switch (response.status) {
        case 401:
        case 403:
            templateId = 'forbidden-alert';
            break;

        case 429:
            templateId = 'too-many-requests-alert';
            break;

        default:
            templateId = 'fatal-error-alert';
    }

    const template = document.getElementById(templateId) as HTMLTemplateElement;
    const alert = template?.content?.firstElementChild?.cloneNode(true) as HTMLElement;

    if (alert) {
        this.alerts.show(alert, { key: 'fetchError', duration: -1 });
    }
};

document.addEventListener('turbo:before-fetch-response', async e => {
    const response = (e as CustomEvent).detail.fetchResponse;

    if (response.statusCode >= 400 && response.statusCode !== 422 && response.statusCode <= 599) {
        window.Waterhole.fetchError(response.response);
        e.preventDefault();
        return;
    }

    window.Waterhole.alerts.dismiss('fetchError');

    appendAlertsFromDocument(
        new DOMParser().parseFromString(await response.responseHTML, 'text/html')
    );
});

document.addEventListener('DOMContentLoaded', () => {
    appendAlertsFromDocument(document);
});

function appendAlertsFromDocument(document: Document) {
    const append = document.getElementById('alerts-append');
    if (! append) return;
    Array.from(append.children).forEach(el => {
        window.Waterhole.alerts.show(el as HTMLElement);
    });
}
