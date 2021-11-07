import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import * as Turbo from '@hotwired/turbo';
import { install } from '@github/hotkey';

import './bootstrap';
// import './elements/turbo-echo-stream-tag';
import 'wicg-inert';
import '@github/text-expander-element'

import { persistResumableFields, restoreResumableFields, setForm } from '@github/session-resume';

let pageId: string;

function updatePageId() {
    pageId = window.location.pathname;
}

// Listen for all form submit events and to see if their default submission
// behavior is invoked.
window.addEventListener('submit', setForm, { capture: true });

window.addEventListener('pageshow', updatePageId);
window.addEventListener('pagehide', updatePageId);
window.addEventListener('turbo:load', updatePageId);


const restore = (e: Event) => {
    restoreResumableFields(pageId);
};
window.addEventListener('pageshow', restore);
window.addEventListener('turbo:load', restore);

const persist = (e: Event) => {
    persistResumableFields(pageId);
};
window.addEventListener('turbo:before-visit', persist);
window.addEventListener('popstate', persist);
window.addEventListener('pagehide', persist);


Turbo.start();

window.Turbo = Turbo;

document.addEventListener('turbo:submit-start', e => {
    const submitter = (e as any).detail.formSubmission.submitter;
    submitter.disabled = true;
    const popupButton = submitter.closest('ui-popup')?.children[0] as HTMLButtonElement;
    if (popupButton) {
        popupButton.disabled = true;
    }
});

document.addEventListener('turbo:submit-end', e => {
    const submitter = (e as any).detail.formSubmission.submitter;
    submitter.disabled = false;
});

document.addEventListener('turbo:before-fetch-response', async e => {
    const response = (e as any).detail.fetchResponse;
    if (response.statusCode >= 500 && response.statusCode <= 599) {
        const alert = (document.getElementById('fetch-error') as HTMLTemplateElement)?.content?.firstElementChild?.cloneNode(true) as HTMLElement;
        if (alert) {
            (document.getElementById('alerts') as AlertsElement).show(alert, { key: 'fetchError', duration: -1 });
        }
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

document.addEventListener('turbo:load', () => {
    document.querySelectorAll<HTMLElement>('[data-hotkey]').forEach(el => {
        install(el);
    });
});

import { Application } from '@hotwired/stimulus';
import { StreamElement } from '@hotwired/turbo/dist/types/elements';

window.Stimulus = Application.start();
const context = require.context('./controllers', true, /\.ts$/);
window.Stimulus.load(
    context.keys().map(key => ({
        identifier: (key.match(/^(?:\.\/)?(.+)(\..+?)$/) || [])[1],
        controllerConstructor: context(key).default,
    }))
);

import morphdom from 'morphdom';


declare global {
    interface Window {
        Turbo: any;
        Stimulus: Application;
        Echo: Echo;
    }
}

import { PopupElement, MenuElement, ModalElement, TooltipElement, AlertsElement } from 'inclusive-elements';
import Echo from 'laravel-echo';

window.customElements.define('ui-popup', PopupElement);
window.customElements.define('ui-menu', MenuElement);
window.customElements.define('ui-modal', ModalElement);
window.customElements.define('ui-tooltip', TooltipElement);
window.customElements.define('ui-alerts', AlertsElement);
