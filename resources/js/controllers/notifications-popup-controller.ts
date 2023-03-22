import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo';
import { PopupElement } from 'inclusive-elements';

/**
 * Controller for the notifications popup.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['badge', 'frame', 'sm'];

    declare readonly badgeTarget: HTMLElement;
    declare readonly frameTarget: FrameElement;
    declare readonly smTarget: HTMLElement;

    open(e: MouseEvent) {
        if (!this.badgeTarget.hidden) {
            this.badgeTarget.hidden = true;
            this.frameTarget.reload();
        }

        Waterhole.alerts.dismiss('notification');

        // If we're on a small display, close the popup and navigate to the
        // link's original target (the notifications page).
        if (getComputedStyle(this.smTarget).display === 'none') {
            window.Turbo.visit((e.currentTarget as HTMLAnchorElement).href);
            (this.element as PopupElement).open = false;
        }
    }
}
