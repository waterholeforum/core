import { subscribe } from '@github/paste-markdown';
import TextExpanderElement from '@github/text-expander-element';
import { ActionEvent, Controller } from '@hotwired/stimulus';
import { Picker } from 'emoji-picker-element';
import { PopupElement } from 'inclusive-elements';
import TextareaEditor from 'textarea-editor';
import { cloneFromTemplate } from '../utils';

interface UserLookupResult {
    id: number;
    name: string;
    html: string;
}

/**
 * Controller for the <x-waterhole::text-editor> component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = [
        'input',
        'preview',
        'previewButton',
        'expander',
        'hotkeyLabel',
        'emojiPicker',
    ];

    static values = {
        formatUrl: String,
        userLookupUrl: String,
        uploadUrl: String,
    };

    declare readonly inputTarget: HTMLTextAreaElement;
    declare readonly previewTarget: HTMLDivElement;
    declare readonly previewButtonTarget: HTMLButtonElement;
    declare readonly expanderTarget: TextExpanderElement;

    declare readonly formatUrlValue: string;
    declare readonly userLookupUrlValue: string;
    declare readonly uploadUrlValue: string;

    editor?: TextareaEditor;

    connect() {
        if (this.inputTarget) {
            // textarea-editor
            this.editor = new TextareaEditor(this.inputTarget);

            // @github/paste-markdown
            subscribe(this.inputTarget);

            // @github/text-expander-element
            this.expanderTarget?.addEventListener('text-expander-change', ((event: CustomEvent) => {
                const { provide, text } = event.detail;

                if (text.length < 2) return;

                provide(
                    Waterhole.fetch(this.userLookupUrlValue + `?q=${encodeURIComponent(text)}`)
                        .json<UserLookupResult[]>()
                        .then((json) => {
                            const listbox = document.createElement('ul');
                            listbox.setAttribute('role', 'listbox');
                            listbox.className = 'menu';
                            listbox.style.position = 'absolute';
                            listbox.style.marginTop = '24px';

                            listbox.append(
                                ...json.map(({ name, html }) => {
                                    const option = document.createElement('li');
                                    option.setAttribute('role', 'option');
                                    option.id = `suggestion-${Math.floor(
                                        Math.random() * 100000
                                    ).toString()}`;
                                    option.className = 'menu-item';
                                    option.dataset.value = name;
                                    option.innerHTML = html;
                                    return option;
                                })
                            );

                            const observer = new MutationObserver(() => {
                                if (listbox.getBoundingClientRect().bottom > window.innerHeight) {
                                    listbox.style.transform = 'translateY(-100%)';
                                    listbox.style.marginTop = '-12px';
                                }
                            });

                            observer.observe(listbox, {
                                attributes: true,
                                attributeFilter: ['style'],
                            });

                            return {
                                matched: Boolean(json.length),
                                fragment: listbox,
                            };
                        })
                );
            }) as EventListener);

            this.expanderTarget?.addEventListener('text-expander-value', ((event: CustomEvent) => {
                const { item } = event.detail;
                event.detail.value = '@' + item.getAttribute('data-value').replace(/ /g, '\xa0');
            }) as EventListener);

            // File uploads
            this.inputTarget.addEventListener('drop', (e) => {
                if (e.dataTransfer?.files.length) {
                    e.preventDefault();
                    Array.from(e.dataTransfer.files).forEach((file) => this.uploadFile(file));
                }
            });

            this.inputTarget.addEventListener('paste', (e) => {
                if (e.clipboardData?.files.length) {
                    e.preventDefault();
                    Array.from(e.clipboardData.files).forEach((file) => this.uploadFile(file));
                }
            });
        }
    }

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
                .post(this.uploadUrlValue, { body })
                .json<{ url: string }>();

            replacement = `${prefix}[${file.name}](${data.url})\n`;
        } catch (e) {}

        const start = this.inputTarget.value.indexOf(placeholder);
        if (start === -1 || !this.editor) return;

        const delta = replacement.length - placeholder.length;
        const range = this.editor.range();
        this.editor
            .range([start, start + placeholder.length])
            .insert(replacement)
            .range([range[0] + delta, range[1] + delta]);
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
        this.previewTarget.replaceChildren(cloneFromTemplate('loading'));
        this.previewButtonTarget?.setAttribute('aria-pressed', String(previewing));
        this.element.classList.toggle('is-previewing', previewing);

        if (!previewing) return;

        this.previewTarget!.innerHTML = await Waterhole.fetch
            .post(this.formatUrlValue, { body: this.inputTarget.value })
            .text();
        this.previewTarget!.hidden = false;
    }

    insertQuote(e: CustomEvent) {
        if (!this.inputTarget || !this.editor) return;

        let text = (this.inputTarget.selectionStart > 0 ? '\n\n' : '') + '> ';

        this.editor.insert(text + e.detail.text.replace(/\n/g, '\n> ') + '\n\n');
    }

    emojiPickerTargetConnected(el: Picker) {
        el.addEventListener('emoji-click', (e) => {
            this.editor?.insert(e.detail.unicode || '');
            el.closest<PopupElement>('ui-popup')!.open = false;
        });
    }
}
