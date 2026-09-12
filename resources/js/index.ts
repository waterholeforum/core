import '../css/global/app.css';
import '@github/relative-time-element';
import { Application } from '@hotwired/stimulus';
import { AlertsElement } from 'inclusive-elements';
import type ky from 'ky';
import { type PhotoSwipeOptions } from 'photoswipe';

import './bootstrap/custom-elements';
import './bootstrap/document-title';
import './bootstrap/echo';
import './bootstrap/fetch';
import './bootstrap/turbo';
import { buildStimulusDefinitions } from './utils';

declare global {
    const Waterhole: Waterhole;

    interface Window {
        Stimulus: Application;
        Waterhole: Waterhole;
    }
}

export interface ShortcutPayload {
    id: string;
    keys: string[];
    scopes?: string[];
}

export interface Waterhole {
    userId: number;
    debug: boolean;
    messages: Record<string, string>;
    shortcuts: ShortcutPayload[];
    alerts: AlertsElement;
    fetch: typeof ky;
    fetchError: (response?: Response, data?: unknown) => Promise<void>;
    openLightbox?: (options: PhotoSwipeOptions) => void;
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
