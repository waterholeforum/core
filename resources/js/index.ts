import { Application } from '@hotwired/stimulus';
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers';
import { AlertsElement } from '../../../../../packages/inclusive-elements';

import './bootstrap/alerts';
import './bootstrap/custom-elements';
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
}

window.Stimulus = Application.start();
window.Stimulus.load(definitionsFromContext(
    require.context('./controllers', true, /\.ts$/)
));
