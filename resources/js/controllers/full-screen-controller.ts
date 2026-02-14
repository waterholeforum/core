import { Controller } from '@hotwired/stimulus';

/**
 * Generic controller for toggling full-screen state on an element.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    connect() {
        this.element.addEventListener('keydown', this.onKeydown);
    }

    disconnect() {
        this.element.removeEventListener('keydown', this.onKeydown);
    }

    toggleFullScreen() {
        this.setFullScreen(!this.isFullScreen());
    }

    enterFullScreen() {
        this.setFullScreen(true);
    }

    exitFullScreen() {
        this.setFullScreen(false);
    }

    setFullScreen(enabled: boolean) {
        if (enabled === this.isFullScreen()) return;

        this.element.classList.toggle('is-full-screen', enabled);
        this.element.classList.toggle('was-full-screen', !enabled);

        this.element.dispatchEvent(
            new CustomEvent(
                enabled ? 'full-screen:enter' : 'full-screen:exit',
                { bubbles: true },
            ),
        );

        const done = () => this.element.classList.remove('was-full-screen');
        this.element.addEventListener('animationend', done, { once: true });
        this.element.addEventListener('animationcancel', done, { once: true });
    }

    private isFullScreen() {
        return this.element.classList.contains('is-full-screen');
    }

    private onKeydown = (e: KeyboardEvent) => {
        if (e.key !== 'Escape' || !this.isFullScreen()) return;

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();
        this.exitFullScreen();
    };
}
