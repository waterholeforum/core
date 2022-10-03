import Combobox from '@github/combobox-nav';
import { Controller } from '@hotwired/stimulus';
import { set } from 'text-field-edit';

/**
 * Controller to hook up a filter input.
 */
export default class extends Controller {
    static targets = ['input', 'list'];

    declare readonly inputTarget: HTMLInputElement;
    declare readonly listTarget: HTMLElement;

    combobox?: Combobox;

    connect() {
        this.combobox = new Combobox(this.inputTarget, this.listTarget);
    }

    focus() {
        this.combobox?.start();
        this.update();
    }

    blur() {
        this.combobox?.stop();
        this.listTarget!.hidden = true;
    }

    currentToken() {
        const start = this.inputTarget.selectionStart || 0;
        const matches = this.inputTarget.value
            .slice(0, start)
            .matchAll(/([^\s"]*)"([^"]*)(?:"|$)|[^\s"]+/gi);
        return Array.from(matches)
            .reverse()
            .find(
                (match) =>
                    match.index !== undefined &&
                    match.index < start &&
                    match.index + match[0].length >= start
            );
    }

    update() {
        const token = this.currentToken();
        const query = token && token[0].toLowerCase();

        const children = Array.from(this.listTarget.children) as HTMLElement[];

        children.forEach((el) => {
            const text = (el.dataset.value || el.textContent)?.trim().toLowerCase() || '';
            const relevant =
                (!query && text.endsWith(':')) ||
                (query && text.startsWith(query) && query.includes(':') !== text.endsWith(':'));
            (el as HTMLElement).hidden = !relevant;
        });

        this.listTarget.hidden = !children.some((el) => !el.hidden);
    }

    commit(e: CustomEvent) {
        const el = e.target as HTMLElement;
        const token = this.currentToken();
        const replacement = (el.dataset.value || el.textContent)?.trim() || '';

        set(
            this.inputTarget,
            this.inputTarget.value.slice(0, token?.index) +
                replacement +
                (replacement.endsWith(':') ? '' : ' ')
        );
    }

    preventBlur(e: Event) {
        e.preventDefault();
    }
}
