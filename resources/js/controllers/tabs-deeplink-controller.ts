import { Controller } from '@hotwired/stimulus';
import { TabsElement } from 'inclusive-elements';

export default class extends Controller<TabsElement> {
    connect() {
        this.element.addEventListener('change', this.onChange);
        this.element.addEventListener('click', this.onClick);
        window.addEventListener('hashchange', this.onHashChange);

        this.selectFromHash();
    }

    disconnect() {
        this.element.removeEventListener('change', this.onChange);
        this.element.removeEventListener('click', this.onClick);
        window.removeEventListener('hashchange', this.onHashChange);
    }

    private onClick = (event: Event) => {
        const target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        const tab = target.closest<HTMLElement>('[role="tab"]');

        if (!tab || !this.hashForTab(tab)) {
            return;
        }

        event.preventDefault();
    };

    private onChange = () => {
        this.updateHashFromSelectedTab();
    };

    private onHashChange = () => {
        this.selectFromHash();
    };

    private selectFromHash(): void {
        const hash = window.location.hash;

        if (!hash) {
            return;
        }

        const index = this.tabs.findIndex(
            (candidate) => this.hashForTab(candidate) === hash,
        );

        if (index === -1) {
            return;
        }

        const tab = this.tabs[index];

        if (tab.getAttribute('aria-selected') === 'true') {
            return;
        }

        this.element.selectTab(index);
    }

    private updateHashFromSelectedTab(): void {
        const selected = this.tabs.find(
            (tab) => tab.getAttribute('aria-selected') === 'true',
        );

        if (!selected) {
            return;
        }

        const hash = this.hashForTab(selected);

        if (!hash || hash === window.location.hash) {
            return;
        }

        history.replaceState(
            history.state,
            '',
            `${window.location.pathname}${window.location.search}${hash}`,
        );
    }

    private hashForTab(tab: HTMLElement): string | null {
        const href = tab.getAttribute('href');

        return href?.startsWith('#') ? href : null;
    }

    private get tabs(): HTMLElement[] {
        return Array.from(
            this.element.querySelectorAll<HTMLElement>('[role="tab"]'),
        );
    }
}
