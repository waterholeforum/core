import { AlertsElement } from 'inclusive-elements';

window.Waterhole.alerts = document.getElementById('alerts') as AlertsElement;

document.addEventListener('turbo:before-fetch-response', async e => {
    const response = (e as any).detail.fetchResponse;
    const alerts = document.getElementById('alerts') as AlertsElement;
    if (response.statusCode >= 400 && response.statusCode !== 422 && response.statusCode <= 599) {
        const templateId = response.statusCode === 429 ? 'too-many-requests-alert' : 'fatal-error-alert';
        const alert = (document.getElementById(templateId) as HTMLTemplateElement)?.content?.firstElementChild?.cloneNode(true) as HTMLElement;
        if (alert) {
            alerts.show(alert, { key: 'fetchError', duration: -1 });
        }
        e.preventDefault();
    } else {
        alerts.dismiss('fetchError');
    }
});
