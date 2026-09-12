import ky, { isHTTPError } from 'ky';
import { cloneFromTemplate, getCookie } from '../utils';

Waterhole.fetch = ky.create({
    headers: { 'X-XSRF-TOKEN': getCookie('XSRF-TOKEN') || undefined },
    hooks: {
        beforeError: [
            async ({ error }) => {
                if (isHTTPError(error)) {
                    await Waterhole.fetchError(error.response, error.data);
                }
                return error;
            },
        ],
    },
});

Waterhole.fetchError = async function (response?: Response, data?: unknown) {
    // TODO: use messages instead of templates
    let templateId = 'fatal-error-alert';
    let message: string | undefined;
    switch (response?.status) {
        case 401:
        case 403:
            templateId = 'forbidden-alert';
            break;

        case 419:
            templateId = 'session-expired-alert';
            break;

        case 422:
            if (data === undefined && !response.bodyUsed) {
                data = await response.json().catch(() => undefined);
            }
            if (
                data &&
                typeof data === 'object' &&
                'message' in data &&
                typeof data.message === 'string'
            ) {
                templateId = 'template-alert-danger';
                message = data.message;
            }
            break;

        case 429:
            templateId = 'too-many-requests-alert';
            break;
    }

    const alert = cloneFromTemplate(templateId);
    if (!alert) return;

    if (message !== undefined) {
        alert.querySelector('.alert__message')!.textContent = message;
    }

    Waterhole.alerts.show(alert, { key: 'fetchError' });
};
