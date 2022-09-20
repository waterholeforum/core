import { subscribe } from '@github/paste-markdown';
import TextExpanderElement from '@github/text-expander-element';
import { ActionEvent, Controller } from '@hotwired/stimulus';
import TextareaEditor from 'textarea-editor';

export default class extends Controller {
    static targets = ['input', 'preview', 'previewButton', 'expander', 'hotkeyLabel'];

    inputTarget?: HTMLTextAreaElement;
    previewTarget?: HTMLDivElement;
    previewButtonTarget?: HTMLButtonElement;
    expanderTarget?: TextExpanderElement;
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
                    fetch(`/user-lookup?q=${encodeURIComponent(text)}`)
                        .then((response) => response.json())
                        .then((json) => {
                            const listbox = document.createElement('ul');
                            listbox.setAttribute('role', 'listbox');
                            listbox.className = 'menu';
                            listbox.style.position = 'absolute';
                            listbox.style.marginTop = '24px';

                            listbox.append(
                                ...json.map(({ name, html }: any) => {
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
                event.detail.value = '@' + item.getAttribute('data-value');
            }) as EventListener);
        }
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

    togglePreview() {
        if (!this.inputTarget || !this.previewTarget) return;

        const previewing = !this.inputTarget.hidden;

        this.inputTarget.hidden = previewing;
        this.previewTarget.hidden = !previewing;
        this.previewTarget.innerHTML = '<div class="loading"></div>';
        this.previewButtonTarget?.setAttribute('aria-pressed', String(previewing));
        this.element.classList.toggle('is-previewing', previewing);

        if (previewing) {
            fetch('/format', {
                method: 'POST',
                body: this.inputTarget.value,
            }).then(async (response) => {
                if (!response.ok) {
                    window.Waterhole.fetchError(response);
                } else {
                    const text = await response.text();
                    this.previewTarget!.hidden = false;
                    this.previewTarget!.innerHTML = text;
                }
            });
        }
    }

    insertQuote(e: CustomEvent) {
        if (!this.inputTarget || !this.editor) return;

        let text = (this.inputTarget.selectionStart > 0 ? '\n\n' : '') + '> ';

        this.editor.insert(text + e.detail.text.replace(/\n/g, '\n> ') + '\n\n');
    }
}
