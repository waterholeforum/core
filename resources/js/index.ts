import '@github/time-elements';
import { Application } from '@hotwired/stimulus';
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import { AlertsElement } from 'inclusive-elements';

import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/hotkeys';
import './bootstrap/turbo';

declare global {
    const Waterhole: Waterhole;

    interface Window {
        Stimulus: Application;
        Waterhole: Waterhole;
    }
}

export interface Waterhole {
    userId: number;
    alerts: AlertsElement;
    fetchError: (response: Response) => void;
    documentTitle: DocumentTitle;
    echoConfig: any;
    twemojiBase: string | null;
}

Object.defineProperty(Waterhole, 'alerts', {
    get: () => document.getElementById('alerts'),
});

window.Stimulus = Application.start();
window.Stimulus.load(definitionsFromContext(require.context('./controllers', true, /\.ts$/)));
