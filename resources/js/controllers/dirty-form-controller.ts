import { Controller } from '@hotwired/stimulus';
import { TurboBeforeVisitEvent, TurboSubmitEndEvent } from '@hotwired/turbo';

type DirtyNavigationEvent = Event & {
    dirtyFormHandled?: boolean;
};

/**
 * Controller to warn before navigating away from forms with unsaved changes.
 *
 * @internal
 */
export default class extends Controller<HTMLFormElement> {
    static values = {
        confirmMessage: String,
    };

    declare readonly hasConfirmMessageValue: boolean;
    declare readonly confirmMessageValue: string;

    private baselineSnapshot = '';

    connect() {
        this.baselineSnapshot = this.snapshot();

        this.element.addEventListener('turbo:submit-end', this.onSubmitEnd);

        window.addEventListener('beforeunload', this.onBeforeUnload);
        document.addEventListener('turbo:before-visit', this.onBeforeVisit);
        document.addEventListener(
            'waterhole:before-modal-close',
            this.onBeforeModalClose,
        );
    }

    disconnect() {
        this.element.removeEventListener('turbo:submit-end', this.onSubmitEnd);

        window.removeEventListener('beforeunload', this.onBeforeUnload);
        document.removeEventListener('turbo:before-visit', this.onBeforeVisit);
        document.removeEventListener(
            'waterhole:before-modal-close',
            this.onBeforeModalClose,
        );
    }

    markClean() {
        this.baselineSnapshot = this.snapshot();
    }

    private onSubmitEnd = (e: TurboSubmitEndEvent) => {
        if (e.detail.success) {
            this.markClean();
        }
    };

    private onBeforeUnload = (e: BeforeUnloadEvent) => {
        if (this.isDirty()) {
            e.preventDefault();
        }
    };

    private onBeforeVisit = (e: TurboBeforeVisitEvent) => {
        const navigationEvent = e as DirtyNavigationEvent;
        if (navigationEvent.dirtyFormHandled || e.defaultPrevented) return;
        if (!this.isDirty()) return;

        const url = new URL(e.detail.url, window.location.href);
        if (
            url.origin === window.location.origin &&
            url.pathname === window.location.pathname &&
            url.search === window.location.search
        ) {
            return;
        }

        navigationEvent.dirtyFormHandled = true;

        if (!window.confirm(this.message())) {
            e.preventDefault();
        }
    };

    private onBeforeModalClose = (e: Event) => {
        const navigationEvent = e as DirtyNavigationEvent;
        if (navigationEvent.dirtyFormHandled || e.defaultPrevented) return;
        if (!(e.target instanceof Element)) return;
        if (!e.target.contains(this.element)) return;
        if (!this.isDirty()) return;

        navigationEvent.dirtyFormHandled = true;

        if (!window.confirm(this.message())) {
            e.preventDefault();
        }
    };

    private isDirty(): boolean {
        return this.snapshot() !== this.baselineSnapshot;
    }

    private snapshot(): string {
        const params = new URLSearchParams();

        for (const [name, value] of new FormData(this.element).entries()) {
            if (['_token', '_method'].includes(name)) continue;
            params.append(name, typeof value === 'string' ? value : value.name);
        }

        return params.toString();
    }

    private message(): string {
        return this.hasConfirmMessageValue
            ? this.confirmMessageValue
            : Waterhole.messages?.[
                  'waterhole::system.unsaved-changes-confirm-message'
              ] ||
                  'You have unsaved changes. Are you sure you want to leave this page?';
    }
}
