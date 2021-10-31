import placement from 'placement.js';
import { insert } from 'text-field-edit';
import getCaretCoordinates from 'textarea-caret';
import { debounce } from '@github/mini-throttle';

export type Options = {
    items: (query: string) => Item[] | Promise<Item[]>;
    character?: string;
    listboxClass?: string;
    optionClass?: string;
    debounce?: number;
};

export type Item = {
    html: string;
    replacement: string;
};

export default class Suggestions {
    private textbox: HTMLTextAreaElement | HTMLInputElement;
    private options: Options;
    private suggestionStart?: number;
    private listbox?: HTMLDivElement;
    private query?: string;
    private debouncedLoad: (query: string) => void;

    constructor(textbox: HTMLTextAreaElement | HTMLInputElement, options: Options) {
        this.textbox = textbox;
        this.options = Object.assign({
            debounce: 200,
        }, options);

        this.debouncedLoad = debounce(this.load, this.options.debounce);

        textbox.addEventListener('keyup', this.handleKeyUp.bind(this) as any);
        textbox.addEventListener('click', this.update.bind(this));
        textbox.addEventListener('input', this.update.bind(this));
        textbox.addEventListener('focus', this.update.bind(this));
        textbox.addEventListener('keydown', this.handleKeyDown.bind(this) as any);
        textbox.addEventListener('blur', this.hide.bind(this));
    }

    private handleKeyUp(e: KeyboardEvent) {
        if (e.key !== 'Escape') {
            this.update();
        }
    }

    private update() {
        const start = this.textbox.selectionStart;

        // Search backwards from the cursor for a symbol. As long as
        // there is no space in the interim, we will show the
        // suggestions listbox if one is found.
        if (start !== null && this.textbox.selectionEnd === start) {
            for (let i = start - 1; i >= Math.max(0, start - 30); i--) {
                const char = this.textbox.value.substr(i, 1);
                if (char === this.options.character) {
                    this.suggestionStart = i;
                    const query = this.textbox.value.substring(i + 1, start);
                    if (this.query !== query) {
                        this.query = query;
                        this.debouncedLoad(query);
                    }
                    return;
                }
                if (char.match(/\s/) || i === 0) {
                    break;
                }
            }
        }

        this.hide();
    }

    private handleKeyDown(e: KeyboardEvent) {
        if (! this.listbox) return;

        switch (e.key) {
            case 'ArrowUp':
                this.navigate(-1);
                e.preventDefault();
                break;

            case 'ArrowDown':
                this.navigate(1);
                e.preventDefault();
                break;

            case 'Escape':
                this.hide();
                e.preventDefault();
                break;

            case 'Enter':
                this.listbox.querySelector<HTMLElement>('[aria-selected="true"]')?.click();
                e.preventDefault();
        }
    }

    private navigate(step: number) {
        if (! this.listbox) return;

        const current = this.listbox.querySelector('[aria-selected="true"]') || this.listbox.children[0];
        current.removeAttribute('aria-selected');

        const currentIndex = Array.from(this.listbox.children).indexOf(current);
        let newIndex = currentIndex + step;

        const max = this.listbox.children.length - 1;
        if (newIndex < 0) newIndex = max;
        else if (newIndex > max) newIndex = 0;

        this.listbox.children[newIndex].setAttribute('aria-selected', 'true');
    }

    private async load(query: string) {
        let items = this.options.items(query);

        if (items instanceof Promise) {
            items = await items;
        }

        this.show(items);
    }

    private show(items: any[]) {
        if (this.suggestionStart === undefined) return;

        if (! items.length) {
            this.hide();
            return;
        }

        if (! this.listbox) {
            this.listbox = document.createElement('div');
            this.listbox.className = this.options.listboxClass || '';
            document.body.appendChild(this.listbox);
        }

        this.listbox.innerHTML = '';
        this.listbox.append(
            ...items.map(item => {
                const option = document.createElement('button');
                option.className = this.options.optionClass || '';
                option.innerHTML = item.html;
                option.addEventListener('click', () => {
                    this.replace(item.replacement);
                });
                option.addEventListener('mousedown', e => e.preventDefault());
                return option;
            })
        );
        this.listbox.children[0].setAttribute('aria-selected', 'true');

        const { left, top, height } = getCaretCoordinates(this.textbox, this.suggestionStart + 1);
        const { x, y } = this.textbox.getBoundingClientRect();

        const anchor = new DOMRect(
            x + left,
            y + top - this.textbox.scrollTop,
            0,
            height
        );

        placement(anchor, this.listbox, {
            placement: 'bottom-start',
        });
    }

    private hide() {
        this.suggestionStart = undefined;
        this.query = undefined;

        if (this.listbox) {
            this.listbox.remove();
            this.listbox = undefined;
        }
    }

    private replace(replacement: string) {
        if (this.suggestionStart === undefined) return;

        this.textbox.selectionStart = this.suggestionStart;
        insert(this.textbox, replacement + ' ');
    }
}
