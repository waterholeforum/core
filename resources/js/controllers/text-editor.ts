import { subscribe } from '@github/paste-markdown';
import { ActionEvent, Controller } from '@hotwired/stimulus';
import fitTextarea from 'fit-textarea';
import TextareaEditor from 'textarea-editor';
import Suggestions from '../suggestions';

export class TextEditor extends Controller {
    static targets = ['input', 'preview', 'toolbar', 'previewButton'];

    inputTarget?: HTMLTextAreaElement;
    previewTarget?: HTMLDivElement;
    toolbarTarget?: HTMLElement;
    previewButtonTarget?: HTMLButtonElement;
    editor?: TextareaEditor;

    connect() {
        if (this.inputTarget) {
            // textarea-editor
            this.editor = new TextareaEditor(this.inputTarget);

            // fit-textarea
            fitTextarea.watch(this.inputTarget);

            // @github/paste-markdown
            subscribe(this.inputTarget);

            // @mentions
            new Suggestions(this.inputTarget, {
                character: '@',
                items: query => {
                    if (query.length < 2) return [];
                    return fetch(`/user-lookup?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(json => json.map(({ name, html }: any) => ({
                            html,
                            replacement: '@' + name,
                        })));
                },
                listboxClass: 'menu',
                optionClass: 'menu-item',
            });
        }
    }

    format(e: ActionEvent) {
        e.preventDefault();
        this.editor?.toggle(e.params.format);
    }

    togglePreview() {
        if (! this.inputTarget || ! this.previewTarget) return;

        const previewing = ! this.inputTarget.hidden;

        this.inputTarget.hidden = previewing;
        this.previewTarget.hidden = ! previewing;
        this.previewTarget.innerHTML = '<div class="loading-indicator"></div>';
        this.previewButtonTarget?.setAttribute('aria-pressed', String(previewing));

        if (this.toolbarTarget) {
            Array.from(this.toolbarTarget.children)
                .filter(el => el !== this.previewButtonTarget && el.className !== 'spacer')
                .forEach(el => (el as HTMLElement).hidden = previewing);
        }

        if (previewing) {
            fetch('/format', {
                method: 'POST',
                body: this.inputTarget.value,
            })
                .then(response => response.text())
                .then(text => {
                    this.previewTarget!.hidden = false;
                    this.previewTarget!.innerHTML = text;
                });
        }
    }
}
