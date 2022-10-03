import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { PopupElement } from 'inclusive-elements';
import { htmlToElement } from '../utils';

export default class extends Controller {
    static targets = ['badge', 'frame'];

    declare readonly hasBadgeTarget: boolean;
    declare readonly badgeTarget: HTMLElement;

    declare readonly hasFrameTarget: boolean;
    declare readonly frameTarget: FrameElement;

    declare readonly xsTarget: HTMLElement;

    get channel() {
        return 'Waterhole.Models.User.' + Waterhole.userId;
    }

    connect() {
        this.frameTarget.removeAttribute('disabled');

        window.Echo.private(this.channel).listen(
            'NotificationReceived',
            ({ unreadCount, html }: any) => {
                if (this.hasBadgeTarget) {
                    this.badgeTarget.hidden = !unreadCount;
                    this.badgeTarget.innerText = unreadCount;
                }

                if (this.hasFrameTarget) {
                    this.frameTarget.reload();
                }

                const alert = htmlToElement(html) as HTMLElement;
                if (alert) {
                    Waterhole.alerts.show(alert, { key: 'notification' });
                }

                Waterhole.documentTitle.increment();
            }
        );
    }

    disconnect() {
        window.Echo.leave(this.channel);
    }

    open(e: MouseEvent) {
        if (this.hasBadgeTarget) {
            this.badgeTarget.hidden = true;
        }

        Waterhole.alerts.dismiss('notification');

        if (getComputedStyle(this.xsTarget).display === 'none') {
            window.Turbo.visit((e.currentTarget as HTMLAnchorElement).href);
            (this.element as PopupElement).open = false;
        }
    }
}
