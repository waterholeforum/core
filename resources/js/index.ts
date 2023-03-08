import '@github/relative-time-element';
import { Application } from '@hotwired/stimulus';
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import { AlertsElement } from 'inclusive-elements';
import ky from 'ky';

import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/hotkeys';
import './bootstrap/turbo';
import { getCookie } from './utils';

declare global {
    const Waterhole: Waterhole;

    interface Window {
        Stimulus: Application;
        Waterhole: Waterhole;
    }
}

export interface Waterhole {
    userId: number;
    debug: boolean;
    alerts: AlertsElement;
    fetch: typeof ky;
    fetchError: (response?: Response) => void;
    documentTitle: DocumentTitle;
    echoConfig: any;
    twemojiBase: string | null;
}

Object.defineProperty(Waterhole, 'alerts', {
    get: () => document.getElementById('alerts'),
});

window.Stimulus = Application.start();
window.Stimulus.load(definitionsFromContext(require.context('./controllers', true, /\.ts$/)));

Waterhole.fetch = ky.create({
    headers: { 'X-XSRF-TOKEN': getCookie('XSRF-TOKEN') || undefined },
    hooks: {
        beforeError: [
            (error) => {
                Waterhole.fetchError(error.response);
                return error;
            },
        ],
    },
});
