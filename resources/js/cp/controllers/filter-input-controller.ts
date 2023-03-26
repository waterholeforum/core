import Combobox from '@github/combobox-nav';
import { Controller } from '@hotwired/stimulus';
import { set } from 'text-field-edit';

const RE_TOKEN = /([^\s"]*)"([^"]*)(?:"|$)|[^\s"]+/gi;

/**
 * Controller to hook up a filter input.
 *
 * A "filter input" is a combobox which allows entering a string of
 * space-separated "tokens". These tokens are suggested in a list, and the list
 * of suggestions is filtered according to the current token.
 *
 * For an example usage, see the CP users page.
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

    tokens() {
        const matches = this.inputTarget.value.matchAll(RE_TOKEN);
        return Array.from(matches).reverse();
    }

    currentToken() {
        const start = this.inputTarget.selectionStart || 0;
        return this.tokens().find(
            (match) =>
                match.index !== undefined &&
                match.index < start &&
                match.index + match[0].length >= start
        );
    }

    update() {
        const tokens = this.tokens();
        const token = this.currentToken();
        const query = token && token[0].toLowerCase();
        const children = Array.from(this.listTarget.children) as HTMLElement[];
        const prefixes: string[] = [];

        // Loop through the suggested and show/hide them based on what's entered
        // as the current token (the "query"). Hide tokens that are already
        // present in the search string. If the query is blank, only show the
        // first instance of a particular token prefix. Otherwise, only show
        // tokens that start with the query.
        children.forEach((el) => {
            const text = (el.dataset.value || el.textContent)?.trim().toLowerCase() || '';

            if (tokens.some((token) => token[0].toLowerCase() === text)) {
                el.hidden = true;
            } else if (query) {
                el.hidden = !text.startsWith(query) || query.includes(':') === text.endsWith(':');
            } else {
                el.hidden = prefixes.some((prefix) => text.startsWith(prefix));
                prefixes.push(text);
            }
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
