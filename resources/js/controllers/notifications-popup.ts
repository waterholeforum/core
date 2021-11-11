import { Controller } from '@hotwired/stimulus';
import { PopupElement } from '../../../../../../packages/inclusive-elements';
import { shouldOpenInNewTab } from '../utils';

export default class extends Controller {
    static targets = ['badge'];

    hasBadgeTarget?: boolean;
    badgeTarget?: HTMLElement;

    open(e: MouseEvent) {
        // TODO: maybe move this functionality into inclusive-elements
        if (shouldOpenInNewTab(e)) {
            (this.element as PopupElement).open = false;
        } else {
            e.preventDefault();
        }

        if (this.hasBadgeTarget) {
            this.badgeTarget!.hidden = true;
        }
    }
}
