import { subscribe as pasteMarkdown } from '@github/paste-markdown';
import { ActionEvent, Controller } from '@hotwired/stimulus';
import { PopupElement } from 'inclusive-elements';
import TextareaEditorModule from 'textarea-editor';
import { promiseTimeout } from '../utils';

const TextareaEditor =
    (TextareaEditorModule as any).default ?? TextareaEditorModule;

/**
 * Controller for the <x-waterhole::text-editor> component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['input', 'preview', 'previewButton', 'hotkeyLabel'];

    static values = {
        formatUrl: String,
    };

    declare readonly inputTarget: HTMLTextAreaElement;
    declare readonly previewTarget: HTMLDivElement;
    declare readonly previewButtonTarget: HTMLButtonElement;

    declare readonly formatUrlValue: string;

    private editor?: typeof TextareaEditor;
    private pasteMarkdown?: ReturnType<typeof pasteMarkdown>;

    private previewTimeout?: number;
    private previewing = false;
    private lastPreviewValue?: string;
    private previewRequestId = 0;

    connect() {
        this.previewing = this.element.classList.contains('is-previewing');
    }

    disconnect() {
        this.clearPreviewTimeout();
    }

    inputTargetConnected(input: HTMLTextAreaElement) {
        this.editor = new TextareaEditor(input);
        this.pasteMarkdown = pasteMarkdown(input);
        input.addEventListener('input', this.inputChanged);
    }

    inputTargetDisconnected(input: HTMLTextAreaElement) {
        this.pasteMarkdown?.unsubscribe();
        input.removeEventListener('input', this.inputChanged);
    }

    hotkeyLabelTargetConnected(element: HTMLElement) {
        const mac = navigator.userAgent.match(/Macintosh/);
        element.innerText = element.innerText
            .split('+')
            .map((part) => {
                if (part === 'Meta') return mac ? '⌘' : 'Ctrl';
                if (part === 'Shift' && mac) return '⇧';
                if (part === 'Alt' && mac) return '⌥';
                if (part.length === 1) return part.toUpperCase();
                return part;
            })
            .join(mac ? '' : '-');
    }

    format(e: ActionEvent) {
        e.preventDefault();
        this.editor?.toggle(e.params.format);
    }

    async insertQuote(e: CustomEvent) {
        // Wait for composer textarea to be ready and focused
        await promiseTimeout(200);

        if (!this.inputTarget || !this.editor) return;

        let text = (this.inputTarget.selectionStart > 0 ? '\n\n' : '') + '> ';

        this.editor.insert(
            text + e.detail.text.replace(/\n/g, '\n> ').trim() + '\n\n',
        );
    }

    insertEmoji(e: CustomEvent) {
        this.editor?.insert(e.detail.unicode || '');
        (e.target as HTMLElement).closest<PopupElement>('ui-popup')!.open =
            false;
    }

    togglePreview() {
        this.setPreviewing(!this.previewing);
    }

    fullScreenEnter() {
        this.setPreviewing(true);
        this.focusInput();
    }

    fullScreenExit() {
        this.setPreviewing(false);
        this.focusInput();
    }

    private setPreviewing(previewing: boolean) {
        if (this.previewing === previewing) return;

        this.previewing = previewing;

        this.previewButtonTarget?.setAttribute(
            'aria-pressed',
            String(this.previewing),
        );
        this.element.classList.toggle('is-previewing', this.previewing);

        if (!this.previewing) {
            this.clearPreviewTimeout();
            this.previewRequestId++;
            return;
        }

        this.refreshPreview();
    }

    private async refreshPreview() {
        const value = this.inputTarget.value;
        if (value === this.lastPreviewValue) return;

        const requestId = ++this.previewRequestId;

        const html = await Waterhole.fetch
            .post(this.formatUrlValue, { body: value })
            .text();

        if (requestId !== this.previewRequestId || !this.previewing) {
            return;
        }

        this.previewTarget.innerHTML = html;
        this.lastPreviewValue = value;
    }

    private inputChanged = () => {
        if (!this.previewing) return;
        this.clearPreviewTimeout();
        this.previewTimeout = window.setTimeout(() => {
            this.refreshPreview();
        }, 500);
    };

    private clearPreviewTimeout() {
        window.clearTimeout(this.previewTimeout);
    }

    private focusInput() {
        requestAnimationFrame(() => {
            this.inputTarget?.focus({ preventScroll: true });
        });
    }
}
