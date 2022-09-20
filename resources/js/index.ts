import '@github/time-elements';
import { Application } from '@hotwired/stimulus';
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import { AlertsElement } from 'inclusive-elements';

import './bootstrap/alerts';
import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/hotkeys';
import './bootstrap/turbo';

import './elements/turbo-echo-stream-tag';

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
}

window.Stimulus = Application.start();
window.Stimulus.load(definitionsFromContext(require.context('./controllers', true, /\.ts$/)));
