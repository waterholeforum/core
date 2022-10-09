import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo';
import { PopupElement } from 'inclusive-elements';

export default class extends Controller {
    static targets = ['badge', 'frame', 'xs'];

    declare readonly badgeTarget: HTMLElement;
    declare readonly frameTarget: FrameElement;
    declare readonly xsTarget: HTMLElement;

    open(e: MouseEvent) {
        if (!this.badgeTarget.hidden) {
            this.badgeTarget.hidden = true;
            this.frameTarget.reload();
        }

        Waterhole.alerts.dismiss('notification');

        if (getComputedStyle(this.xsTarget).display === 'none') {
            window.Turbo.visit((e.currentTarget as HTMLAnchorElement).href);
            (this.element as PopupElement).open = false;
        }
    }
}
