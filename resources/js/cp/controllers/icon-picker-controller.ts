import { Controller } from '@hotwired/stimulus';
import { Picker } from 'emoji-picker-element';
import { PopupElement } from 'inclusive-elements';

/**
 * Controller for the <x-waterhole::icon-picker> component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['preview', 'form', 'emoji'];

    declare readonly previewTarget: HTMLElement;
    declare readonly formTarget: HTMLElement;
    declare readonly emojiPickerTarget: Picker;

    change() {
        this.previewTarget.hidden = true;
        this.formTarget.hidden = false;
    }

    emojiTargetConnected(el: HTMLElement) {
        const input = el.querySelector('input')!;
        const popup = el.querySelector<PopupElement>('ui-popup')!;
        const button = el.querySelector('button')!;
        const picker = el.querySelector('emoji-picker')!;

        input.type = 'hidden';
        popup.hidden = false;

        picker.addEventListener('emoji-click', (e) => {
            input.value = e.detail.unicode || '';
            button.innerHTML = input.value;
            popup.open = false;
        });
    }
}
