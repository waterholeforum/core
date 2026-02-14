import { install, uninstall } from '@github/hotkey';
import { Controller } from '@hotwired/stimulus';

/**
 * Controller for installing a keyboard shortcut on an element.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    connect() {
        install(this.element);
    }

    disconnect() {
        uninstall(this.element);
    }
}
