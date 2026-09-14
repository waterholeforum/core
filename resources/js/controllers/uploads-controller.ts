import { Controller } from '@hotwired/stimulus';
import TextareaEditorModule from 'textarea-editor';

const TextareaEditor =
    (TextareaEditorModule as any).default ?? TextareaEditorModule;

/**
 * Controller to power file uploads in the text editor.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    static targets = ['input'];

    static values = {
        url: String,
    };

    declare readonly inputTarget: HTMLTextAreaElement;
    declare readonly urlValue: string;

    private editor?: typeof TextareaEditor;

    connect() {
        this.editor = new TextareaEditor(this.inputTarget);

        this.inputTarget.addEventListener('drop', this.onDrop);
        this.inputTarget.addEventListener('paste', this.onPaste);
    }

    disconnect() {
        this.inputTarget.removeEventListener('drop', this.onDrop);
        this.inputTarget.removeEventListener('paste', this.onPaste);
    }

    private onDrop = (e: DragEvent) => {
        if (e.dataTransfer?.files.length) {
            e.preventDefault();
            Array.from(e.dataTransfer.files).forEach((file) =>
                this.uploadFile(file),
            );
        }
    };

    private onPaste = (e: ClipboardEvent) => {
        if (e.clipboardData?.files.length) {
            e.preventDefault();
            Array.from(e.clipboardData.files).forEach((file) =>
                this.uploadFile(file),
            );
        }
    };

    chooseFiles() {
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = true;
        input.hidden = true;
        document.body.appendChild(input);
        input.addEventListener('change', () => {
            if (input.files) {
                Array.from(input.files).forEach((file) =>
                    this.uploadFile(file),
                );
            }
        });
        input.click();
        input.remove();
    }

    async uploadFile(file: File) {
        if (!this.editor) return;

        const prefix = file.type.startsWith('image/') ? '!' : '';
        const placeholder = `${prefix}[Uploading ${file.name}]()`;
        const [selectionStart, selectionEnd] = this.editor.range();
        const before = this.inputTarget.value.slice(0, selectionStart);
        const after = this.inputTarget.value.slice(selectionEnd);
        let separator = '';

        if (before && !before.endsWith('\n\n')) {
            separator = before.endsWith('\n') ? '\n' : '\n\n';
        }

        // Reuse up to two existing newlines and leave the cursor in the next paragraph.
        this.editor
            .range([
                selectionStart,
                selectionEnd + after.match(/^\n{0,2}/)![0].length,
            ])
            .insert(separator + placeholder + '\n\n');

        const body = new FormData();
        body.append('file', file);
        let replacement = '';

        try {
            const data = await Waterhole.fetch
                .post(this.urlValue, { body })
                .json<{ url: string }>();

            replacement = `${prefix}[${file.name}](${data.url})`;
        } catch (e) {}

        const start = this.inputTarget.value.indexOf(placeholder);
        if (start === -1) return;

        const delta = replacement.length - placeholder.length;
        const range = this.editor.range().map((position: number) => {
            if (position <= start) return position;
            if (position >= start + placeholder.length) return position + delta;
            return start + replacement.length;
        });
        this.editor
            .range([start, start + placeholder.length])
            .insert(replacement)
            .range(range);
    }
}
