import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { PopupElement } from 'inclusive-elements';
import { htmlToElement, shouldOpenInNewTab } from '../utils';

export default class extends Controller {
    static targets = ['badge', 'frame'];

    hasBadgeTarget?: boolean;
    badgeTarget?: HTMLElement;

    hasFrameTarget?: boolean;
    frameTarget?: FrameElement;

    connect() {
        window.Echo.private('Waterhole.Models.User.' + Waterhole.userId)
            .listen('NotificationReceived', ({ unreadCount, html }: any) => {
                if (this.hasBadgeTarget) {
                    this.badgeTarget!.hidden = ! unreadCount;
                    this.badgeTarget!.innerText = unreadCount;
                }

                if (this.hasFrameTarget) {
                    this.frameTarget!.reload();
                }

                const alert = htmlToElement(html) as HTMLElement;
                if (alert) {
                    Waterhole.alerts.show(alert, { key: 'notification' });
                }
            });
    }

    disconnect() {
        window.Echo.leave('Waterhole.Models.User.' + Waterhole.userId);
    }

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

        Waterhole.alerts.dismiss('notification');
    }
}
