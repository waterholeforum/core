import { Controller } from '@hotwired/stimulus';

/**
 * Controller to show an element's children as alerts.
 *
 * This is used in the layout template to move "flash" messages from a static
 * container into the main alerts container, so that they show with an
 * animation and disappear automatically.
 *
 * @internal
 */
export default class extends Controller {
    connect() {
        Array.from(this.element.children).forEach(el => {
            window.Waterhole.alerts.show(el as HTMLElement);
        });

        this.element.remove();
    }
}
