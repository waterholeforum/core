import '../css/global/app.css';
import '@github/relative-time-element';
import { Application } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';
import ky from 'ky';

import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/turbo';
import { buildStimulusDefinitions, getCookie } from './utils';

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
    messages: Record<string, string>;
    alerts: AlertsElement;
    fetch: typeof ky;
    fetchError: (response?: Response) => void;
    documentTitle: DocumentTitle;
    echoConfig: any;
}

Object.defineProperty(Waterhole, 'alerts', {
    get: () => document.getElementById('alerts'),
});

window.Stimulus = Application.start();

window.Stimulus.load(
    buildStimulusDefinitions(
        import.meta.glob('./controllers/**/*.ts', { eager: true }),
    ),
);

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
