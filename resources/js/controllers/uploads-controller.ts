import { Controller } from '@hotwired/stimulus';
import TextareaEditor from 'textarea-editor';

/**
 * Controller to power file uploads in the text editor.
 *
 * @internal
 */
export default class extends Controller<HTMLTextAreaElement> {
    static values = {
        url: String,
    };

    declare readonly urlValue: string;

    private editor?: TextareaEditor;

    connect() {
        this.editor = new TextareaEditor(this.element);

        this.element.addEventListener('drop', this.onDrop);
        this.element.addEventListener('paste', this.onPaste);
    }

    disconnect() {
        this.element.removeEventListener('drop', this.onDrop);
        this.element.removeEventListener('paste', this.onPaste);
    }

    private onDrop = (e: DragEvent) => {
        if (e.dataTransfer?.files.length) {
            e.preventDefault();
            Array.from(e.dataTransfer.files).forEach((file) => this.uploadFile(file));
        }
    };

    private onPaste = (e: ClipboardEvent) => {
        if (e.clipboardData?.files.length) {
            e.preventDefault();
            Array.from(e.clipboardData.files).forEach((file) => this.uploadFile(file));
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
                Array.from(input.files).forEach((file) => this.uploadFile(file));
            }
        });
        input.click();
        input.remove();
    }

    async uploadFile(file: File) {
        const prefix = file.type.startsWith('image/') ? '!' : '';
        const placeholder = `${prefix}[Uploading ${file.name}]()\n`;
        let replacement = '';

        this.editor?.insert(placeholder);

        const body = new FormData();
        body.append('file', file);

        try {
            const data = await Waterhole.fetch
                .post(this.urlValue, { body })
                .json<{ url: string }>();

            replacement = `${prefix}[${file.name}](${data.url})\n`;
        } catch (e) {}

        const start = this.element.value.indexOf(placeholder);
        if (start === -1 || !this.editor) return;

        const delta = replacement.length - placeholder.length;
        const range = this.editor.range();
        this.editor
            .range([start, start + placeholder.length])
            .insert(replacement)
            .range([range[0] + delta, range[1] + delta]);
    }
}
