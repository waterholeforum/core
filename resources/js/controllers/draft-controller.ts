import { Controller } from '@hotwired/stimulus';
import { TurboSubmitEndEvent, TurboSubmitStartEvent } from '@hotwired/turbo';

type DraftState = {
    lastSnapshot: string;
    pendingSnapshot?: string;
    submitting: boolean;
};

/**
 * Draft controller for forms.
 *
 * @internal
 */
export default class DraftController extends Controller<HTMLFormElement> {
    static targets = ['saveButton', 'saving', 'saved', 'error'];
    private static states = new WeakMap<HTMLFormElement, DraftState>();

    declare readonly saveButtonTarget: HTMLButtonElement;
    declare readonly savingTarget: HTMLElement;
    declare readonly savedTarget: HTMLElement;
    declare readonly errorTarget: HTMLElement;

    private timeout?: number;

    connect() {
        const state = this.getState();

        if (this.snapshot() !== state.lastSnapshot && !state.submitting) {
            this.queue();
        }
    }

    disconnect() {
        window.clearTimeout(this.timeout);
    }

    queue() {
        window.clearTimeout(this.timeout);

        this.timeout = window.setTimeout(() => void this.save(), 1500);

        if (this.snapshot() !== this.getState().lastSnapshot) {
            this.showState(null);
        }
    }

    saveNow() {
        window.clearTimeout(this.timeout);

        this.save();
    }

    submitStart(e: TurboSubmitStartEvent) {
        const state = this.getState();
        state.submitting = true;

        if (!this.isSaveSubmission(e)) {
            return;
        }

        state.pendingSnapshot = this.snapshot();
        this.showState('saving');
    }

    submitEnd(e: TurboSubmitEndEvent) {
        const state = this.getState();
        state.submitting = false;

        if (!this.isSaveSubmission(e)) {
            return;
        }

        if (!e.detail.success) {
            this.showState('error');
            return;
        }

        state.lastSnapshot = state.pendingSnapshot ?? this.snapshot();
        state.pendingSnapshot = undefined;
        this.showState('saved');

        const snapshot = this.snapshot();
        if (snapshot !== state.lastSnapshot) {
            this.queue();
        }
    }

    private save() {
        const state = this.getState();

        if (state.submitting) {
            return;
        }

        const snapshot = this.snapshot();
        if (snapshot === state.lastSnapshot) {
            return;
        }

        state.pendingSnapshot = snapshot;
        this.showState('saving');
        this.saveButtonTarget.click();
    }

    private formData(): FormData {
        const formData = new FormData(this.element);
        const filtered = new FormData();

        for (const [name, value] of formData.entries()) {
            if (
                !['_token', '_method', 'commit', 'draft_action'].includes(name)
            ) {
                filtered.append(name, value);
            }
        }

        return filtered;
    }

    private snapshot(): string {
        const params = new URLSearchParams();

        for (const [name, value] of this.formData().entries()) {
            params.append(name, typeof value === 'string' ? value : value.name);
        }

        return params.toString();
    }

    private isSaveSubmission(e: CustomEvent): boolean {
        return e.detail.formSubmission?.submitter === this.saveButtonTarget;
    }

    private showState(state: 'saving' | 'saved' | 'error' | null) {
        this.savingTarget.hidden = state !== 'saving';
        this.savedTarget.hidden = state !== 'saved';
        this.errorTarget.hidden = state !== 'error';
    }

    private getState(): DraftState {
        const existing = DraftController.states.get(this.element);

        if (existing) {
            return existing;
        }

        const state = {
            lastSnapshot: this.snapshot(),
            submitting: false,
        };

        DraftController.states.set(this.element, state);

        return state;
    }
}
