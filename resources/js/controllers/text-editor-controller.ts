import { subscribe as pasteMarkdown } from '@github/paste-markdown';
import { ActionEvent, Controller } from '@hotwired/stimulus';
import { PopupElement } from 'inclusive-elements';
import TextareaEditorModule from 'textarea-editor';

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

    inputTargetConnected(input: HTMLInputElement) {
        this.editor = new TextareaEditor(input);
        this.pasteMarkdown = pasteMarkdown(input);
    }

    inputTargetDisconnected() {
        this.pasteMarkdown?.unsubscribe();
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

    async togglePreview() {
        if (!this.inputTarget || !this.previewTarget) return;

        const previewing = !this.inputTarget.hidden;

        this.inputTarget.hidden = previewing;
        this.previewTarget.hidden = !previewing;
        this.previewButtonTarget?.setAttribute(
            'aria-pressed',
            String(previewing),
        );
        this.element.classList.toggle('is-previewing', previewing);

        if (!previewing) return;

        this.previewTarget.setAttribute('aria-busy', 'true');
        this.previewTarget.innerHTML = await Waterhole.fetch
            .post(this.formatUrlValue, { body: this.inputTarget.value })
            .text();
        this.previewTarget.hidden = false;
        this.previewTarget.setAttribute('aria-busy', 'false');
    }

    insertQuote(e: CustomEvent) {
        if (!this.inputTarget || !this.editor) return;

        let text = (this.inputTarget.selectionStart > 0 ? '\n\n' : '') + '> ';

        this.editor.insert(
            text + e.detail.text.replace(/\n/g, '\n> ') + '\n\n',
        );
    }

    insertEmoji(e: CustomEvent) {
        this.editor?.insert(e.detail.unicode || '');
        (e.target as HTMLElement).closest<PopupElement>('ui-popup')!.open =
            false;
    }
}
