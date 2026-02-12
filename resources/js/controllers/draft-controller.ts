import { Controller } from '@hotwired/stimulus';

/**
 * Draft controller for forms.
 *
 * @internal
 */
export default class extends Controller<HTMLFormElement> {
    static targets = ['saveButton', 'saving', 'saved', 'error'];

    declare readonly saveButtonTarget: HTMLButtonElement;
    declare readonly savingTarget: HTMLElement;
    declare readonly savedTarget: HTMLElement;
    declare readonly errorTarget: HTMLElement;

    private timeout?: number;
    private lastSnapshot = '';
    private pendingSnapshot?: string;
    private submitting = false;

    connect() {
        this.lastSnapshot = this.snapshot();
    }

    disconnect() {
        window.clearTimeout(this.timeout);
    }

    queue() {
        window.clearTimeout(this.timeout);

        this.timeout = window.setTimeout(() => void this.save(), 1500);

        if (this.snapshot() !== this.lastSnapshot) {
            this.showState(null);
        }
    }

    saveNow() {
        window.clearTimeout(this.timeout);

        this.save();
    }

    submitStart(e: CustomEvent) {
        if (!this.isSaveSubmission(e)) {
            return;
        }

        this.submitting = true;
        this.pendingSnapshot = this.snapshot();
        this.showState('saving');
    }

    submitEnd(e: CustomEvent) {
        if (!this.isSaveSubmission(e)) {
            return;
        }

        this.submitting = false;

        if (!e.detail.success) {
            this.showState('error');
            return;
        }

        this.lastSnapshot = this.pendingSnapshot ?? this.snapshot();
        this.pendingSnapshot = undefined;
        this.showState('saved');

        const snapshot = this.snapshot();
        if (snapshot !== this.lastSnapshot) {
            this.queue();
        }
    }

    private save() {
        if (this.submitting) {
            return;
        }

        const snapshot = this.snapshot();
        if (snapshot === this.lastSnapshot) {
            return;
        }

        this.pendingSnapshot = snapshot;
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
}
