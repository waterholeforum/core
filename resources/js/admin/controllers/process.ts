import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';

export default class extends Controller {
    static targets = ['output', 'loading', 'done'];

    outputTarget?: FrameElement;
    loadingTargets?: HTMLElement[];
    doneTargets?: HTMLElement[];

    interval?: number;

    start() {
        this.outputTarget!.hidden = false;
        this.loadingTargets!.forEach(el => el.hidden = false);
        this.doneTargets!.forEach(el => el.hidden = true);

        this.interval = window.setInterval(() => {
            this.outputTarget!.disabled = false;
            this.outputTarget?.reload();
        }, 1000);
    }

    finish() {
        window.clearInterval(this.interval);
        this.outputTarget!.disabled = false;
        this.outputTarget!.reload();
        this.outputTarget!.disabled = true;
        this.loadingTargets!.forEach(el => el.hidden = true);
        this.doneTargets!.forEach(el => el.hidden = false);
        this.dispatch('finish');
    }

    outputTargetConnected(el: FrameElement) {
        el.addEventListener('turbo:frame-render', () => {
            el.scroll({ top: el.scrollHeight });
        });
    }
}
