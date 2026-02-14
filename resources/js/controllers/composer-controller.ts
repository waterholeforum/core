import { attach } from '@frsource/autoresize-textarea';
import { Controller } from '@hotwired/stimulus';
import { TurboFrameRenderEvent } from '@hotwired/turbo';
import animateScrollTo from 'animated-scroll-to';
import { shouldOpenInNewTab } from '../utils';

/**
 * Controller for the <x-waterhole::composer> component.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    private textareaAutoresize?: ReturnType<typeof attach>;
    private textarea?: HTMLTextAreaElement;

    connect() {
        this.textarea = this.element.querySelector('textarea') || undefined;

        this.enableTextareaAutosize();

        this.element.addEventListener('input', this.syncDraftState);
        this.element.addEventListener('change', this.syncDraftState);
        this.syncDraftState();

        window.addEventListener('hashchange', this.onHashChange);
        this.onHashChange();

        this.element.addEventListener('keydown', this.onKeydown);
    }

    disconnect() {
        this.disableTextareaAutosize();

        this.element.removeEventListener('input', this.syncDraftState);
        this.element.removeEventListener('change', this.syncDraftState);

        window.removeEventListener('hashchange', this.onHashChange);

        this.element.removeEventListener('keydown', this.onKeydown);
    }

    placeholderClick(e: MouseEvent) {
        if (shouldOpenInNewTab(e)) return;

        e.preventDefault();
        this.open();
    }

    async open() {
        const oldHeight = this.element.offsetHeight;

        this.element.classList.add('is-open');
        this.enableTextareaAutosize();
        this.textarea?.focus({ preventScroll: true });

        const newHeight = this.element.offsetHeight;

        if (!this.element.classList.contains('is-stuck')) {
            animateScrollTo(window.scrollY + newHeight - oldHeight, {
                minDuration: 200,
                maxDuration: 200,
            });
        }
    }

    close() {
        this.element.classList.remove('is-open');
        this.element.classList.add('was-open');

        if (window.location.hash === '#reply') {
            history.replaceState(null, '', ' ');
        }
    }

    frameRender(e: TurboFrameRenderEvent) {
        // If the composer frame, or the "reply to" frame renders, then open
        // the composer.
        const element = e.target as HTMLElement;
        if (
            element !== this.element &&
            !element.classList.contains('composer__parent')
        ) {
            return;
        }

        this.open();
    }

    startResize(e: PointerEvent) {
        e.preventDefault();

        const el = this.element;
        const startY = e.clientY;
        const startHeight = el.offsetHeight;
        const startBottom = el.getBoundingClientRect().bottom;

        el.classList.add('no-transition');

        const move = (e: PointerEvent) => {
            const height = startHeight - (e.clientY - startY);
            el.style.height = height + 'px';

            this.disableTextareaAutosize();

            window.scroll(
                0,
                window.scrollY +
                    el.getBoundingClientRect().bottom -
                    startBottom,
            );
        };

        document.addEventListener('pointermove', move);

        document.addEventListener(
            'pointerup',
            () => {
                el.classList.remove('no-transition');
                document.removeEventListener('pointermove', move);
            },
            { once: true },
        );
    }

    private onHashChange = () => {
        requestAnimationFrame(() => {
            if (window.location.hash === '#reply') {
                this.open();
            }
        });
    };

    private enableTextareaAutosize() {
        this.disableTextareaAutosize();

        if (!this.textarea) return;

        this.textarea.style.height = '';
        this.textareaAutoresize = attach(this.textarea);

        this.textarea?.addEventListener('input', this.syncComposerHeight);
        window.addEventListener('resize', this.syncComposerHeight);
        this.syncComposerHeight();
    }

    private disableTextareaAutosize() {
        this.textareaAutoresize?.detach();

        if (!this.textarea) return;

        this.textarea.style.maxHeight = 'none';
        this.textarea.style.height = '';
        this.textarea.removeEventListener('input', this.syncComposerHeight);
        window.removeEventListener('resize', this.syncComposerHeight);
    }

    private syncComposerHeight = () => {
        if (!this.element.classList.contains('is-open')) {
            return;
        }

        this.element.style.height = '';

        if (this.textarea) this.textarea.style.height = '';
        this.textareaAutoresize?.update();

        this.element.style.height = this.element.scrollHeight + 'px';
    };

    private syncDraftState = () => {
        this.element.classList.toggle(
            'has-draft',
            !!this.textarea?.value.trim(),
        );
    };

    private onKeydown = (e: KeyboardEvent) => {
        if (e.key !== 'Escape' || !this.element.classList.contains('is-open'))
            return;

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();
        this.close();
    };
}
