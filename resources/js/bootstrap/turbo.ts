import * as Turbo from '@hotwired/turbo';
import {
    FrameElement,
    StreamActions,
    TurboFrameMissingEvent,
} from '@hotwired/turbo';
import { cloneFromTemplate, getFragmentTarget, nextFrame } from '../utils';
import { AlertsElement } from 'inclusive-elements';

declare global {
    interface Window {
        Turbo: any;
    }
}

window.Turbo = Turbo;

let newAlerts: AlertsElement | null = null;

document.addEventListener('turbo:before-render', (e) => {
    newAlerts = e.detail.newBody.querySelector<AlertsElement>('#alerts');
});

document.addEventListener('turbo:load', () => {
    if (!newAlerts) return;
    [...newAlerts.children].forEach((el) =>
        Waterhole.alerts.show(el as HTMLElement),
    );
});

// Leave same-document fragments to the browser so native history, hashchange,
// scrolling, and focus behavior are preserved instead of starting a Turbo visit.
document.addEventListener('turbo:click', (e) => {
    const url = new URL(e.detail.url);

    if (
        url.origin !== window.location.origin ||
        url.pathname !== window.location.pathname ||
        url.search !== window.location.search ||
        !url.hash
    ) {
        return;
    }

    e.preventDefault();
});

// After a cross-page Turbo visit, correct its fragment handling when the URL
// contains encoded Unicode. ASCII fragments are left to Turbo's default path.
document.addEventListener('turbo:load', async () => {
    // Let Turbo finish rendering and applying its own scroll position first.
    await nextFrame();

    const element = getFragmentTarget(window.location.hash);

    if (!element || element.id === window.location.hash.slice(1)) return;

    // Move focus for accessibility without making headings permanent tab stops.
    const hadTabIndex = element.hasAttribute('tabindex');

    if (!hadTabIndex) element.tabIndex = -1;

    element.focus({ preventScroll: true });
    element.scrollIntoView();

    if (!hadTabIndex) element.removeAttribute('tabindex');
});

document.addEventListener('turbo:before-morph-element', (e) => {
    if (
        e.target instanceof FrameElement &&
        e.target.loading === 'lazy' &&
        e.target.complete
    ) {
        e.preventDefault();
    }
});

StreamActions.alert = function () {
    Waterhole.alerts.show(
        this.templateContent.firstElementChild as HTMLElement,
    );
};

StreamActions.redirect = function () {
    Turbo.visit(String(this.getAttribute('url')), { action: 'replace' });
};

StreamActions.dispatch = function () {
    const event = this.getAttribute('event');
    if (!event) return;

    const elements = this.target
        ? [document.getElementById(this.target)]
        : this.targets
          ? Array.from(document.querySelectorAll(this.targets))
          : [document.documentElement];

    elements
        .filter((element) => !!element)
        .forEach((element) => {
            element.dispatchEvent(new CustomEvent(event, { bubbles: true }));
        });
};

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
