import { Controller } from '@hotwired/stimulus';
import { slug } from '../../utils';

/**
 * Controller to automatically populate a slug field as you type in a name.
 *
 * Call the `updateName` action on the name input, and the `updateSlug` action
 * on the slug input. Target the slug input as `slug`, and any elements that
 * should mirror the slug value (eg. a URL preview) as `mirror`.
 *
 * For an example usage, see the `ChannelName` and `ChannelSlug` form fields.
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
