import { Controller } from '@hotwired/stimulus';
import { slug } from '../../utils';

/**
 * Controller to automatically populate a slug field as you type.
 *
 *
 */
export default class extends Controller {
    static targets = ['slug', 'mirror'];

    declare readonly slugTarget: HTMLInputElement;
    declare readonly mirrorTargets: HTMLElement[];

    locked: boolean = false;

    updateName(e: Event) {
        const input = e.target as HTMLInputElement;

        if (!this.locked) {
            this.slugTarget.value = slug(input.value);
            this.mirror();
        }
    }

    updateSlug(e: Event) {
        const input = e.target as HTMLInputElement;

        this.mirror();

        this.locked = Boolean(input.value);
    }

    mirror() {
        this.mirrorTargets.forEach((el) => {
            el.textContent = this.slugTarget.value;
        });
    }
}
