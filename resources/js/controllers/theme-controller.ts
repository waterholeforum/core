import { Controller } from '@hotwired/stimulus';

const STORAGE_KEY = 'theme';

/**
 * Controller for the <x-waterhole::theme-selector> component.
 *
 * @internal
 */
export default class extends Controller {
    connect() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem(STORAGE_KEY)) {
                this.apply(e.matches ? 'dark' : 'light');
            }
        });

        this.updateMenuItems();
    }

    set({ params: { name } }: any) {
        if (!name) {
            localStorage.removeItem(STORAGE_KEY);
            this.apply(matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        } else {
            localStorage.setItem(STORAGE_KEY, name);
            this.apply(name);
        }

        this.updateMenuItems();
        this.dispatch('change', { detail: { name } });
    }

    apply(name: 'dark' | 'light') {
        document.documentElement.dataset.theme = name;
    }

    updateMenuItems() {
        const saved = localStorage.getItem(STORAGE_KEY) || '';
        this.element.querySelectorAll('[data-theme-name-param]').forEach((el) => {
            el.setAttribute(
                'aria-checked',
                el.getAttribute('data-theme-name-param') === saved ? 'true' : 'false'
            );
        });
    }
}
